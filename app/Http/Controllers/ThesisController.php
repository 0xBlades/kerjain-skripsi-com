<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Thesis;

class ThesisController extends Controller
{
    /**
     * Display a listing of the user's theses.
     */
    public function index()
    {
        $theses = Thesis::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('features.thesis.index', compact('theses'));
    }

    /**
     * Show the form for creating a new thesis.
     */
    public function create()
    {
        return view('features.thesis.create');
    }

    /**
     * Store a newly created thesis.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:500',
            'field' => 'required|string|max:100',
            'topic' => 'required|string|max:200',
            'advisor_name' => 'nullable|string|max:200',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'target_completion_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $thesis = Thesis::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'field' => $validated['field'],
            'topic' => $validated['topic'],
            'advisor_name' => $validated['advisor_name'] ?? null,
            'description' => $validated['description'] ?? null,
            'status' => 'draft',
            'progress_percentage' => 0,
            'start_date' => $validated['start_date'] ?? now(),
            'target_completion_date' => $validated['target_completion_date'] ?? null,
        ]);

        return redirect()->route('thesis.show', $thesis)
            ->with('success', 'Skripsi berhasil dibuat!');
    }

    /**
     * Display the specified thesis.
     */
    public function show(Thesis $thesis)
    {
        // Authorization check
        if ($thesis->user_id !== Auth::id()) {
            abort(403);
        }

        // No server-side storage of documents, references, or plagiarism checks
        
        // Calculate chapter progress
        $chapterProgress = $this->calculateChapterProgress($thesis);
        
        // Get recent activities
        $recentActivities = $this->getRecentActivities($thesis);
        
        return view('features.thesis.show', compact('thesis', 'chapterProgress', 'recentActivities'));
    }

    /**
     * Show the form for editing the thesis.
     */
    public function edit(Thesis $thesis)
    {
        if ($thesis->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('features.thesis.edit', compact('thesis'));
    }

    /**
     * Update the thesis.
     */
    public function update(Request $request, Thesis $thesis)
    {
        if ($thesis->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:500',
            'field' => 'required|string|max:100',
            'topic' => 'required|string|max:200',
            'advisor_name' => 'nullable|string|max:200',
            'description' => 'nullable|string',
            'status' => 'nullable|string|in:draft,in_progress,under_review,completed',
            'progress_percentage' => 'nullable|integer|min:0|max:100',
            'target_completion_date' => 'nullable|date',
        ]);

        $thesis->update($validated);

        return redirect()->route('thesis.show', $thesis)
            ->with('success', 'Skripsi berhasil diperbarui!');
    }

    /**
     * Remove the thesis.
     */
    public function destroy(Thesis $thesis)
    {
        if ($thesis->user_id !== Auth::id()) {
            abort(403);
        }

        // Delete associated documents from storage
        foreach ($thesis->documents as $document) {
            if ($document->file_path && \Storage::exists($document->file_path)) {
                \Storage::delete($document->file_path);
            }
        }

        $thesis->delete();

        return redirect()->route('thesis.index')
            ->with('success', 'Skripsi berhasil dihapus.');
    }

    /**
     * Update chapter progress.
     */
    public function updateChapterProgress(Request $request, Thesis $thesis)
    {
        if ($thesis->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'chapter' => 'required|integer|min:1|max:10',
            'status' => 'required|string|in:not_started,drafting,review,in_progress,completed',
            'progress' => 'required|integer|min:0|max:100',
        ]);

        $chapterStatuses = $thesis->chapter_statuses ?? [];
        $chapterStatuses["chapter_{$validated['chapter']}"] = [
            'status' => $validated['status'],
            'progress' => $validated['progress'],
            'updated_at' => now()->toISOString(),
        ];

        // Recalculate overall progress across all 5 default chapters
        $totalProgress = 0;
        $totalChapters = 5;
        for ($i = 1; $i <= $totalChapters; $i++) {
            $totalProgress += $chapterStatuses["chapter_{$i}"]['progress'] ?? 0;
        }
        $overallProgress = round($totalProgress / $totalChapters);

        $thesis->update([
            'chapter_statuses' => $chapterStatuses,
            'progress' => $overallProgress,
        ]);

        return response()->json([
            'success' => true,
            'overall_progress' => $overallProgress,
            'message' => 'Progress updated successfully.',
        ]);
    }

    /**
     * Export thesis to PDF (placeholder for future implementation).
     */
    public function export(Thesis $thesis, string $format = 'pdf')
    {
        if ($thesis->user_id !== Auth::id()) {
            abort(403);
        }

        // Placeholder for export functionality
        return response()->json([
            'success' => false,
            'message' => 'Export functionality coming soon.',
        ]);
    }

    // Private helper methods

    private function calculateChapterProgress(Thesis $thesis): array
    {
        $defaultChapters = [
            1 => ['name' => 'Bab I - Pendahuluan', 'status' => 'not_started', 'progress' => 0],
            2 => ['name' => 'Bab II - Tinjauan Pustaka', 'status' => 'not_started', 'progress' => 0],
            3 => ['name' => 'Bab III - Metodologi', 'status' => 'not_started', 'progress' => 0],
            4 => ['name' => 'Bab IV - Hasil & Pembahasan', 'status' => 'not_started', 'progress' => 0],
            5 => ['name' => 'Bab V - Kesimpulan', 'status' => 'not_started', 'progress' => 0],
        ];

        $savedStatuses = $thesis->chapter_statuses ?? [];
        
        foreach ($savedStatuses as $key => $status) {
            $chapterNum = (int) str_replace('chapter_', '', $key);
            if (isset($defaultChapters[$chapterNum])) {
                $defaultChapters[$chapterNum]['status'] = $status['status'];
                $defaultChapters[$chapterNum]['progress'] = $status['progress'];
            }
        }

        return $defaultChapters;
    }

    private function getRecentActivities(Thesis $thesis): array
    {
        // No server-side storage of documents/references — return empty
        return [];
    }
}
