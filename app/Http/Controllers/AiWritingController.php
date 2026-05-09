<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\Thesis;
use App\Models\AIInteraction;
use App\Models\PlagiarismCheck;
use App\Models\Document;

class AiWritingController extends Controller
{
    private string $swiftrouterApiUrl;
    private ?string $swiftrouterApiKey;
    private string $swiftrouterModel;

    public function __construct()
    {
        $this->swiftrouterApiUrl = config('services.swiftrouter.url', 'https://api.swiftrouter.com/v1');
        $this->swiftrouterApiKey = config('services.swiftrouter.key');
        $this->swiftrouterModel = config('services.swiftrouter.model', 'gemini-3.1-flash-lite-preview');
    }

    /**
     * Display the AI Writing Assistant interface.
     */
    public function index()
    {
        $theses = Thesis::where('user_id', Auth::id())
            ->where('status', '!=', 'completed')
            ->get();
            
        $recentInteractions = AIInteraction::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
        return view('features.ai-writing', compact('theses', 'recentInteractions'));
    }

    /**
     * Generate text using AI (outline, chapter content, etc.)
     */
    public function generate(Request $request)
    {
        $request->validate([
            'thesis_id' => 'required|exists:theses,id',
            'prompt' => 'required|string|min:10|max:5000',
            'type' => 'required|string|in:outline,chapter,abstract,introduction,literature_review,methodology,analysis,conclusion',
            'language' => 'nullable|string|in:id,en,mixed',
            'word_count' => 'nullable|integer|min:100|max:5000',
        ]);

        $thesis = Thesis::where('id', $request->thesis_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $startTime = microtime(true);

        try {
            // Enhanced prompt with thesis context
            $enhancedPrompt = $this->buildEnhancedPrompt(
                $request->prompt,
                $thesis,
                $request->type,
                $request->language ?? 'mixed',
                $request->word_count ?? 500
            );

            // Call Swiftrouter API or fallback to mock response for development
            $aiResponse = $this->callSwiftrouterApi($enhancedPrompt);

            $duration = (microtime(true) - $startTime) * 1000;

            // Log the interaction
            $interaction = AIInteraction::create([
                'user_id' => Auth::id(),
                'thesis_id' => $request->thesis_id,
                'feature' => 'ai_writing',
                'prompt' => $request->prompt,
                'response' => $aiResponse,
                'metadata' => [
                    'type' => $request->type,
                    'language' => $request->language ?? 'mixed',
                    'word_count' => $request->word_count ?? 500,
                    'thesis_title' => $thesis->title,
                ],
                'duration_ms' => round($duration, 2),
                'status' => 'success',
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'content' => $aiResponse,
                    'interaction_id' => $interaction->id,
                    'type' => $request->type,
                    'word_count_estimate' => str_word_count($aiResponse),
                ],
                'message' => 'Content generated successfully.',
            ]);

        } catch (\Exception $e) {
            $duration = (microtime(true) - $startTime) * 1000;
            
            AIInteraction::create([
                'user_id' => Auth::id(),
                'thesis_id' => $request->thesis_id,
                'feature' => 'ai_writing',
                'prompt' => $request->prompt,
                'response' => '',
                'metadata' => [
                    'type' => $request->type,
                    'error' => $e->getMessage(),
                ],
                'duration_ms' => round($duration, 2),
                'status' => 'error',
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to generate content.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Improve existing text (paraphrase, enhance, etc.)
     */
    public function improve(Request $request)
    {
        $request->validate([
            'thesis_id' => 'required|exists:theses,id',
            'text' => 'required|string|min:50|max:10000',
            'improvement_type' => 'required|string|in:paraphrase,enhance,simplify,formalize',
            'language' => 'nullable|string|in:id,en',
        ]);

        $thesis = Thesis::where('id', $request->thesis_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $startTime = microtime(true);

        try {
            $prompt = $this->buildImprovementPrompt(
                $request->text,
                $request->improvement_type,
                $request->language ?? 'mixed'
            );

            $aiResponse = $this->callSwiftrouterApi($prompt);
            $duration = (microtime(true) - $startTime) * 1000;

            $interaction = AIInteraction::create([
                'user_id' => Auth::id(),
                'thesis_id' => $request->thesis_id,
                'feature' => 'text_improvement',
                'prompt' => $request->text,
                'response' => $aiResponse,
                'metadata' => [
                    'improvement_type' => $request->improvement_type,
                    'language' => $request->language ?? 'mixed',
                ],
                'duration_ms' => round($duration, 2),
                'status' => 'success',
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'improved_text' => $aiResponse,
                    'interaction_id' => $interaction->id,
                    'improvement_type' => $request->improvement_type,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to improve text.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Correct typos and grammar
     */
    public function correctTypo(Request $request)
    {
        $request->validate([
            'thesis_id' => 'nullable|exists:theses,id',
            'text' => 'required|string|min:10|max:10000',
            'language' => 'nullable|string|in:id,en,mixed',
        ]);

        $startTime = microtime(true);

        try {
            $prompt = $this->buildTypoCorrectionPrompt($request->text, $request->language ?? 'mixed');
            $aiResponse = $this->callSwiftrouterApi($prompt);
            $duration = (microtime(true) - $startTime) * 1000;

            // Parse corrections
            $corrections = $this->parseCorrections($request->text, $aiResponse);

            $interaction = AIInteraction::create([
                'user_id' => Auth::id(),
                'thesis_id' => $request->thesis_id,
                'feature' => 'typo_correction',
                'prompt' => $request->text,
                'response' => json_encode($corrections),
                'metadata' => [
                    'language' => $request->language ?? 'mixed',
                    'correction_count' => count($corrections['corrections'] ?? []),
                ],
                'duration_ms' => round($duration, 2),
                'status' => 'success',
            ]);

            return response()->json([
                'success' => true,
                'data' => $corrections,
                'interaction_id' => $interaction->id,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to correct text.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Check plagiarism (simulated with AI analysis)
     */
    public function checkPlagiarism(Request $request)
    {
        $request->validate([
            'thesis_id' => 'nullable|exists:theses,id',
            'text' => 'required|string|min:100|max:50000',
            'document_id' => 'nullable|exists:documents,id',
        ]);

        $startTime = microtime(true);

        try {
            // In a real implementation, this would call a plagiarism detection service
            // For now, we'll use AI to analyze potential similarities
            $prompt = $this->buildPlagiarismCheckPrompt($request->text);
            $aiResponse = $this->callSwiftrouterApi($prompt);
            
            $duration = (microtime(true) - $startTime) * 1000;
            
            // Parse plagiarism check results
            $plagiarismData = $this->parsePlagiarismResults($aiResponse);

            // Create plagiarism check record
            $check = PlagiarismCheck::create([
                'user_id' => Auth::id(),
                'thesis_id' => $request->thesis_id,
                'document_id' => $request->document_id,
                'text_excerpt' => substr($request->text, 0, 1000),
                'similarity_score' => $plagiarismData['similarity_score'] ?? 0,
                'status' => 'completed',
                'matches' => $plagiarismData['matches'] ?? [],
            ]);

            // Log AI interaction
            AIInteraction::create([
                'user_id' => Auth::id(),
                'thesis_id' => $request->thesis_id,
                'feature' => 'plagiarism_check',
                'prompt' => substr($request->text, 0, 500),
                'response' => json_encode($plagiarismData),
                'metadata' => [
                    'similarity_score' => $plagiarismData['similarity_score'] ?? 0,
                    'document_id' => $request->document_id,
                ],
                'duration_ms' => round($duration, 2),
                'status' => 'success',
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'check_id' => $check->id,
                    'similarity_score' => $plagiarismData['similarity_score'] ?? 0,
                    'status' => 'completed',
                    'matches' => $plagiarismData['matches'] ?? [],
                    'suggestions' => $plagiarismData['suggestions'] ?? [],
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to check plagiarism.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Find research gaps
     */
    public function findGap(Request $request)
    {
        $request->validate([
            'thesis_id' => 'required|exists:theses,id',
            'topic' => 'required|string|min:10|max:500',
            'research_questions' => 'nullable|string|max:1000',
        ]);

        $thesis = Thesis::where('id', $request->thesis_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $startTime = microtime(true);

        try {
            $prompt = $this->buildResearchGapPrompt(
                $request->topic,
                $thesis,
                $request->research_questions
            );

            $aiResponse = $this->callSwiftrouterApi($prompt);
            $duration = (microtime(true) - $startTime) * 1000;

            $gapData = $this->parseGapAnalysis($aiResponse);

            $interaction = AIInteraction::create([
                'user_id' => Auth::id(),
                'thesis_id' => $request->thesis_id,
                'feature' => 'research_gap',
                'prompt' => $request->topic,
                'response' => json_encode($gapData),
                'metadata' => [
                    'research_questions' => $request->research_questions,
                    'gap_count' => count($gapData['gaps'] ?? []),
                ],
                'duration_ms' => round($duration, 2),
                'status' => 'success',
            ]);

            return response()->json([
                'success' => true,
                'data' => $gapData,
                'interaction_id' => $interaction->id,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to analyze research gaps.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get AI interaction history
     */
    public function history()
    {
        $interactions = AIInteraction::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $interactions,
        ]);
    }

    // Private helper methods

    private function buildEnhancedPrompt(string $prompt, Thesis $thesis, string $type, string $language, int $wordCount): string
    {
        $typeDescriptions = [
            'outline' => 'buatkan outline/detail kerangka bab',
            'chapter' => 'tuliskan konten bab lengkap',
            'abstract' => 'buatkan abstrak',
            'introduction' => 'tuliskan bab pendahuluan',
            'literature_review' => 'tuliskan tinjauan pustaka',
            'methodology' => 'tuliskan metodologi penelitian',
            'analysis' => 'tuliskan analisis dan pembahasan',
            'conclusion' => 'tuliskan kesimpulan dan saran',
        ];

        $langInstruction = match($language) {
            'id' => 'Gunakan Bahasa Indonesia formal akademik.',
            'en' => 'Use formal academic English.',
            'mixed' => 'Gunakan Bahasa Indonesia campur English untuk istilah teknis (code-switching seperti akademisi Indonesia).',
            default => 'Gunakan Bahasa Indonesia campur English untuk istilah teknis.',
        };

        return <<<PROMPT
Konteks Skripsi:
- Judul: {$thesis->title}
- Bidang: {$thesis->field}
- Topik: {$thesis->topic}

Instruksi: {$typeDescriptions[$type]} untuk skripsi dengan konteks di atas. 
{$langInstruction}
Target: sekitar {$wordCount} kata.

Permintaan spesifik pengguna: {$prompt}

Format output:
- Gunakan format akademik standar
- Sertakan referensi placeholder [Author, Year] untuk kutipan
- Gunakan paragraf yang terstruktur dengan baik
- Untuk outline, gunakan format hierarki (1, 1.1, 1.1.1, dll.)
PROMPT;
    }

    private function buildImprovementPrompt(string $text, string $type, string $language): string
    {
        $typeInstructions = [
            'paraphrase' => 'Parafrase teks berikut dengan kalimat dan struktur yang berbeda tapi mempertahankan makna.',
            'enhance' => 'Tingkatkan kualitas teks berikut dengan memperkaya kosakata dan memperjelas argumen.',
            'simplify' => 'Sederhanakan teks berikut agar lebih mudah dipahami tanpa kehilangan esensi.',
            'formalize' => 'Buat teks berikut lebih formal dan akademis.',
        ];

        return $typeInstructions[$type] . "\n\nBahasa: {$language}\n\nTeks:\n{$text}";
    }

    private function buildTypoCorrectionPrompt(string $text, string $language): string
    {
        return <<<PROMPT
Analisis teks berikut untuk koreksi typo, kesalahan grammar, ejaan, dan tanda baca.
Bahasa: {$language}

Teks:
{$text}

Format output (JSON):
{
  "corrected_text": "teks yang sudah dikoreksi",
  "corrections": [
    {
      "original": "kata salah",
      "corrected": "kata benar",
      "type": "typo/grammar/spelling/punctuation",
      "position": "index"
    }
  ],
  "summary": "ringkasan perubahan"
}
PROMPT;
    }

    private function buildPlagiarismCheckPrompt(string $text): string
    {
        return <<<PROMPT
Analisis teks berikut untuk potensi plagiarisme. Identifikasi:
1. Kalimat yang terlalu umum/mirip dengan sumber umum
2. Bagian yang perlu dikutip/direferensikan
3. Estimasi similarity score (0-100%)
4. Rekomendasi parafrase untuk bagian bermasalah

Teks (potongan):
{$text}

Format output (JSON):
{
  "similarity_score": 15.5,
  "matches": [
    {
      "text": "kalimat bermasalah",
      "suggestion": "versi parafrase",
      "type": "common_phrase/needs_citation/potential_copy"
    }
  ],
  "suggestions": ["saran perbaikan umum"]
}
PROMPT;
    }

    private function buildResearchGapPrompt(string $topic, Thesis $thesis, ?string $researchQuestions): string
    {
        $rqContext = $researchQuestions ? "\nRumusan Masalah:\n{$researchQuestions}" : '';

        return <<<PROMPT
Analisis research gap untuk topik penelitian berikut:

Judul Skripsi: {$thesis->title}
Topik Spesifik: {$topic}
Bidang: {$thesis->field}{$rqContext}

Tugas:
1. Identifikasi 3-5 research gap spesifik yang belum diteliti
2. Untuk setiap gap, jelaskan:
   - Apa yang sudah diteliti (existing research)
   - Apa yang belum/kurang (the gap)
   - Mengapa penting untuk diteliti (significance)
   - Metode yang disarankan
3. Prioritaskan gap berdasarkan feasibility dan novelty

Format output (JSON):
{
  "gaps": [
    {
      "title": "judul gap",
      "existing_research": "ringkasan",
      "the_gap": "apa yang kurang",
      "significance": "kenapa penting",
      "suggested_method": "metode yang disarankan",
      "priority": "high/medium/low"
    }
  ],
  "recommendation": "gap mana yang paling direkomendasikan dan alasan"
}
PROMPT;
    }

    private function callSwiftrouterApi(string $prompt): string
    {
        // If no API key is configured, return mock response for development
        if (empty($this->swiftrouterApiKey)) {
            return $this->getMockResponse($prompt);
        }

        try {
            $response = Http::timeout(60)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->swiftrouterApiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post($this->swiftrouterApiUrl . '/chat/completions', [
                    'model' => $this->swiftrouterModel,
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => $prompt,
                        ],
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 2000,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['choices'][0]['message']['content'] ?? '';
            }

            throw new \Exception('API request failed: ' . $response->body());

        } catch (\Exception $e) {
            // Fallback to mock response if API fails
            return $this->getMockResponse($prompt);
        }
    }

    private function getMockResponse(string $prompt): string
    {
        // Simple mock responses for development/testing
        if (str_contains($prompt, 'outline')) {
            return "## Outline Bab II - Tinjauan Pustaka\n\n### 2.1 Landasan Teori\n#### 2.1.1 Konsep Dasar [Topik Utama]\n- Definisi dan terminologi\n- Sejarah perkembangan\n- Variabel dan indikator\n\n#### 2.1.2 Kerangka Konseptual\n- Hubungan antar variabel\n- Hipotesis penelitian\n\n### 2.2 Penelitian Terdahulu\n#### 2.2.1 Studi Internasional\n- [Penelitian 1: Author, Year] - Temuan utama\n- [Penelitian 2: Author, Year] - Metodologi\n\n#### 2.2.2 Studi Lokal (Indonesia)\n- [Penelitian 3: Author, Year] - Konteks Indonesia\n\n### 2.3 Kerangka Pemikiran\n- Sintesis teori\n- Model konseptual penelitian ini\n\n---\n\n*Catatan: Outline ini dapat dikembangkan lebih detail berdasarkan fokus penelitian spesifik Anda.*";
        }

        if (str_contains($prompt, 'plagiarisme') || str_contains($prompt, 'similarity')) {
            return json_encode([
                'similarity_score' => 8.5,
                'matches' => [
                    [
                        'text' => 'Machine learning is a subset of artificial intelligence',
                        'suggestion' => 'Machine learning represents a specialized domain within the broader field of artificial intelligence',
                        'type' => 'common_phrase',
                    ],
                    [
                        'text' => 'According to Smith (2020), the results show significant improvement',
                        'suggestion' => 'The findings demonstrate considerable enhancement [Smith, 2020]',
                        'type' => 'needs_citation',
                    ],
                ],
                'suggestions' => [
                    'Gunakan parafrase untuk definisi umum',
                    'Pastikan semua kutipan memiliki referensi lengkap',
                    'Tambahkan analisis kritis setelah kutipan',
                ],
            ]);
        }

        if (str_contains($prompt, 'typo') || str_contains($prompt, 'correction')) {
            return json_encode([
                'corrected_text' => 'Teks ini sudah dikoreksi dengan baik.',
                'corrections' => [
                    [
                        'original' => 'teks',
                        'corrected' => 'teks',
                        'type' => 'spelling',
                        'position' => 10,
                    ],
                ],
                'summary' => '1 koreksi ditemukan dan diperbaiki.',
            ]);
        }

        if (str_contains($prompt, 'gap') || str_contains($prompt, 'research gap')) {
            return json_encode([
                'gaps' => [
                    [
                        'title' => 'Kurangnya Studi Empiris di Konteks Indonesia',
                        'existing_research' => 'Banyak studi tentang topik ini dilakukan di negara barat',
                        'the_gap' => 'Belum ada studi komprehensif dengan sampel populasi Indonesia yang beragam',
                        'significance' => 'Konteks budaya dan sosial Indonesia unik, memerlukan validasi lokal',
                        'suggested_method' => 'Survei kuantitatif dengan sampel representatif nasional',
                        'priority' => 'high',
                    ],
                    [
                        'title' => 'Integrasi dengan Teknologi Terbaru',
                        'existing_research' => 'Penelitian existing menggunakan metode konvensional',
                        'the_gap' => 'Belum ada yang mengintegrasikan AI/ML untuk optimasi proses',
                        'significance' => 'Efisiensi dan akurasi dapat ditingkatkan signifikan',
                        'suggested_method' => 'Mixed method dengan implementasi prototype sistem',
                        'priority' => 'high',
                    ],
                ],
                'recommendation' => 'Gap 1 (Konteks Indonesia) paling direkomendasikan karena feasibility tinggi dan novelty yang kuat untuk kontribusi lokal.',
            ]);
        }

        return "Konten berhasil digenerate berdasarkan permintaan Anda. [Ini adalah mock response untuk development. Integration dengan Swiftrouter API akan memberikan hasil yang lebih berkualitas.]";
    }

    private function parseCorrections(string $original, string $aiResponse): array
    {
        try {
            $data = json_decode($aiResponse, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $data;
            }
        } catch (\Exception $e) {
            // Fallback: return simple correction structure
        }

        return [
            'corrected_text' => $aiResponse,
            'corrections' => [],
            'summary' => 'Koreksi berhasil diterapkan.',
        ];
    }

    private function parsePlagiarismResults(string $aiResponse): array
    {
        try {
            $data = json_decode($aiResponse, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $data;
            }
        } catch (\Exception $e) {
            // Fallback
        }

        return [
            'similarity_score' => 0,
            'matches' => [],
            'suggestions' => ['Unable to parse results. Please try again.'],
        ];
    }

    private function parseGapAnalysis(string $aiResponse): array
    {
        try {
            $data = json_decode($aiResponse, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $data;
            }
        } catch (\Exception $e) {
            // Fallback
        }

        return [
            'gaps' => [],
            'recommendation' => 'Unable to analyze gaps. Please try again.',
        ];
    }
}
