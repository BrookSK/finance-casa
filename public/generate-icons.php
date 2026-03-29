<?php
/**
 * Gerador de ícones PWA usando GD
 * Acesse UMA VEZ: https://lucasebia.lrvweb.com.br/generate-icons.php
 * Depois DELETE este arquivo por segurança.
 */

if (!extension_loaded('gd')) {
    die('Extensão GD não está habilitada no PHP. Habilite no php.ini.');
}

$configs = [
    ['name' => 'icon-192.png', 'size' => 192],
    ['name' => 'icon-512.png', 'size' => 512],
    ['name' => 'icon-maskable-192.png', 'size' => 192],
    ['name' => 'icon-maskable-512.png', 'size' => 512],
];

$dir = __DIR__ . '/assets/img/';
if (!is_dir($dir)) mkdir($dir, 0755, true);

foreach ($configs as $cfg) {
    $size = $cfg['size'];
    $img = imagecreatetruecolor($size, $size);
    imagesavealpha($img, true);
    imagealphablending($img, false);

    // Fundo transparente
    $transparent = imagecolorallocatealpha($img, 0, 0, 0, 127);
    imagefill($img, 0, 0, $transparent);
    imagealphablending($img, true);

    // Retângulo arredondado roxo
    $purple = imagecolorallocate($img, 99, 102, 241);
    $radius = (int)($size * 0.2);

    // Corpo central
    imagefilledrectangle($img, $radius, 0, $size - $radius, $size, $purple);
    imagefilledrectangle($img, 0, $radius, $size, $size - $radius, $purple);

    // Cantos arredondados
    imagefilledellipse($img, $radius, $radius, $radius * 2, $radius * 2, $purple);
    imagefilledellipse($img, $size - $radius, $radius, $radius * 2, $radius * 2, $purple);
    imagefilledellipse($img, $radius, $size - $radius, $radius * 2, $radius * 2, $purple);
    imagefilledellipse($img, $size - $radius, $size - $radius, $radius * 2, $radius * 2, $purple);

    // Símbolo "$" no centro
    $white = imagecolorallocate($img, 255, 255, 255);
    $fontSize = 5; // Maior fonte built-in do GD
    $char = '$';
    $charW = imagefontwidth($fontSize);
    $charH = imagefontheight($fontSize);

    // Desenhar "$" grande escalando manualmente
    $scale = (int)($size * 0.35 / $charH);
    $tempW = $charW;
    $tempH = $charH;
    $temp = imagecreatetruecolor($tempW, $tempH);
    $tempBg = imagecolorallocate($temp, 99, 102, 241);
    imagefill($temp, 0, 0, $tempBg);
    $tempWhite = imagecolorallocate($temp, 255, 255, 255);
    imagechar($temp, $fontSize, 0, 0, $char, $tempWhite);

    // Escalar o caractere
    $scaledW = $tempW * $scale;
    $scaledH = $tempH * $scale;
    $destX = (int)(($size - $scaledW) / 2);
    $destY = (int)(($size - $scaledH) / 2);
    imagecopyresized($img, $temp, $destX, $destY, 0, 0, $scaledW, $scaledH, $tempW, $tempH);
    imagedestroy($temp);

    // Salvar
    $path = $dir . $cfg['name'];
    imagepng($img, $path, 9);
    imagedestroy($img);

    echo "✅ Gerado: {$cfg['name']} ({$size}x{$size})<br>";
}

echo '<br><strong>Pronto! Ícones gerados em /assets/img/</strong>';
echo '<br><em>Delete este arquivo (generate-icons.php) por segurança.</em>';
echo '<br><br><a href="/dashboard">Ir para o Dashboard</a>';
