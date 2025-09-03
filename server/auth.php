<?php
declare(strict_types=1);

define('TOKEN_STORE', '/var/zuno/node_auth.json');


/**
 * Validates the Bearer token and returns the resolved node ID (e.g., "node1").
 * Exits with appropriate HTTP status on failure.
 */
function validateToken(): string
{
    if (!is_file(TOKEN_STORE)) {
        http_response_code(500);
        exit('Auth store missing');
    }

    $raw = file_get_contents(TOKEN_STORE);
    $nodes = json_decode($raw, true);
    if (!is_array($nodes)) {
        http_response_code(500);
        exit('Auth store invalid');
    }

    $auth = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
    if (!preg_match('/Bearer\s+([a-f0-9]{64})/i', $auth, $m)) {
        http_response_code(401);
        exit('Missing or invalid token');
    }
    $token = strtolower($m[1]);

    foreach ($nodes as $nodeId => $data) {
        if (!isset($data['token'], $data['expires'])) {
            continue;
        }
        if (strtolower($data['token']) !== $token) {
            continue;
        }
        if (strtotime($data['expires']) < time()) {
            http_response_code(403);
            exit('Token expired');
        }
        return (string)$nodeId;
    }

    http_response_code(403);
    exit('Token not recognized');
}