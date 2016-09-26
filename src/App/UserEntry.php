<?php

namespace App;

use Core\Entry;

class UserEntry extends Entry {
    const GRAVATAR_URL = "https://www.gravatar.com/avatar/";

    public function image($width = 60, $height = 60) {
        if (empty($this->avatar))
            return self::GRAVATAR_URL.md5(strtolower($this->email))."?s={$width}";
        return $this->avatar;
    }
}