<?php
/**
 * Gera ícones PWA mínimos sem extensão GD
 * Cria PNGs roxos válidos usando SVG → conversão
 * Uso: php tools/generate-icons-nogd.php
 */

$dir = __DIR__ . '/../public/assets/img/';
if (!is_dir($dir)) mkdir($dir, 0755, true);

$sizes = [192, 512];
$names = ['icon', 'icon-maskable'];

foreach ($names as $prefix) {
    foreach ($sizes as $size) {
        $r = (int)($size * 0.2);
        $fontSize = (int)($size * 0.45);
        $textY = (int)($size * 0.62);

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="{$size}" height="{$size}" viewBox="0 0 {$size} {$size}">
  <rect width="{$size}" height="{$size}" rx="{$r}" ry="{$r}" fill="#6366f1"/>
  <text x="50%" y="{$textY}" text-anchor="middle" font-family="Arial,sans-serif" font-weight="bold" font-size="{$fontSize}" fill="white">$</text>
</svg>
SVG;

        $filename = "{$prefix}-{$size}";
        // Salvar como SVG
        file_put_contents($dir . $filename . '.svg', $svg);
        echo "✅ {$filename}.svg\n";
    }
}

echo "\nSVGs gerados! Agora precisa converter para PNG.\n";
echo "Opções:\n";
echo "1. Use https://svgtopng.com/ para converter online\n";
echo "2. Ou instale Inkscape: inkscape icon-192.svg -o icon-192.png\n";
echo "3. Ou habilite GD no PHP e rode: php tools/generate-icons.php\n";
