<?php
declare(strict_types=1);

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/utils.php';


$nodeId = validateToken();
$manifestPath = __DIR__ . "/manifest_{$nodeId}.json";

try {
    $manifest = json_load($manifestPath);
} catch (RuntimeException $e) {
    http_response_code(404);
    exit($e->getMessage());
}

[$key, $meta] = manifest_next_pending($manifest);
if ($key === null) {
    http_response_code(204);
    exit;
}

$filePath = $meta['file_path'] ?? '';
if ($filePath === '' || !is_file($filePath)) {
    manifest_mark($manifest, $key, 'failed', client_ip());
    json_save($manifestPath, $manifest);
    http_response_code(410); // Gone / inconsistent manifest
    exit("File missing: $filePath");
}

$ok = serve_file($filePath, basename($key));
manifest_mark($manifest, $key, $ok ? 'done' : 'failed', client_ip());
json_save($manifestPath, $manifest);

if (!$ok) {
    error_log("Failed to stream file: $filePath for node $nodeId");
}
exit;