<?php

namespace Core;

class SimpleMailer {
    public function send($template, $dest, $title = "", $vars = [])
    {
        extract($vars);
        ob_start();
        require $template;
        $content = ob_get_clean();
        mail($dest, $title, $content);
    }
}