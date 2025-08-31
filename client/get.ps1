# Hard-coded HEAD request to verify token + CID

try {
    $response = Invoke-WebRequest `
        -Uri "https://api.zunobackup.com/get.php?cid=QmQmUNKkg8RnC1HjE3wF6tJCg3ka2dLr1Lct26c9nmampnRg" `
        -Method Head `
        -Headers @{ "Authorization" = "Bearer ed906d935bcaf857f6f5c85e223495339279f6d45e973b5a71276bea3cbe741c" }

    Write-Host "Auth succeeded - Status: $($response.StatusCode)" -ForegroundColor Green
    Write-Host "CID: QmQmUNKkg8RnC1HjE3wF6tJCg3ka2dLr1Lct26c9nmampnRg"
    Write-Host "URL: https://api.zunobackup.com/get.php?cid=QmQmUNKkg8RnC1HjE3wF6tJCg3ka2dLr1Lct26c9nmampnRg"
}
catch {
    Write-Warning "Auth failed - $($_.Exception.Message)"
    Write-Warning "Token: ed906d935bcaf857f6f5c85e223495339279f6d45e973b5a71276bea3cbe741c"
    Write-Warning "CID: QmQmUNKkg8RnC1HjE3wF6tJCg3ka2dLr1Lct26c9nmampnRg"
}