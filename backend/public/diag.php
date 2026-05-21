<?php
/**
 * ClimbSphere Diagnostic Script
 * This file helps diagnose session, database, and symlink issues on the production server.
 */

// Disable caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

session_start();

// Increment page views in session to test session persistence
if (!isset($_SESSION['views'])) {
    $_SESSION['views'] = 1;
} else {
    $_SESSION['views']++;
}

// Check database connection and .env settings by booting Laravel if possible
$laravel_status = "Not booted";
$laravel_db_status = "N/A";
$laravel_env_details = [];
$symlink_test_result = "N/A";

// Auto-fix symlink if requested
$auto_fix_symlink = isset($_GET['fix_symlink']);
$symlink_created = false;
$symlink_error = '';

$target = dirname(__DIR__) . '/storage/app/public';
$shortcut = dirname(__DIR__) . '/public/storage';

if ($auto_fix_symlink) {
    if (file_exists($shortcut) || is_link($shortcut)) {
        // Try to delete existing symlink or folder
        if (is_link($shortcut)) {
            unlink($shortcut);
        } else {
            // It's a folder, rename it
            rename($shortcut, $shortcut . '_backup_' . time());
        }
    }
    if (symlink($target, $shortcut)) {
        $symlink_created = true;
    } else {
        $symlink_error = error_get_last()['message'] ?? 'Unknown error';
    }
}

// Boot Laravel to check DB and config
try {
    require __DIR__.'/../vendor/autoload.php';
    $app = require_once __DIR__.'/../bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $response = $kernel->handle(
        $request = Illuminate\Http\Request::capture()
    );
    
    $laravel_status = "Laravel booted successfully!";
    
    // Check DB
    try {
        $db = Illuminate\Support\Facades\DB::connection()->getPdo();
        $laravel_db_status = "Connected successfully to " . Illuminate\Support\Facades\DB::connection()->getDatabaseName();
    } catch (\Exception $e) {
        $laravel_db_status = "Failed: " . $e->getMessage();
    }
    
    $sessions_dir = dirname(__DIR__) . '/storage/framework/sessions';
    $sessions_dir_writable = is_writable($sessions_dir);
    $sessions_dir_exists = file_exists($sessions_dir);
    
    $laravel_env_details = [
        'APP_NAME' => config('app.name'),
        'APP_ENV' => config('app.env'),
        'APP_URL' => config('app.url'),
        'APP_DEBUG' => config('app.debug') ? 'true' : 'false',
        'SESSION_DRIVER' => config('session.driver'),
        'SESSION_SECURE' => config('session.secure') ? 'true' : 'false',
        'SESSION_COOKIE' => config('session.cookie'),
        'SESSION_PATH' => config('session.path'),
        'SESSION_DOMAIN' => config('session.domain') ?? 'null',
        'FILESYSTEM_DISK' => config('filesystems.default'),
        'SESSION_DIR_EXISTS' => $sessions_dir_exists ? 'true' : 'false',
        'SESSION_DIR_WRITABLE' => $sessions_dir_writable ? 'true' : 'false',
    ];
} catch (\Exception $e) {
    $laravel_status = "Failed to boot Laravel: " . $e->getMessage();
}

// Check symlink details
$shortcut_exists = file_exists($shortcut);
$shortcut_is_link = is_link($shortcut);
$shortcut_target = $shortcut_is_link ? readlink($shortcut) : null;
$target_exists = file_exists($target);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ClimbSphere System Diagnostics</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background: #0f172a; color: #e2e8f0; padding: 2rem; margin: 0; }
        .container { max-width: 900px; margin: 0 auto; background: #1e293b; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        h1 { color: #f8fafc; border-bottom: 2px solid #334155; padding-bottom: 0.5rem; }
        h2 { color: #38bdf8; margin-top: 1.5rem; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { text-align: left; padding: 0.75rem; border-bottom: 1px solid #334155; }
        th { background: #334155; color: #f8fafc; }
        .status-ok { color: #4ade80; font-weight: bold; }
        .status-err { color: #f87171; font-weight: bold; }
        .btn { display: inline-block; background: #0284c7; color: white; padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none; font-weight: bold; margin-top: 1rem; }
        .btn:hover { background: #0369a1; }
        pre { background: #0f172a; padding: 1rem; border-radius: 4px; overflow-x: auto; color: #38bdf8; }
    </style>
</head>
<body>
<div class="container">
    <h1>ClimbSphere System Diagnostics</h1>
    
    <h2>1. Native PHP Session Test (State Persistence)</h2>
    <table>
        <tr><th>Metric</th><th>Value</th><th>Status</th></tr>
        <tr>
            <td>Session Views</td>
            <td><?php echo $_SESSION['views']; ?></td>
            <td>Refresh page. If this number increases, cookies and PHP sessions are working in this browser.</td>
        </tr>
        <tr>
            <td>PHP Session Cookie Name</td>
            <td><?php echo session_name(); ?></td>
            <td>-</td>
        </tr>
        <tr>
            <td>PHP Session Cookie Value</td>
            <td><?php echo $_COOKIE[session_name()] ?? 'None (first load or blocked)'; ?></td>
            <td>-</td>
        </tr>
        <tr>
            <td>HTTPS Active</td>
            <td><?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'Yes' : 'No'; ?></td>
            <td>-</td>
        </tr>
    </table>
    
    <h2>2. Laravel Boot & Database</h2>
    <table>
        <tr><th>Metric</th><th>Value</th></tr>
        <tr><td>Laravel Status</td><td><?php echo $laravel_status; ?></td></tr>
        <tr><td>Laravel Database Connection</td><td><?php echo $laravel_db_status; ?></td></tr>
    </table>
    
    <h2>3. Laravel Config Settings</h2>
    <?php if (empty($laravel_env_details)): ?>
        <p class="status-err">Laravel configuration could not be loaded.</p>
    <?php else: ?>
        <table>
            <tr><th>Config Key</th><th>Value</th></tr>
            <?php foreach ($laravel_env_details as $k => $v): ?>
                <tr><td><?php echo htmlspecialchars($k); ?></td><td><?php echo htmlspecialchars($v); ?></td></tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    
    <h2>4. Public Storage Symlink Verification</h2>
    <table>
        <tr><th>Metric</th><th>Value</th><th>Status</th></tr>
        <tr>
            <td>Expected Symlink (Shortcut)</td>
            <td><?php echo htmlspecialchars($shortcut); ?></td>
            <td><?php echo $shortcut_exists ? '<span class="status-ok">Exists</span>' : '<span class="status-err">Missing</span>'; ?></td>
        </tr>
        <tr>
            <td>Is Link?</td>
            <td><?php echo $shortcut_is_link ? 'Yes' : 'No'; ?></td>
            <td><?php echo $shortcut_is_link ? '<span class="status-ok">OK</span>' : '<span class="status-err">Not a symlink (or folder instead of link)</span>'; ?></td>
        </tr>
        <tr>
            <td>Current Link Target</td>
            <td><?php echo htmlspecialchars($shortcut_target ?? 'N/A'); ?></td>
            <td>-</td>
        </tr>
        <tr>
            <td>Expected Target (Real Folder)</td>
            <td><?php echo htmlspecialchars($target); ?></td>
            <td><?php echo $target_exists ? '<span class="status-ok">Exists</span>' : '<span class="status-err">Missing (storage folder does not exist)</span>'; ?></td>
        </tr>
    </table>
    
    <?php if ($symlink_created): ?>
        <p class="status-ok">Successfully attempted to create/recreate symlink!</p>
    <?php elseif ($symlink_error): ?>
        <p class="status-err">Failed to create symlink: <?php echo htmlspecialchars($symlink_error); ?></p>
    <?php endif; ?>
    
    <p>
        <a href="?fix_symlink=1" class="btn">Attempt to Auto-Fix Storage Symlink</a>
    </p>
    
    <h2>5. Request HTTP Headers</h2>
    <pre><?php print_r(getallheaders()); ?></pre>
    
    <h2>6. Server $_SERVER Variables</h2>
    <pre><?php print_r($_SERVER); ?></pre>
</div>
</body>
</html>
