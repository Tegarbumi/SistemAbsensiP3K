# ============================================
#  push.ps1 - Script Push ke GitHub
#  Repo: Tegarbumi/SistemAbsensiP3K
# ============================================

$projectDir = "c:\xampp\htdocs\SistemAbsensiP3K"
Set-Location $projectDir

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "   PUSH SCRIPT - SistemAbsensiP3K" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# 1. Cek status perubahan
Write-Host "[1] Mengecek perubahan file..." -ForegroundColor Yellow
git status
Write-Host ""

# 2. Minta pesan commit
$commitMsg = Read-Host "Masukkan pesan commit (Enter untuk pesan default)"
if ([string]::IsNullOrWhiteSpace($commitMsg)) {
    $timestamp = Get-Date -Format "yyyy-MM-dd HH:mm"
    $commitMsg = "update: perubahan pada $timestamp"
}

Write-Host ""

# 3. Add semua perubahan
Write-Host "[2] Menambahkan semua perubahan..." -ForegroundColor Yellow
git add .
Write-Host "    Semua file di-stage." -ForegroundColor Green
Write-Host ""

# 4. Commit
Write-Host "[3] Membuat commit..." -ForegroundColor Yellow
git commit -m $commitMsg
Write-Host ""

# 5. Push ke GitHub
Write-Host "[4] Mendorong ke GitHub (origin/main)..." -ForegroundColor Yellow
git push origin main

Write-Host ""
if ($LASTEXITCODE -eq 0) {
    Write-Host "========================================" -ForegroundColor Green
    Write-Host "   BERHASIL di-push ke GitHub!" -ForegroundColor Green
    Write-Host "   https://github.com/Tegarbumi/SistemAbsensiP3K" -ForegroundColor Green
    Write-Host "========================================" -ForegroundColor Green
} else {
    Write-Host "========================================" -ForegroundColor Red
    Write-Host "   GAGAL push. Cek error di atas." -ForegroundColor Red
    Write-Host "========================================" -ForegroundColor Red
}

Write-Host ""
