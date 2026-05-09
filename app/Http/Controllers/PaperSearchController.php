<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\PaperReference;

class PaperSearchController extends Controller
{
    /**
     * Display the paper search interface.
     */
    public function index()
    {
        return view('features.paper-search');
    }

    /**
     * Search papers using Semantic Scholar API with AI fallback.
     */
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|max:255',
            'fields' => 'nullable|string',
            'limit' => 'nullable|integer|min:1|max:100',
            'year_start' => 'nullable|integer|min:1900|max:' . date('Y'),
            'open_access' => 'nullable|boolean',
            'pub_type' => 'nullable|string|in:JournalArticle,Conference,Review,Book',
            'top_cited' => 'nullable|boolean',
            'language' => 'nullable|string|in:en,id',
        ]);

        $query = $request->input('query');
        $fields = $request->input('fields', 'title,authors,year,abstract,venue,citationCount,referenceCount,openAccessPdf,publicationTypes');
        $limit = $request->input('limit', 20);
        $yearStart = $request->input('year_start');
        $openAccess = $request->boolean('open_access');
        $pubType = $request->input('pub_type');
        $topCited = $request->boolean('top_cited');
        $language = $request->input('language');

        try {
            // Enhance query with language hint for Semantic Scholar
            $ssQuery = $query;
            if ($language === 'id') {
                $ssQuery .= ' Indonesia';
            }

            $ssParams = [
                'query' => $ssQuery,
                'fields' => $fields,
                'limit' => $limit,
                'offset' => 0,
            ];

            if ($yearStart) {
                $ssParams['publicationDateOrYear'] = $yearStart . ':2026';
            }
            if ($openAccess) {
                $ssParams['openAccessPdf'] = 'true';
            }
            if ($pubType) {
                $ssParams['publicationTypes'] = $pubType;
            }

            $response = Http::timeout(30)
                ->withHeaders([
                    'User-Agent' => 'ThesisAssistant/1.0 (https://thesis-assistant.local)',
                ])
                ->get('https://api.semanticscholar.org/graph/v1/paper/search', $ssParams);

            if ($response->successful()) {
                $data = $response->json();

                if (Auth::check()) {
                    session(['last_paper_search' => [
                        'query' => $query,
                        'results' => $data['total'] ?? 0,
                        'timestamp' => now(),
                    ]]);
                }

                // Sort by citation count if top_cited is requested
                if ($topCited && !empty($data['data'])) {
                    usort($data['data'], fn($a, $b) => ($b['citationCount'] ?? 0) <=> ($a['citationCount'] ?? 0));
                }

                return response()->json([
                    'success' => true,
                    'data' => $data,
                    'query' => $query,
                ]);
            }

            // Try AI fallback on API failure
            $aiResults = $this->aiPaperSearchFallback($query, $limit, $yearStart, $openAccess, $pubType, $topCited, $language);
            if ($aiResults !== null) {
                return response()->json([
                    'success' => true,
                    'data' => $aiResults,
                    'query' => $query,
                    'source' => 'ai_fallback',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch results from Semantic Scholar.',
                'error' => $response->body(),
            ], $response->status());

        } catch (\Exception $e) {
            // Try AI fallback on network error
            $aiResults = $this->aiPaperSearchFallback($query, $limit, $yearStart, $openAccess, $pubType, $topCited, $language);
            if ($aiResults !== null) {
                return response()->json([
                    'success' => true,
                    'data' => $aiResults,
                    'query' => $query,
                    'source' => 'ai_fallback',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while searching.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * AI-powered paper search fallback using Swiftrouter.
     */
    private function aiPaperSearchFallback(string $query, int $limit, ?int $yearStart = null, bool $openAccess = false, ?string $pubType = null, bool $topCited = false, ?string $language = null): ?array
    {
        $apiKey = config('services.swiftrouter.key');
        $apiUrl = config('services.swiftrouter.url', 'https://api.swiftrouter.com/v1');
        $model = config('services.swiftrouter.model', 'gemini-3.1-flash-lite-preview');

        if (empty($apiKey)) {
            return null;
        }

        $constraints = '';
        if ($yearStart) {
            $constraints .= "All papers MUST be published in year {$yearStart} or later. ";
        }
        if ($openAccess) {
            $constraints .= "All papers MUST have open access PDF available. ";
        }
        if ($pubType) {
            $constraints .= "Publication type MUST be {$pubType}. ";
        }
        if ($topCited) {
            $constraints .= "Prioritize highly-cited papers (well-known, influential works). ";
        }
        if ($language === 'id') {
            $constraints .= "All papers MUST be in Bahasa Indonesia (Indonesian language). ";
        } elseif ($language === 'en') {
            $constraints .= "All papers MUST be in English language. ";
        }

        try {
            $prompt = "You are an academic paper search engine. Find {$limit} relevant academic papers for the query: \"{$query}\". " .
                $constraints . "Return ONLY valid JSON with this exact structure (no markdown, no extra text):\n" .
                '{"total": <number>, "data": [{"paperId": "<unique-id>", "title": "<paper title>", "authors": [{"name": "<author name>"}], "year": <year>, "abstract": "<short abstract max 300 chars>", "venue": "<journal/conference name>", "citationCount": <number>, "referenceCount": <number>, "openAccessPdf": {"url": "<url or null>"}, "url": "<url or null>"}]}';

            $response = Http::timeout(60)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post($apiUrl . '/chat/completions', [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are a JSON-only academic search engine. Always return valid JSON matching the requested schema exactly.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'temperature' => 0.3,
                    'max_tokens' => 4000,
                ]);

            if (!$response->successful()) {
                return null;
            }

            $content = $response->json()['choices'][0]['message']['content'] ?? '';

            // Strip markdown code fences if present
            $content = preg_replace('/^```json\s*/', '', $content);
            $content = preg_replace('/\s*```$/', '', $content);

            $data = json_decode($content, true);
            if (json_last_error() === JSON_ERROR_NONE && isset($data['data'])) {
                if ($openAccess) {
                    $data['data'] = array_filter($data['data'], fn($p) => !empty($p['openAccessPdf']['url']));
                    $data['data'] = array_values($data['data']);
                }
                if ($topCited) {
                    usort($data['data'], fn($a, $b) => ($b['citationCount'] ?? 0) <=> ($a['citationCount'] ?? 0));
                }
                $data['total'] = count($data['data']);
                return $data;
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get paper details by ID.
     */
    public function getPaperDetails(string $paperId)
    {
        try {
            $response = Http::timeout(30)->get("https://api.semanticscholar.org/graph/v1/paper/{$paperId}", [
                'fields' => 'title,authors,year,abstract,venue,citationCount,referenceCount,openAccessPdf,fieldsOfStudy,sjr,eigenFactor',
            ]);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'data' => $response->json(),
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch paper details.',
            ], $response->status());

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display user's saved papers.
     */
    public function saved()
    {
        $papers = PaperReference::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('features.saved-papers', compact('papers'));
    }

    /**
     * Toggle save/unsave a paper.
     */
    public function toggleSave(Request $request)
    {
        $request->validate([
            'external_id' => 'required|string',
            'title' => 'required|string|max:500',
            'authors' => 'nullable|string',
            'year' => 'nullable|integer',
            'abstract' => 'nullable|string',
            'url' => 'nullable|string',
            'venue' => 'nullable|string',
            'citation_count' => 'nullable|integer',
        ]);

        $existing = PaperReference::where('user_id', Auth::id())
            ->where('external_id', $request->external_id)
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json([
                'success' => true,
                'saved' => false,
                'message' => 'Paper removed from saved list.',
            ]);
        }

        PaperReference::create([
            'user_id' => Auth::id(),
            'external_id' => $request->external_id,
            'title' => $request->title,
            'authors' => $request->authors,
            'abstract' => $request->abstract,
            'url' => $request->url,
            'publisher' => $request->venue,
            'published_at' => $request->year ? "{$request->year}-01-01" : null,
            'metadata' => [
                'citation_count' => $request->citation_count,
                'year' => $request->year,
            ],
        ]);

        return response()->json([
            'success' => true,
            'saved' => true,
            'message' => 'Paper saved successfully.',
        ]);
    }

    /**
     * Check if papers are saved (batch).
     */
    public function checkSaved(Request $request)
    {
        $ids = $request->input('ids', []);
        $saved = PaperReference::where('user_id', Auth::id())
            ->whereIn('external_id', $ids)
            ->pluck('external_id')
            ->toArray();

        return response()->json([
            'success' => true,
            'saved_ids' => $saved,
        ]);
    }
}
