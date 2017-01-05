<?php

namespace Core;

use \Exception;

abstract class Entry
{
    public function __get($name)
    {
        $name = "get".ucfirst($name);
        if (method_exists($this, $name)) {
            return $this->$name();
        }
        throw new Exception("Invalid entry property!");
    }
}
