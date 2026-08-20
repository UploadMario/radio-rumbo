param(
    [string]$OutputDirectory = "build"
)

$ErrorActionPreference = "Stop"

$root = Split-Path -Parent $PSScriptRoot
$timestamp = Get-Date -Format "yyyyMMdd-HHmmss"
$staging = Join-Path $root ".deploy-staging"
$output = Join-Path $root $OutputDirectory
$zipPath = Join-Path $output "radio-rumbo-donweb-$timestamp.zip"

$excludedDirectories = @(
    ".git",
    ".github",
    ".phpunit.cache",
    ".deploy-staging",
    "build",
    "tests",
    "storage\logs",
    "storage\rate-limit"
)

$excludedFiles = @(
    ".env",
    ".env.local",
    ".env.production",
    ".phpunit.result.cache"
)

if (Test-Path $staging) {
    Remove-Item -LiteralPath $staging -Recurse -Force
}

New-Item -ItemType Directory -Path $staging | Out-Null
New-Item -ItemType Directory -Path $output -Force | Out-Null

Get-ChildItem -LiteralPath $root -Force | ForEach-Object {
    $relative = $_.Name

    if ($excludedFiles -contains $relative) {
        return
    }

    if ($excludedDirectories -contains $relative) {
        return
    }

    Copy-Item -LiteralPath $_.FullName -Destination (Join-Path $staging $relative) -Recurse -Force
}

foreach ($path in $excludedDirectories) {
    $target = Join-Path $staging $path
    if (Test-Path $target) {
        Remove-Item -LiteralPath $target -Recurse -Force
    }
}

foreach ($file in $excludedFiles) {
    $target = Join-Path $staging $file
    if (Test-Path $target) {
        Remove-Item -LiteralPath $target -Force
    }
}

Compress-Archive -Path (Join-Path $staging "*") -DestinationPath $zipPath -Force
Remove-Item -LiteralPath $staging -Recurse -Force

Write-Host "Paquete DonWeb generado:"
Write-Host $zipPath
Write-Host "Crear .env de produccion manualmente en el servidor. Nunca subir el .env local."
