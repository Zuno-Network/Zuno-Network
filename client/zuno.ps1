$endpoint = "https://api.zunobackup.com/get.php"
$token = "ed906d935bcaf857f6f5c85e223495339279f6d45e973b5a71276bea3cbe741c"

# Define where to save the file
$downloadPath = "C:\zuno\pending.bin"

# Make the request and save the file
try {
    Invoke-WebRequest `
        -Uri $endpoint `
        -Method GET `
        -Headers @{ "Authorization" = "Bearer $token" } `
        -OutFile $downloadPath `
        -ErrorAction Stop

    Write-Host "File saved to $downloadPath"
}


catch {
    $statusCode = $_.Exception.Response.StatusCode.Value__
    $body = $_.Exception.Response.GetResponseStream()
    $reader = New-Object System.IO.StreamReader($body)
    $errorText = $reader.ReadToEnd()

    Write-Warning "HTTP $statusCode"
    Write-Host " Response body:" -ForegroundColor Yellow
    Write-Host $errorText
}