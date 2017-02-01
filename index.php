<?php

define("ROOT", __DIR__."/");
define("STORAGE", ROOT."storage/");
define("PAGES", ROOT."views/pages/");
define("PARTIALS", ROOT."views/partials/");
define("PUBLIK", ROOT."public/");
define("MAILS", ROOT."views/mails/");

/*
** SIMPLE ROUTING
*/

$file_request = ltrim($_SERVER["REQUEST_URI"], "/");
$file_request = preg_replace("/\?.*$/", "", $file_request);
$ext = pathinfo($file_request, PATHINFO_EXTENSION);

if ($_SERVER["REQUEST_URI"] !== "/" && file_exists(PUBLIK.$file_request))
    return false;

if ($file_request === "") {
    $file_request = "sign-in.php";
} else if ($ext === "") {
    $ext = ".php";
    $file_request .= $ext;
}

if (file_exists(PAGES.$file_request)) {
	$app = require "boot.php";
	csrf_protect();
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
