<?php
/**
 * Cria PNGs mínimos válidos sem GD
 * Gera um quadrado roxo sólido como PNG válido
 * Uso: php tools/create-png.php
 */

$dir = __DIR__ . '/../public/assets/img/';
if (!is_dir($dir)) mkdir($dir, 0755, true);

function createPNG(string $path, int $size): void
{
    // Cor roxa: RGB(99, 102, 241)
    $r = 99; $g = 102; $b = 241;

    // Criar raw image data (cada linha: filter byte + RGB pixels)
    $rawData = '';
    for ($y = 0; $y < $size; $y++) {
        $rawData .= "\x00"; // filter: none
        for ($x = 0; $x < $size; $x++) {
            $rawData .= chr($r) . chr($g) . chr($b);
        }
    }

    $compressed = gzcompress($rawData);

    // PNG file
    $png = '';

    // Signature
    $png .= "\x89PNG\r\n\x1a\n";

    // IHDR chunk
    $ihdr = pack('N', $size) . pack('N', $size) . "\x08\x02\x00\x00\x00"; // 8bit RGB
    $png .= pngChunk('IHDR', $ihdr);

    // IDAT chunk
    $png .= pngChunk('IDAT', $compressed);

    // IEND chunk
    $png .= pngChunk('IEND', '');

    file_put_contents($path, $png);
}

function pngChunk(string $type, string $data): string
{
    $chunk = $type . $data;
    return pack('N', strlen($data)) . $chunk . pack('N', crc32($chunk));
}

// Gerar todos os ícones
$files = [
    'icon-192.png' => 192,
    'icon-512.png' => 512,
    'icon-maskable-192.png' => 192,
    'icon-maskable-512.png' => 512,
];

foreach ($files as $name => $size) {
    $path = $dir . $name;
    createPNG($path, $size);
    $filesize = filesize($path);
    echo "✅ {$name} ({$size}x{$size}) - {$filesize} bytes\n";
}

echo "\nPronto! Ícones PNG válidos gerados em public/assets/img/\n";
echo "Faça upload para o servidor.\n";
