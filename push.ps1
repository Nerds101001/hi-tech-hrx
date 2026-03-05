param (
    [string]$Message
)

if (-not $Message) {
    $Message = Read-Host "Enter commit message"
}

if (-not $Message) {
    Write-Host "Error: Commit message is required." -ForegroundColor Red
    exit 1
}

Write-Host "Staging changes..." -ForegroundColor Cyan
git add .

Write-Host "Committing changes..." -ForegroundColor Cyan
git commit -m "$Message"

Write-Host "Pushing to GitHub..." -ForegroundColor Cyan
git push origin main

if ($LASTEXITCODE -eq 0) {
    Write-Host "Success! Changes pushed to GitHub." -ForegroundColor Green
} else {
    Write-Host "Error: Failed to push changes." -ForegroundColor Red
}
