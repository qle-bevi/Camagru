<?php
$app = require "boot.php";

/*
** SIMPLE ROUTING
*/

$file_request = ltrim($_SERVER["REQUEST_URI"], "/");
$file_request = preg_replace("/\?.*$/", "", $file_request);
$ext = pathinfo($file_request, PATHINFO_EXTENSION);

if (strpos($file_request, "public/") === 0) {
    if (file_exists(ROOT.$file_request))
        return false;
} else {
    if ($file_request === "") {
        $file_request = "gallery.php";
    } else if ($ext === "") {
        $ext = ".php";
        $file_request .= $ext;
    } else if ($ext === "jpg" && file_exists(STORAGE.$file_request)) {
        die("LOAD IMAGE HERE");
    }

    if (file_exists(PAGES.$file_request)) {
        foreach ($_POST as $k => $v)
            $_POST[$k] = trim($v);
        $scripts = [];
        return require PAGES.$file_request;
    }
}

/*
** NOT FOUND
*/

http_response_code(404);
require ROOT."views/404.php";
