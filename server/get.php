<?php
define('TOKEN_STORE', '/var/zuno/tokens.json');

// Load token store
$tokens = json_decode(file_get_contents(TOKEN_STORE), true);

// Extract token from Authorization header
$auth = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
if (!preg_match('/Bearer\s+([a-f0-9]{64})/', $auth, $matches)) {
    http_response_code(401);
    exit('Missing or invalid token');
}

$token = $matches[1];
$nodeId = null;

// Validate token and resolve node
foreach ($tokens as $id => $data) {
    if ($data['token'] === $token) {
        if (strtotime($data['expires']) < time()) {
            http_response_code(403);
            exit('Token expired');
        }
        $nodeId = $id;
        break;
    }
}

if (!$nodeId) {
    http_response_code(403);
    exit('Token not recognized');
}

// Validate CID
$cid = $_GET['cid'] ?? '';
if (!preg_match('/^[a-zA-Z0-9_-]+$/', $cid)) {
    http_response_code(400);
    exit('Invalid CID');
}

// Build file path with .dat extension
$path = "/var/zuno/nodes/$nodeId/$cid.dat";
if (!file_exists($path)) {
    http_response_code(404);
    exit('Chunk not found');
}

// Serve file
header('Content-Type: application/octet-stream');
header('Content-Length: ' . filesize($path));
header('Content-Disposition: attachment; filename="' . basename($path) . '"');
readfile($path);