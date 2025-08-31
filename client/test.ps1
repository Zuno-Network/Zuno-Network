# Token and CID
$token = "ed906d935bcaf857f6f5c85e223495339279f6d45e973b5a71276bea3cbe741c"
$cid   = "QmQmUNKkg8RnC1HjE3wF6tJCg3ka2dLr1Lct26c9nmampnRg"
$url   = "https://api.zunobackup.com/get.php?cid=$cid"

# Authorization header
$headers = @{ Authorization = "Bearer $token" }

# Perform HEAD request to verify token + CID
try {
    $response = Invoke-WebRequest -Uri $url -Method Head -Headers $headers -UseBasicParsing
    Write-Host "Auth succeeded — Status: $($response.StatusCode)"
    Write-Host "CID: $cid"
    Write-Host "URL: $url"
} catch {
    Write-Warning "Auth failed — $($_.Exception.Message)"
    Write-Warning "Token: $token"
    Write-Warning "CID: $cid"
}
