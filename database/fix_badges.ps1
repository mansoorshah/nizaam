# PowerShell script to fix all badges in schema_diagram.html

$file = "c:\xampp\htdocs\nizaam\database\schema_diagram.html"
$content = Get-Content $file -Raw

# Replace all PK badges (240cx)
$content = $content -replace '<circle class="pk-badge" cx="240" cy="60" r="12"/><text class="badge-text" x="240" y="64">PK</text>', '<rect class="pk-badge" x="245" y="57" width="25" height="16" rx="3"/><text class="badge-text" x="257.5" y="65">PK</text>'

# Replace all PK badges (260cx)
$content = $content -replace '<circle class="pk-badge" cx="260" cy="60" r="12"/><text class="badge-text" x="260" y="64">PK</text>', '<rect class="pk-badge" x="265" y="57" width="25" height="16" rx="3"/><text class="badge-text" x="277.5" y="65">PK</text>'

# Replace all FK badges (240cx, 80cy)
$content = $content -replace '<circle class="fk-badge" cx="240" cy="80" r="12"/><text class="badge-text" x="240" y="84">FK</text>', '<rect class="fk-badge" x="245" y="77" width="25" height="16" rx="3"/><text class="badge-text" x="257.5" y="85">FK</text>'

# Replace all FK badges (240cx, 100cy)
$content = $content -replace '<circle class="fk-badge" cx="240" cy="100" r="12"/><text class="badge-text" x="240" y="104">FK</text>', '<rect class="fk-badge" x="245" y="97" width="25" height="16" rx="3"/><text class="badge-text" x="257.5" y="105">FK</text>'

# Replace all FK badges (240cx, 120cy)
$content = $content -replace '<circle class="fk-badge" cx="240" cy="120" r="12"/><text class="badge-text" x="240" y="124">FK</text>', '<rect class="fk-badge" x="245" y="117" width="25" height="16" rx="3"/><text class="badge-text" x="257.5" y="125">FK</text>'

# Replace all FK badges (260cx, 80cy)
$content = $content -replace '<circle class="fk-badge" cx="260" cy="80" r="12"/><text class="badge-text" x="260" y="84">FK</text>', '<rect class="fk-badge" x="265" y="77" width="25" height="16" rx="3"/><text class="badge-text" x="277.5" y="85">FK</text>'

# Replace all FK badges (260cx, 100cy)
$content = $content -replace '<circle class="fk-badge" cx="260" cy="100" r="12"/><text class="badge-text" x="260" y="104">FK</text>', '<rect class="fk-badge" x="265" y="97" width="25" height="16" rx="3"/><text class="badge-text" x="277.5" y="105">FK</text>'

# Save the file
$content | Set-Content $file -NoNewline

Write-Host "✅ All PK/FK badges have been successfully replaced with rectangular badges!" -ForegroundColor Green
Write-Host "📊 File updated: $file" -ForegroundColor Cyan
