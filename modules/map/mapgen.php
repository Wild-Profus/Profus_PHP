<?php
$srcpath = htmlspecialchars($_POST['source']);
$filepath = htmlspecialchars($_POST['fichier']);
$heightres = htmlspecialchars($_POST['hauteur']);
$widthres = htmlspecialchars($_POST['largeur']);
$im = @imagecreatetruecolor(10000, 8000);
for ($x = 0; $x <= 31; $x++) {
    for ($y = 0; $y <= 39; $y++) {
        $val = ($x * 40) + $y + 1;
        $tomerge = imagecreatefromjpeg("$srcpath\\$val.jpg");
        imagecopymerge($im, $tomerge, $y * 250, $x * 250, 0, 0, 250, 250, 100);
        imagedestroy($tomerge);
    }
}
$im2 = @imagecreatetruecolor($heightres, $widthres);
imagecopyresampled($im2, $im, 0, 0, 0, 0, $heightres, $widthres, 10000, 8000);
imagejpeg($im2, $filepath);
imagedestroy($im);
imagedestroy($im2);
header('Content-Type: application/json');
echo 'Carte correctement créée';