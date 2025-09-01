$endpoint = "https://api.zunobackup.com/get.php"
$token = "ed906d935bcaf857f6f5c85e223495339279f6d45e973b5a71276bea3cbe741c"

try {
    $response = Invoke-WebRequest `
        -Uri $endpoint `
        -Method GET `
        -Headers @{ "Authorization" = "Bearer $token" } `
        -ErrorAction Stop

    # Extract filename from Content-Disposition header
    $contentDisposition = $response.Headers["Content-Disposition"]
    $filename = if ($contentDisposition -match 'filename="([^"]+)"') { $matches[1] } else { "pending.bin" }

    # Define full path
    $downloadPath = "C:\zuno\$filename"

    # Save file manually
    $response.RawContentStream.Position = 0
    $reader = New-Object System.IO.BinaryReader($response.RawContentStream)
    [System.IO.File]::WriteAllBytes($downloadPath, $reader.ReadBytes($response.RawContentStream.Length))

    Write-Host "File saved to $downloadPath"
}
catch {
    if ($_.Exception.Response) {
        $statusCode = $_.Exception.Response.StatusCode.Value__
        $body = $_.Exception.Response.GetResponseStream()
        $reader = New-Object System.IO.StreamReader($body)
        $errorText = $reader.ReadToEnd()

        Write-Warning "HTTP $statusCode"
        Write-Host "Response body:"
        Write-Host $errorText
    } else {
        Write-Host "Request failed before receiving a response"
        Write-Host "Error message:"
        Write-Host $_.Exception.Message
    }
}