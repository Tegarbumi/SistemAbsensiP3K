# ============================================
#  pull.ps1 - Script Pull dari GitHub
#  Repo: Tegarbumi/SistemAbsensiP3K
# ============================================

$projectDir = "c:\xampp\htdocs\SistemAbsensiP3K"
Set-Location $projectDir

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "   PULL SCRIPT - SistemAbsensiP3K" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# 1. Cek status lokal
Write-Host "[1] Mengecek status lokal..." -ForegroundColor Yellow
$status = git status --porcelain
if ($status) {
    Write-Host "    Ada perubahan lokal yang belum di-commit:" -ForegroundColor Red
    git status
    Write-Host ""
    $confirm = Read-Host "    Lanjut pull? Perubahan lokal mungkin konflik (y/n)"
    if ($confirm -ne "y") {
        Write-Host "    Pull dibatalkan." -ForegroundColor Red
        exit
    }
} else {
    Write-Host "    Working directory bersih." -ForegroundColor Green
}

Write-Host ""

# 2. Pull dari GitHub
Write-Host "[2] Mengambil update dari GitHub (origin/main)..." -ForegroundColor Yellow
git pull origin main

Write-Host ""

if ($LASTEXITCODE -eq 0) {
    # 3. Install dependencies jika composer.json berubah
    Write-Host "[3] Mengecek dependency PHP..." -ForegroundColor Yellow
    composer install --no-interaction --quiet
    Write-Host "    Composer OK." -ForegroundColor Green
    Write-Host ""

    # 4. Build assets jika package.json berubah
    Write-Host "[4] Build assets (Vite)..." -ForegroundColor Yellow
    npm run build
    Write-Host ""

    # 5. Jalankan migrasi jika ada
    Write-Host "[5] Menjalankan migrasi database..." -ForegroundColor Yellow
    php artisan migrate --force
    Write-Host ""

    # 6. Clear cache
    Write-Host "[6] Membersihkan cache..." -ForegroundColor Yellow
    php artisan view:clear
    php artisan config:clear
    php artisan cache:clear 2>$null
    Write-Host "    Cache dibersihkan." -ForegroundColor Green

    Write-Host ""
    Write-Host "========================================" -ForegroundColor Green
    Write-Host "   BERHASIL! Project sudah up-to-date." -ForegroundColor Green
    Write-Host "========================================" -ForegroundColor Green
} else {
    Write-Host "========================================" -ForegroundColor Red
    Write-Host "   GAGAL pull. Cek error di atas." -ForegroundColor Red
    Write-Host "   Coba: git stash lalu jalankan lagi." -ForegroundColor Red
    Write-Host "========================================" -ForegroundColor Red
}

Write-Host ""
