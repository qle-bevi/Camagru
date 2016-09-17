<?php

namespace App;

use Core\Validator as CoreValidator;

class Validator extends CoreValidator {
    public function alphanumdash($field, $value) {
    if (preg_match("/^([a-z]|[0-9]|-)+$/i", $value))
      return true;
    $this->errors[$field] = "Le champ {$field} ne peux contenir que des lettre, des chiffres et des tirets.";
    return false;
    }

    public function safePassword($field, $value) {
        return true;
    }
}
