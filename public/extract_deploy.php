<?php
// Temporary deployment extractor. Remove after use.
$TOKEN = 'DEPLOY1234';
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
$files = [
    $root . '/vendor.zip' => $root,
    $root . '/public_build.zip' => $root . '/public'
];
$zip = new ZipArchive();
foreach ($files as $zipPath => $dest) {
    echo "Processing: $zipPath -> $dest\n";
    if (!file_exists($zipPath)) {
        echo "  Not found: $zipPath\n";
        continue;
    }
    if (!is_dir($dest)) {
        if (!mkdir($dest, 0755, true)) {
            echo "  Failed to create destination: $dest\n";
            continue;
        }
    }
    $res = $zip->open($zipPath);
    if ($res !== true) {
        echo "  Failed to open zip ($res): $zipPath\n";
        continue;
    }
    // Extract safely
    $ok = $zip->extractTo($dest);
    $zip->close();
    if ($ok) {
        echo "  Extracted: $zipPath\n";
        @unlink($zipPath);
    } else {
        echo "  Extraction failed for: $zipPath\n";
    }
}
// Try to remove this script for safety
$self = __FILE__;
if (is_writable($self)) {
    @unlink($self);
    echo "Cleanup: removed extractor script\n";
} else {
    echo "Note: could not remove extractor script automatically. Please delete public/extract_deploy.php\n";
}
echo "Done.\n";
