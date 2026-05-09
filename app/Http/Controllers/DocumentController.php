<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Document;
use App\Models\Thesis;

class DocumentController extends Controller
{
    /**
     * Display a listing of documents.
     */
    public function index()
    {
        $documents = Document::where('user_id', Auth::id())
            ->with('thesis')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        $theses = Thesis::where('user_id', Auth::id())
            ->where('status', '!=', 'completed')
            ->get();
            
        return view('features.documents.index', compact('documents', 'theses'));
    }

    /**
     * Show the form for creating a new document.
     */
    public function create()
    {
        $theses = Thesis::where('user_id', Auth::id())
            ->where('status', '!=', 'completed')
            ->get();
            
        return view('features.documents.create', compact('theses'));
    }

    /**
     * Store a newly created document.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'thesis_id' => 'required|exists:theses,id',
            'title' => 'required|string|max:255',
            'type' => 'required|string|in:chapter,reference,draft,final,proposal,revision,other',
            'chapter_number' => 'nullable|integer|min:1|max:10',
            'file' => 'required|file|mimes:pdf,doc,docx,txt|max:51200', // 50MB max
            'notes' => 'nullable|string',
        ]);

        // Verify thesis belongs to user
        $thesis = Thesis::where('id', $validated['thesis_id'])
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Store file
        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('documents/' . Auth::id() . '/' . $thesis->id, $fileName, 'private');

        $document = Document::create([
            'user_id' => Auth::id(),
            'thesis_id' => $validated['thesis_id'],
            'title' => $validated['title'],
            'type' => $validated['type'],
            'chapter_number' => $validated['chapter_number'] ?? null,
            'file_path' => $filePath,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'file_type' => $file->getMimeType(),
            'file_extension' => $file->getClientOriginalExtension(),
            'notes' => $validated['notes'] ?? null,
            'version' => 1,
        ]);

        return redirect()->route('documents.show', $document)
            ->with('success', 'Dokumen berhasil diunggah!');
    }

    /**
     * Display the specified document.
     */
    public function show(Document $document)
    {
        if ($document->user_id !== Auth::id()) {
            abort(403);
        }

        $document->load('thesis');
        
        // Get document content for text files
        $content = null;
        if (in_array($document->file_extension, ['txt', 'md'])) {
            if (Storage::disk('private')->exists($document->file_path)) {
                $content = Storage::disk('private')->get($document->file_path);
            }
        }

        return view('features.documents.show', compact('document', 'content'));
    }

    /**
     * Download the document.
     */
    public function download(Document $document)
    {
        if ($document->user_id !== Auth::id()) {
            abort(403);
        }

        if (!Storage::disk('private')->exists($document->file_path)) {
            return redirect()->back()->with('error', 'File tidak ditemukan.');
        }

        return Storage::disk('private')->download($document->file_path, $document->file_name);
    }

    /**
     * Preview the document (for PDF and images).
     */
    public function preview(Document $document)
    {
        if ($document->user_id !== Auth::id()) {
            abort(403);
        }

        if (!Storage::disk('private')->exists($document->file_path)) {
            abort(404);
        }

        $mimeType = $document->file_type ?? 'application/octet-stream';
        
        return response()->file(Storage::disk('private')->path($document->file_path), [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $document->file_name . '"',
        ]);
    }

    /**
     * Show the form for editing the document.
     */
    public function edit(Document $document)
    {
        if ($document->user_id !== Auth::id()) {
            abort(403);
        }

        $theses = Thesis::where('user_id', Auth::id())
            ->where('status', '!=', 'completed')
            ->get();
            
        return view('features.documents.edit', compact('document', 'theses'));
    }

    /**
     * Update the document.
     */
    public function update(Request $request, Document $document)
    {
        if ($document->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|in:chapter,reference,draft,final,proposal,revision,other',
            'chapter_number' => 'nullable|integer|min:1|max:10',
            'notes' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,txt|max:51200',
        ]);

        // Handle file update
        if ($request->hasFile('file')) {
            // Delete old file
            if (Storage::disk('private')->exists($document->file_path)) {
                Storage::disk('private')->delete($document->file_path);
            }

            // Store new file
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs(
                'documents/' . Auth::id() . '/' . $document->thesis_id, 
                $fileName, 
                'private'
            );

            $validated['file_path'] = $filePath;
            $validated['file_name'] = $file->getClientOriginalName();
            $validated['file_size'] = $file->getSize();
            $validated['file_type'] = $file->getMimeType();
            $validated['file_extension'] = $file->getClientOriginalExtension();
            $validated['version'] = $document->version + 1;
        }

        $document->update($validated);

        return redirect()->route('documents.show', $document)
            ->with('success', 'Dokumen berhasil diperbarui!');
    }

    /**
     * Remove the document.
     */
    public function destroy(Document $document)
    {
        if ($document->user_id !== Auth::id()) {
            abort(403);
        }

        // Delete file from storage
        if (Storage::disk('private')->exists($document->file_path)) {
            Storage::disk('private')->delete($document->file_path);
        }

        $document->delete();

        return redirect()->route('documents.index')
            ->with('success', 'Dokumen berhasil dihapus.');
    }

    /**
     * Get documents by thesis.
     */
    public function getByThesis(string $thesisId)
    {
        $thesis = Thesis::where('id', $thesisId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $documents = Document::where('thesis_id', $thesisId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $documents,
        ]);
    }

    /**
     * Get document statistics.
     */
    public function statistics()
    {
        $totalDocuments = Document::where('user_id', Auth::id())->count();
        $totalSize = Document::where('user_id', Auth::id())->sum('file_size');
        
        $documentsByType = Document::where('user_id', Auth::id())
            ->selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'total_documents' => $totalDocuments,
                'total_size_mb' => round($totalSize / 1024 / 1024, 2),
                'documents_by_type' => $documentsByType,
            ],
        ]);
    }

    /**
     * Quick upload from AI Writing interface.
     */
    public function quickUpload(Request $request)
    {
        $validated = $request->validate([
            'thesis_id' => 'required|exists:theses,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|string|in:chapter,draft',
            'chapter_number' => 'nullable|integer',
        ]);

        // Verify thesis belongs to user
        $thesis = Thesis::where('id', $validated['thesis_id'])
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Create text file from content
        $fileName = time() . '_' . str_replace(' ', '_', $validated['title']) . '.txt';
        $filePath = 'documents/' . Auth::id() . '/' . $thesis->id . '/' . $fileName;
        
        Storage::disk('private')->put($filePath, $validated['content']);
        
        $fullPath = Storage::disk('private')->path($filePath);
        $fileSize = filesize($fullPath);

        $document = Document::create([
            'user_id' => Auth::id(),
            'thesis_id' => $validated['thesis_id'],
            'title' => $validated['title'],
            'type' => $validated['type'],
            'chapter_number' => $validated['chapter_number'] ?? null,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_size' => $fileSize,
            'file_type' => 'text/plain',
            'file_extension' => 'txt',
            'notes' => 'Generated by AI Writing Assistant',
            'version' => 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Dokumen berhasil disimpan.',
            'data' => $document,
        ]);
    }
}
