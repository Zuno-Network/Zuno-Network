# basic.ps1 — hard-coded URL, header, and method

try {
    $response = Invoke-WebRequest `
        -Uri "https://api.zunobackup.com/basic.php" `
        -Method Get `
        -Headers @{ "Authorization" = "Bearer 123" }

    Write-Host "Success - Status: $($response.StatusCode)" -ForegroundColor Green
    Write-Host "Response: $($response.Content)"
}
catch {
    Write-Host "Failed - $($_.Exception.Message)" -ForegroundColor Red
}