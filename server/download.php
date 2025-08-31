<?php
require_once __DIR__ . '/auth.php';

$nodeId = validateToken();
$bucketDir = "/var/zuno/{$nodeId}/bucket";

if (!is_dir($bucketDir)) {
    http_response_code(404);
    exit("Bucket folder missing for node: $nodeId");
}

echo "✅ Bucket folder exists: $bucketDir\n";