<?php
$app->mustBeConfirmed();
header("csrf-token: ".csrf_token());
$img = imagecreatefromstring(file_get_contents("php://input"));
if (!$img) {
	http_response_code(400);
	die("Format incorrect!");
}
$width = imagesx($img);
$height = imagesy($img);
if ($width < 640 || $height < 360) {
	http_response_code(400);
	die("Image trop petite. Minimum 640x360!");
}
header('Content-Type: image/jpeg');
ob_start();
imagejpeg(imagescale($img, 1280, 720));
$data = base64_encode(ob_get_clean());
echo "data:image/jpeg;base64,$data";
