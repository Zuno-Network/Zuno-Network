<?php
declare(strict_types=1);

$nodeId = validateToken();
$bucketDir = "/var/zuno/{$nodeId}/bucket";

if (!is_dir($bucketDir)) {
    http_response_code(404);
    exit("Bucket folder missing for node: $nodeId");
}

echo "✅ Bucket folder exists: $bucketDir\n";