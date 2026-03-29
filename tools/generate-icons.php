<?php
/**
 * Gerador de ícones PWA — rodar LOCALMENTE
 * Uso: php tools/generate-icons.php
 */

if (!extension_loaded('gd')) {
    die("Extensão GD não habilitada. Instale: sudo apt install php-gd\n");
}

$dir = __DIR__ . '/../public/assets/img/';
if (!is_dir($dir)) mkdir($dir, 0755, true);

$files = [
    ['name' => 'icon-192.png', 'size' => 192],
    ['name' => 'icon-512.png', 'size' => 512],
    ['name' => 'icon-maskable-192.png', 'size' => 192],
    ['name' => 'icon-maskable-512.png', 'size' => 512],
];

foreach ($files as $f) {
    $s = $f['size'];
    $img = imagecreatetruecolor($s, $s);
    imagesavealpha($img, true);
    imagealphablending($img, false);

    $transp = imagecolorallocatealpha($img, 0, 0, 0, 127);
    imagefill($img, 0, 0, $transp);
    imagealphablending($img, true);

    $purple = imagecolorallocate($img, 99, 102, 241);
    $r = (int)($s * 0.2);

    imagefilledrectangle($img, $r, 0, $s - $r, $s, $purple);
    imagefilledrectangle($img, 0, $r, $s, $s - $r, $purple);
    imagefilledellipse($img, $r, $r, $r * 2, $r * 2, $purple);
    imagefilledellipse($img, $s - $r, $r, $r * 2, $r * 2, $purple);
    imagefilledellipse($img, $r, $s - $r, $r * 2, $r * 2, $purple);
    imagefilledellipse($img, $s - $r, $s - $r, $r * 2, $r * 2, $purple);

    // Desenhar "$"
    $white = imagecolorallocate($img, 255, 255, 255);
    $tmp = imagecreatetruecolor(imagefontwidth(5), imagefontheight(5));
    $tmpBg = imagecolorallocate($tmp, 99, 102, 241);
    imagefill($tmp, 0, 0, $tmpBg);
    imagechar($tmp, 5, 0, 0, '$', imagecolorallocate($tmp, 255, 255, 255));

    $scale = (int)($s * 0.35 / imagefontheight(5));
    $sw = imagefontwidth(5) * $scale;
    $sh = imagefontheight(5) * $scale;
    imagecopyresized($img, $tmp, (int)(($s-$sw)/2), (int)(($s-$sh)/2), 0, 0, $sw, $sh, imagefontwidth(5), imagefontheight(5));
    imagedestroy($tmp);

    imagepng($img, $dir . $f['name'], 9);
    imagedestroy($img);
    echo "✅ {$f['name']} ({$s}x{$s})\n";
}

echo "\nPronto! Faça upload da pasta public/assets/img/ para o servidor.\n";
