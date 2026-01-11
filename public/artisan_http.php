<?php
// Temporary web trigger to run Laravel Artisan commands when CLI is not available.
// Secure: requires token query param. Remove after use.
$TOKEN = 'ARTISAN1234';
if (!isset($_GET['token']) || $_GET['token'] !== $TOKEN) {
    http_response_code(403);
    echo "Forbidden\n";
    exit;
}
$root = realpath(__DIR__ . '/..');
if ($root === false) {
    echo "Cannot determine project root\n";
    exit;
}
require $root . '/vendor/autoload.php';
$app = require $root . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$commands = [
    'config:cache',
    'route:cache',
    'view:clear',
    'view:cache'
];
foreach ($commands as $cmd) {
    echo "Running: $cmd\n";
    ob_start();
    $status = $kernel->call($cmd);
    $output = $kernel->output();
    echo nl2br(htmlspecialchars($output));
    ob_end_flush();
    flush();
}
// Try to remove this script for safety
$self = __FILE__;
if (is_writable($self)) {
    @unlink($self);
    echo "\nCleanup: removed artisan runner script\n";
} else {
    echo "\nNote: could not remove artisan runner script automatically. Please delete public/artisan_http.php\n";
}
echo "Done.\n";
