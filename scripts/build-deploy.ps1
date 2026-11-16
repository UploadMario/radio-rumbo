[CmdletBinding()]
param(
    [string]$OutputDirectory = "build"
)

$ErrorActionPreference = "Stop"

$root = (Resolve-Path (Join-Path $PSScriptRoot "..")).Path
$timestamp = Get-Date -Format "yyyyMMdd-HHmmss"
$runId = [guid]::NewGuid().ToString("N")
$staging = Join-Path ([IO.Path]::GetTempPath()) "radio-rumbo-deploy-$runId"
$output = if ([IO.Path]::IsPathRooted($OutputDirectory)) {
    [IO.Path]::GetFullPath($OutputDirectory)
} else {
    [IO.Path]::GetFullPath((Join-Path $root $OutputDirectory))
}
$zipPath = Join-Path $output "radio-rumbo-donweb-$timestamp-$runId.zip"

$requiredFiles = @(
    "composer.json",
    "composer.lock",
    "vendor/autoload.php",
    "public/.htaccess",
    "public/index.php",
    "public/bootstrap.php",
    "config/app.php",
    "config/database.php",
    "config/default-content.php"
)

$requiredDirectories = @(
    "app",
    "config",
    "resources",
    "public",
    "public/assets/dist",
    "vendor"
)

$includedPaths = @(
    "app",
    "config",
    "resources",
    "public",
    "vendor",
    "composer.json",
    "composer.lock"
)

$excludedNames = @(
    ".env",
    ".env.local",
    ".env.production",
    ".env.*",
    "*.log",
    "*.sql",
    "*.sqlite",
    "*.db",
    "*.bak",
    "*.backup",
    "phpunit.xml",
    "phpstan.neon",
    "phpcs.xml"
)

$developmentVendorPrefixes = @()
$lockPath = Join-Path $root "composer.lock"
if (Test-Path -LiteralPath $lockPath -PathType Leaf) {
    $lock = Get-Content -LiteralPath $lockPath -Raw | ConvertFrom-Json
    $runtimePrefixes = @($lock.packages | ForEach-Object { ($_.name -split '/')[0] } | Sort-Object -Unique)
    $developmentVendorPrefixes = @($lock.'packages-dev' | ForEach-Object {
        ($_.name -split '/')[0]
    } | Where-Object { $runtimePrefixes -notcontains $_ } | Sort-Object -Unique)
}

function Test-ExcludedName([string]$Name) {
    foreach ($pattern in $excludedNames) {
        if ($Name -like $pattern) {
            return $true
        }
    }
    return $false
}

function Assert-RequiredResources {
    foreach ($relativePath in $requiredFiles) {
        $path = Join-Path $root ($relativePath -replace '/', '\')
        if (-not (Test-Path -LiteralPath $path -PathType Leaf)) {
            throw "Falta un archivo obligatorio para producción: $relativePath"
        }
    }

    foreach ($relativePath in $requiredDirectories) {
        $path = Join-Path $root ($relativePath -replace '/', '\')
        if (-not (Test-Path -LiteralPath $path -PathType Container)) {
            throw "Falta un directorio obligatorio para producción: $relativePath"
        }
    }
}

function Assert-NoReparsePoints([string]$Path) {
    $item = Get-Item -LiteralPath $Path -Force
    if ($item.Attributes -band [IO.FileAttributes]::ReparsePoint) {
        throw "No se empaquetan enlaces o puntos de reanálisis: $($item.FullName.Substring($root.Length + 1))"
    }

    if ($item.PSIsContainer) {
        $reparsePoints = @(Get-ChildItem -LiteralPath $Path -Force -Recurse | Where-Object {
            $_.Attributes -band [IO.FileAttributes]::ReparsePoint
        })
    } else {
        $reparsePoints = @()
    }
    if ($reparsePoints.Count -gt 0) {
        $relative = $reparsePoints | ForEach-Object { $_.FullName.Substring($root.Length + 1) }
        throw "No se empaquetan enlaces o puntos de reanálisis: $($relative -join ', ')"
    }
}

function Copy-IncludedPath([string]$RelativePath) {
    $source = Join-Path $root ($RelativePath -replace '/', '\')
    $destination = Join-Path $staging ($RelativePath -replace '/', '\')

    if (Test-Path -LiteralPath $source -PathType Container) {
        New-Item -ItemType Directory -Path $destination -Force | Out-Null
        Get-ChildItem -LiteralPath $source -Force -Recurse -File | ForEach-Object {
            if (Test-ExcludedName $_.Name) {
                return
            }

            $relativeFile = $_.FullName.Substring($root.Length + 1)
            if ($RelativePath -eq "vendor" -and $developmentVendorPrefixes.Count -gt 0) {
                $vendorPrefix = ($relativeFile -split '[\\/]')[1]
                if ($developmentVendorPrefixes -contains $vendorPrefix) {
                    return
                }
            }
            $targetFile = Join-Path $staging $relativeFile
            $targetDirectory = Split-Path -Parent $targetFile
            New-Item -ItemType Directory -Path $targetDirectory -Force | Out-Null
            Copy-Item -LiteralPath $_.FullName -Destination $targetFile
        }
        return
    }

    if (Test-ExcludedName (Split-Path -Leaf $source)) {
        throw "Un recurso obligatorio está excluido por seguridad: $RelativePath"
    }

    $destinationDirectory = Split-Path -Parent $destination
    New-Item -ItemType Directory -Path $destinationDirectory -Force | Out-Null
    Copy-Item -LiteralPath $source -Destination $destination
}

try {
    Assert-RequiredResources

    foreach ($relativePath in $includedPaths) {
        Assert-NoReparsePoints (Join-Path $root ($relativePath -replace '/', '\'))
    }

    if (Test-Path -LiteralPath $staging) {
        throw "La ubicación temporal generada ya existe; se cancela para evitar tocar datos ajenos: $staging"
    }

    if (Test-Path -LiteralPath $zipPath) {
        throw "El ZIP de destino ya existe; se cancela para evitar sobrescritura: $zipPath"
    }

    New-Item -ItemType Directory -Path $staging -Force | Out-Null
    New-Item -ItemType Directory -Path $output -Force | Out-Null

    foreach ($relativePath in $includedPaths) {
        Copy-IncludedPath $relativePath
    }

    foreach ($directory in @("storage/logs", "storage/rate-limit", "storage/sessions", "public/uploads")) {
        New-Item -ItemType Directory -Path (Join-Path $staging ($directory -replace '/', '\')) -Force | Out-Null
    }

    $packageEntries = @(Get-ChildItem -LiteralPath $staging -Force)
    if ($packageEntries.Count -eq 0) {
        throw "El staging quedó vacío; no se generará un paquete inválido."
    }

    Compress-Archive -Path (Join-Path $staging "*") -DestinationPath $zipPath

    if (-not (Test-Path -LiteralPath $zipPath -PathType Leaf)) {
        throw "No se pudo verificar la creación del ZIP: $zipPath"
    }

    Write-Host "Paquete DonWeb generado correctamente:"
    Write-Host $zipPath
    Write-Host "El paquete excluye secretos, pruebas, cachés, logs, sesiones, uploads existentes y herramientas de desarrollo."
    Write-Host "Crear .env de producción manualmente en el servidor; nunca subir el .env local."
} finally {
    if (Test-Path -LiteralPath $staging) {
        Remove-Item -LiteralPath $staging -Recurse -Force
    }
}
