<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        $thesis = $user->theses()->latest()->first();

        if (! $thesis) {
            $thesis = $user->theses()->create([
                'title' => 'Master Thesis Timeline',
                'topic' => 'Belum ditentukan',
                'summary' => 'Mulai susun tujuan, pertanyaan riset, dan milestone penting.',
                'status' => 'ideation',
                'progress' => 12,
                'target_completion_date' => now()->addMonths(5),
                'supervisor_name' => 'Belum ditetapkan',
                'research_question' => 'Apa dampak teknologi terhadap pengalaman belajar mahasiswa?',
                'methodology' => 'Literature review + mixed method',
                'keywords' => ['skripsi', 'edtech', 'AI'],
                'last_activity_at' => now(),
            ]);
        }

        $recentInteractions = $user->aiInteractions()->latest()->take(4)->get();

        $deadlines = collect([
            [
                'title' => 'Bab I - Pendahuluan',
                'due' => now()->addDays(6),
                'status' => 'On track',
                'focus' => 'Rangkum latar belakang & rumusan masalah',
            ],
            [
                'title' => 'Bab II - Tinjauan Pustaka',
                'due' => now()->addDays(18),
                'status' => 'Drafting',
                'focus' => 'Mapping state-of-the-art & gap',
            ],
            [
                'title' => 'Proposal Review',
                'due' => now()->addDays(28),
                'status' => 'Perlu revisi',
                'focus' => 'Siapkan data pendukung supervisor',
            ],
        ]);

        $workspaceShortcuts = [
            [
                'title' => 'Cari Paper Cepat',
                'description' => 'Semantic Scholar + filter jurnal nasional',
                'icon' => '📚',
                'accent' => 'from-emerald-400/40 to-emerald-500/10',
                'href' => route('paper.index'),
            ],
            [
                'title' => 'AI Writing Bab',
                'description' => 'Generate outline & paragraf siap sunting',
                'icon' => '✨',
                'accent' => 'from-fuchsia-500/40 to-purple-500/10',
                'href' => route('ai-writing.index'),
            ],
            [
                'title' => 'Koreksi Typo',
                'description' => 'Proofread bilingual secara otomatis',
                'icon' => '📝',
                'accent' => 'from-sky-400/40 to-cyan-500/10',
                'href' => route('ai-writing.index') . '?tab=typo',
            ],
            [
                'title' => 'Research Gap',
                'description' => 'Temukan celah penelitian baru',
                'icon' => '🔍',
                'accent' => 'from-amber-400/40 to-orange-500/10',
                'href' => route('ai-writing.index') . '?tab=gap',
            ],
        ];

        $metrics = [
            [
                'label' => 'Progress skripsi',
                'value' => $thesis->progress,
                'suffix' => '%',
                'trend' => '+6% minggu ini',
            ],
            [
                'label' => 'Paper tersimpan',
                'value' => $user->paperReferences()->count(),
                'suffix' => ' paper',
                'trend' => 'Referensi yang kamu kumpulkan',
            ],
            [
                'label' => 'Sesi AI',
                'value' => $user->aiInteractions()->count(),
                'suffix' => ' percakapan',
                'trend' => 'Terakhir '.optional($recentInteractions->first())->created_at?->diffForHumans(),
            ],
            [
                'label' => 'Hari Tersisa',
                'value' => now()->diffInDays($thesis->target_completion_date),
                'suffix' => ' hari',
                'trend' => 'Hingga target selesai',
            ],
        ];

        return view('dashboard', [
            'thesis' => $thesis,
            'deadlines' => $deadlines,
            'workspaceShortcuts' => $workspaceShortcuts,
            'recentInteractions' => $recentInteractions,
            'metrics' => $metrics,
        ]);
    }
}
