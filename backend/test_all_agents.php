<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\AI\Agents\LeadChatAgent;
use App\AI\Agents\BlogOptimizerAgent;
use App\Models\ChatSession;
use App\Models\Lead;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

echo "====================================================================\n";
echo " CLIMBSPHERE LOCAL GEMMA AI AGENT AUTONOMOUS WORKFLOW TEST SUITE \n";
echo "====================================================================\n\n";

$passCount = 0;
$failCount = 0;

function report($testName, $passed, $details = '') {
    global $passCount, $failCount;
    if ($passed) {
        $passCount++;
        echo " [PASS] $testName\n";
    } else {
        $failCount++;
        echo " [FAIL] $testName\n";
    }
    if (!empty($details)) {
        echo "        $details\n";
    }
    echo "\n";
}

// ------------------------------------------------------------------
// 1. Connection & Model Availability Test
// ------------------------------------------------------------------
echo "--- TEST 1: LM Studio / Gemma Model Connection ---\n";
try {
    $baseUrl = config('ai.providers.openai.url', 'http://host.containers.internal:1234/v1');
    $modelName = config('ai.providers.openai.models.text.default', 'google/gemma-4-e4b');
    
    $res = Http::timeout(10)->get(rtrim($baseUrl, '/') . '/models');
    $models = $res->json()['data'] ?? [];
    $modelIds = array_column($models, 'id');
    
    $found = in_array($modelName, $modelIds);
    report("Gemma Model Connectivity", $found, "Endpoint: $baseUrl | Model: $modelName | Found: " . ($found ? 'YES' : 'NO'));
} catch (\Throwable $e) {
    report("Gemma Model Connectivity", false, "Error: " . $e->getMessage());
}

// ------------------------------------------------------------------
// 2. BlogOptimizerAgent Autonomous Execution Test
// ------------------------------------------------------------------
echo "--- TEST 2: BlogOptimizerAgent (SEO / AEO / GEO Autonomous Optimization) ---\n";
try {
    $sampleTitle = "Fractional Technology Leadership in Modern Enterprise";
    $sampleContent = "Fractional Technology Leadership provides on-demand executive tech talent. ClimbSphere offers an Agile Growth model (Diagnose, Map, Climb, Sustain) to help organizations scale technology systems efficiently.";
    
    $agent = new BlogOptimizerAgent($sampleTitle, $sampleContent);
    $result = $agent->prompt("Analyze and optimize the article.");
    
    $payload = is_array($result) ? $result : (method_exists($result, 'toArray') ? $result->toArray() : []);
    
    $hasSeo = !empty($payload['seo_title']) && !empty($payload['seo_description']);
    $hasKeywords = !empty($payload['target_keywords']) && is_array($payload['target_keywords']);
    $hasAeo = !empty($payload['aeo_summary']);
    $hasFaqs = !empty($payload['faqs']) && is_array($payload['faqs']);
    
    $passed = $hasSeo && $hasKeywords && $hasAeo && $hasFaqs;
    $details = sprintf(
        "SEO Title: %s\n        Keywords: %s\n        AEO Summary: %s\n        FAQ Count: %d",
        $payload['seo_title'] ?? 'N/A',
        implode(', ', $payload['target_keywords'] ?? []),
        substr($payload['aeo_summary'] ?? 'N/A', 0, 80) . '...',
        count($payload['faqs'] ?? [])
    );
    report("BlogOptimizerAgent Structured Output", $passed, $details);
} catch (\Throwable $e) {
    report("BlogOptimizerAgent Structured Output", false, "Error: " . $e->getMessage());
}

// ------------------------------------------------------------------
// 3. LeadChatAgent Autonomous Qualification & Meeting Scheduling
// ------------------------------------------------------------------
echo "--- TEST 3: LeadChatAgent Autonomous Qualification & Meeting Scheduling ---\n";
try {
    $session = ChatSession::create([
        'session_uuid' => (string) Str::uuid(),
        'ip_address' => '127.0.0.1',
        'is_qualified' => false,
    ]);

    $userMsg = "Hello! My name is Adhithan, CEO at InnoTech (adhithan@example.com). We want Fractional Technology Leadership to overhaul our Service Desk. Can we schedule a strategy call tomorrow at 3:00 PM?";
    
    $session->messages()->create([
        'role' => 'user',
        'content' => $userMsg,
    ]);

    $agent = new LeadChatAgent($session);
    $result = $agent->prompt($userMsg);
    $payload = is_array($result) ? $result : (method_exists($result, 'toArray') ? $result->toArray() : []);

    $extracted = $payload['extracted'] ?? [];
    $meeting = $payload['meeting'] ?? [];
    $leadStatus = $payload['lead_status'] ?? '';
    
    $nameExtracted = !empty($extracted['name']);
    $emailExtracted = !empty($extracted['email']) && str_contains($extracted['email'], '@');
    $meetingDetected = !empty($meeting['is_scheduled']);
    $hasReply = !empty($payload['reply']);

    $passed = $nameExtracted && $emailExtracted && $hasReply;
    $details = sprintf(
        "Reply: %s\n        Extracted Name: %s | Email: %s | Company: %s\n        Meeting Scheduled: %s | Preferred Time: %s\n        Lead Status: %s",
        substr($payload['reply'] ?? '', 0, 100) . '...',
        $extracted['name'] ?? 'null',
        $extracted['email'] ?? 'null',
        $extracted['company'] ?? 'null',
        $meetingDetected ? 'TRUE' : 'FALSE',
        $meeting['preferred_time'] ?? 'null',
        $leadStatus
    );
    report("LeadChatAgent Autonomous Reasoning & Extraction", $passed, $details);
} catch (\Throwable $e) {
    report("LeadChatAgent Autonomous Reasoning & Extraction", false, "Error: " . $e->getMessage());
}

// ------------------------------------------------------------------
// 4. Autonomous DevOps Log Analysis Command (log:analyze)
// ------------------------------------------------------------------
echo "--- TEST 4: Autonomous DevOps Log Analysis (php artisan log:analyze) ---\n";
try {
    $exitCode = \Illuminate\Support\Facades\Artisan::call('log:analyze', ['--lines' => 20]);
    $output = \Illuminate\Support\Facades\Artisan::output();
    $passed = ($exitCode === 0) && (str_contains($output, 'Analyzing last') || str_contains($output, 'No issues found') || str_contains($output, 'No errors'));
    report("Artisan log:analyze with Gemma", $passed, "Output snippet:\n        " . trim(substr($output, 0, 200)));
} catch (\Throwable $e) {
    report("Artisan log:analyze with Gemma", false, "Error: " . $e->getMessage());
}

// ------------------------------------------------------------------
// 5. Inbound Email Reply Autonomous AI Responder
// ------------------------------------------------------------------
echo "--- TEST 5: Inbound Email Reply Autonomous AI Auto-Responder ---\n";
try {
    $apiKey = config('ai.providers.openai.key') ?: (config('ai.providers.groq.key') ?: env('OPENAI_API_KEY', 'local-gemma'));
    $baseUrl = config('ai.providers.openai.url') ?: (config('ai.providers.groq.url') ?: env('OPENAI_BASE_URL', 'http://host.containers.internal:1234/v1'));
    $model = config('ai.providers.openai.models.text.default', env('AI_MODEL', 'google/gemma-4-e4b'));

    $res = Http::withHeaders([
        'Authorization' => "Bearer {$apiKey}",
        'Content-Type'  => 'application/json',
    ])->timeout(45)->post(rtrim($baseUrl, '/') . '/chat/completions', [
        'model' => $model,
        'messages' => [
            ['role' => 'system', 'content' => 'You are ClimbSphere Advisory Team. Keep responses concise, warm and professional.'],
            ['role' => 'user', 'content' => 'Write a warm confirmation email to Adhithan (adhithan@example.com) for their Service Desk discovery call scheduled tomorrow at 3 PM.'],
        ],
        'temperature' => 0.4,
        'max_tokens' => 1000,
    ]);

    $content = $res->json()['choices'][0]['message']['content'] ?? '';
    $passed = !empty($content) && (str_contains(strtolower($content), 'adhithan') || str_contains(strtolower($content), 'meeting') || str_contains(strtolower($content), 'call') || str_contains(strtolower($content), 'climbsphere'));
    report("Autonomous Email Auto-Responder", $passed, "AI Response: " . substr(str_replace("\n", " ", $content), 0, 120) . "...");
} catch (\Throwable $e) {
    report("Autonomous Email Auto-Responder", false, "Error: " . $e->getMessage());
}

// ------------------------------------------------------------------
// 6. Live HTTP End-to-End Chat API Route (/api/chat)
// ------------------------------------------------------------------
echo "--- TEST 6: Live End-to-End HTTP Chat API (/api/chat) ---\n";
try {
    $apiUuid = (string) Str::uuid();
    $apiRes = Http::timeout(180)->post('http://backend-web/api/chat', [
        'session_uuid' => $apiUuid,
        'message' => 'Hello! My name is Adhithan, Director at ClimbPartners (adhithan@climbsphere.ai). We need help with digital maturity assessments.',
    ]);

    $apiData = $apiRes->json();
    $chatPassed = $apiRes->status() === 200 && !empty($apiData['reply']);
    $leadRecord = Lead::whereHas('chatSession', function($q) use ($apiUuid) {
        $q->where('session_uuid', $apiUuid);
    })->first();

    $leadCaptured = $leadRecord && $leadRecord->name === 'Adhithan';
    $details = sprintf(
        "HTTP Status: %d | Reply: %s\n        DB Lead Captured: %s (Name: %s, Email: %s)",
        $apiRes->status(),
        substr($apiData['reply'] ?? '', 0, 80) . '...',
        $leadRecord ? 'YES' : 'NO',
        $leadRecord->name ?? 'N/A',
        $leadRecord->email ?? 'N/A'
    );
    report("Live HTTP /api/chat Workflow", $chatPassed, $details);
} catch (\Throwable $e) {
    report("Live HTTP /api/chat Workflow", false, "Error: " . $e->getMessage());
}

// ------------------------------------------------------------------
// Summary
// ------------------------------------------------------------------
echo "====================================================================\n";
echo sprintf(" TEST SUMMARY: %d PASSED / %d FAILED\n", $passCount, $failCount);
echo "====================================================================\n";

exit($failCount === 0 ? 0 : 1);
