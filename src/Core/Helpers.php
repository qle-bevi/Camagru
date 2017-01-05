<?php

namespace Core;

class Helpers
{
    public static function randomString($len)
    {
    	$chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $charsLen = strlen($chars);
        $str = "";
        for ($i = 0; $i < $len; $i++) {
            $str .= $chars[rand(0, $charsLen - 1)];
        }
        return $str;
    }

    public static function url($url, $params = [])
    {
        $url = ltrim($url, "/");
        $url = "http://".$_SERVER["HTTP_HOST"]."/".$url;
        if (!empty($params)) {
            $url .= "?".http_build_query($params);
        }
        return $url;
    }
}
