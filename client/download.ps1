$baseUrl = "https://zunobackup.com/j2k8d5h6p1b4na7_node1"
$downloadFolder = "$PSScriptRoot\downloads"
New-Item -ItemType Directory -Path $downloadFolder -Force | Out-Null
$response = Invoke-WebRequest -Uri $baseUrl -UseBasicParsing
$fileLinks = $response.Links | Where-Object { $_.href -match "\.zc$" }
foreach ($link in $fileLinks) {
    $fileUrl = if ($link.href -match "^https?://") { $link.href } else { "$baseUrl/$($link.href)" }
    $fileName = Split-Path $fileUrl -Leaf
    $destination = Join-Path $downloadFolder $fileName
    Invoke-WebRequest -Uri $fileUrl -OutFile $destination -ErrorAction Stop
}