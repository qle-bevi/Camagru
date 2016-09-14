<?php
$app = require "boot.php";

/*
** SIMPLE ROUTING
*/

$file_request = ltrim($_SERVER["REQUEST_URI"], "/");
$file_request = preg_replace("/\?.*$/", "", $file_request);
$ext = pathinfo($file_request, PATHINFO_EXTENSION);
if ($file_request === "") {
	$file_request = "gallery.php";
} else if ($ext === "") {
	$file_request .= ".php";
} else if ($ext === "jpg" && file_exists(STORAGE.$file_request)) {
	die("LOAD IMAGE HERE");
}

if (file_exists(PAGES.$file_request)) {
	return require PAGES.$file_request;
}

/*
** NOT FOUND
*/

http_response_code(404);
require ROOT."views/404.php";
