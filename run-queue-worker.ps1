# PowerShell Script untuk menjalankan Queue Worker di Windows (Laragon)
# Untuk development/testing purposes

# Get the project directory
$projectPath = Get-Location
Write-Host "================================" -ForegroundColor Cyan
Write-Host "Eureka Kopi - Queue Worker" -ForegroundColor Cyan
Write-Host "================================" -ForegroundColor Cyan
Write-Host "Project Path: $projectPath" -ForegroundColor Green

# Check if PHP is available
$php = Get-Command php -ErrorAction SilentlyContinue
if (-not $php) {
    Write-Host "PHP not found! Make sure Laragon is running." -ForegroundColor Red
    exit 1
}

# Clear config cache
Write-Host "`n[1/3] Clearing config cache..." -ForegroundColor Yellow
php artisan config:clear
php artisan cache:clear

# Check jobs table
Write-Host "`n[2/3] Verifying jobs table..." -ForegroundColor Yellow
php artisan tinker --execute="echo DB::table('jobs')->count() . ' jobs in queue';"

# Start queue worker
Write-Host "`n[3/3] Starting Queue Worker..." -ForegroundColor Yellow
Write-Host "Queue Worker is running. Press Ctrl+C to stop.`n" -ForegroundColor Green
php artisan queue:work database --sleep=3 --tries=3 --verbose
