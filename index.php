<?php
$app = require "boot.php";

/*
** SIMPLE ROUTING
*/

$file_request = ltrim($_SERVER["REQUEST_URI"], "/");
$file_request = preg_replace("/\?.*$/", "", $file_request);
$ext = pathinfo($file_request, PATHINFO_EXTENSION);

if ($_SERVER["REQUEST_URI"] !== "/" && file_exists(PUBLIK.$file_request))
    return false;
    
if ($file_request === "") {
    $file_request = "gallery.php";
} else if ($ext === "") {
    $ext = ".php";
    $file_request .= $ext;
}

if (file_exists(PAGES.$file_request)) {
    foreach ($_POST as $k => $v)
        $_POST[$k] = trim($v);
    $scripts = [];
    return require PAGES.$file_request;
}

/*
** NOT FOUND
*/

http_response_code(404);
require ROOT."views/404.php";
