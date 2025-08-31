<?php
declare(strict_types=1);

function json_load(string $path): array {
    if (!is_file($path)) throw new RuntimeException("Missing file: $path");
    $data = json_decode(file_get_contents($path), true);
    if (!is_array($data)) throw new RuntimeException("Invalid JSON: $path");
    return $data;
}

function json_save(string $path, array $data): void {
    $ok = file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    if ($ok === false) throw new RuntimeException("Failed to write: $path");
}

function client_ip(): string {
    return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
}

function serve_file(string $filePath, string $downloadName): bool {
    if (!is_file($filePath)) {
        http_response_code(404);
        return false;
    }
    header('Content-Type: application/octet-stream');
    header('Content-Length: ' . (string)filesize($filePath));
    header('Content-Disposition: attachment; filename="' . $downloadName . '"');
    return readfile($filePath) !== false;
}

/**
 * Finds the first pending manifest entry. Returns [key, meta] or [null, null].
 */
function manifest_next_pending(array $manifest): array {
    foreach ($manifest as $k => $meta) {
        $status = $meta['status'] ?? 'pending';
        if ($status === 'pending') return [$k, $meta];
    }
    return [null, null];
}

/**
 * Marks outcome for a manifest entry with timestamp and IP.
 */
function manifest_mark(array &$manifest, string $key, string $status, string $ip): void {
    $manifest[$key]['status'] = $status;
    $manifest[$key]['last_time_downloaded'] = date('c');
    $manifest[$key]['IP_address_downloaded'] = $ip;
}