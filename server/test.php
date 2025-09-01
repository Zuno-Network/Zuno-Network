<?php
declare(strict_types=1);

$hello = $_GET['hello'] ?? null;

if ($hello !== null) {
    echo "✅ You passed: $hello\n";
} else {
    echo "❌ No 'hello' parameter found\n";
}