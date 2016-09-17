<?php

namespace Core;

class Flash implements \ArrayAccess {
  public function __construct() {
      if (!isset($_SESSION["FLASH"]))
        $_SESSION["FLASH"] = [];
  }

  public function offsetExists($key) {
      return isset($_SESSION["FLASH"][$key]);
  }

  public function offsetGet($key) {
    if (!isset($_SESSION["FLASH"][$key]))
      return null;
    $value = $_SESSION["FLASH"][$key];
    unset($_SESSION["FLASH"][$key]);
    return $value;
  }

  public function offsetSet($key , $value) {
    $_SESSION["FLASH"][$key] = $value;
  }

  public function offsetUnset($key) {
    unset($_SESSION["FLASH"][$key]);
  }
}
