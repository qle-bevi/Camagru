<?php

namespace Core;

class Container
{
    private static $instance;

    public static function instance()
    {
        if (!isset(self::$instance)) {
            $class = get_called_class();
            self::$instance = new $class();
        }
        return self::$instance;
    }

    private $factories    =    [];
    private $singletons =    [];
    private $instances    =    [];

    public function __get($name)
    {
        return $this->get($name);
    }

    public function factory($key, callable $func)
    {
        $this->factories[$key] = $func;
        return $this;
    }

    public function singleton($key, callable $func)
    {
        $this->singletons[$key] = $func;
        return $this;
    }

    public function get($key)
    {
        if (isset($this->factories[$key])) {
            return $this->factories[$key]($this);
        }
        if (isset($this->singletons[$key])) {
            $this->instances[$key] = $this->singletons[$key]($this);
            unset($this->singletons[$key]);
        }
        if (isset($this->instances[$key])) {
            return $this->instances[$key];
        }
        throw new \InvalidArgumentException(sprintf("Key %s does not exists !", $key));
    }
}
