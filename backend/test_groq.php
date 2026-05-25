<?php

echo "=== Test: Actual LeadChatAgent ===\n";

try {
    $agent = new \App\AI\Agents\LeadChatAgent();
    $result = $agent->prompt('hello');
    echo "SUCCESS!\n";
    echo json_encode($result, JSON_PRETTY_PRINT) . "\n";
} catch (\Throwable $e) {
    echo "EXCEPTION CLASS: " . get_class($e) . "\n";
    echo "MESSAGE: " . $e->getMessage() . "\n";
    echo "CODE: " . $e->getCode() . "\n";
    echo "FILE: " . $e->getFile() . ":" . $e->getLine() . "\n";
    
    // Walk up the exception chain
    $prev = $e->getPrevious();
    $depth = 1;
    while ($prev) {
        echo "\n--- Previous exception ($depth) ---\n";
        echo "CLASS: " . get_class($prev) . "\n";
        echo "MESSAGE: " . $prev->getMessage() . "\n";
        echo "CODE: " . $prev->getCode() . "\n";
        echo "FILE: " . $prev->getFile() . ":" . $prev->getLine() . "\n";
        
        if ($prev instanceof \Illuminate\Http\Client\RequestException && $prev->response) {
            echo "HTTP STATUS: " . $prev->response->status() . "\n";
            echo "HTTP BODY: " . substr($prev->response->body(), 0, 500) . "\n";
            echo "RATE LIMIT HEADERS:\n";
            foreach ($prev->response->headers() as $name => $values) {
                if (str_contains(strtolower($name), 'ratelimit') || str_contains(strtolower($name), 'retry')) {
                    echo "  $name: " . implode(', ', $values) . "\n";
                }
            }
        }
        
        $prev = $prev->getPrevious();
        $depth++;
        if ($depth > 5) break;
    }
}
