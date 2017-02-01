<?php

namespace Core;

class Validator
{
    private $db;

    protected $errors = [];
    protected $fields = [];

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function errors()
    {
        return $this->errors;
    }

	public function addError($field, $value) {
		$this->errors[$field] = $value;
	}

    public function validates($fields, $rules)
    {
        $this->fields = $fields;
        foreach ($rules as $k => $v) {
            $checks = explode("|", $v);
            if (in_array("required", $checks) && (!isset($fields[$k]) || empty($fields[$k]))) {
                $this->errors[$k] = "Le champ {$k} est requis.";
                continue;
            }
            if (!isset($fields[$k]) || empty($fields[$k])) {
                continue;
            }
            if (($key = array_search("required", $checks)) !== false) {
                unset($checks[$key]);
            }
            $field = [$k, $fields[$k]];
            foreach ($checks as $check) {
                $args = explode(":", $check);
                $methodName = $args[0];
                unset($args[0]);
                $args = array_merge($field, $args);
                if (method_exists($this, $methodName)) {
                    if (!call_user_func_array([$this, $methodName], $args)) {
                        break ;
                    }
                }
            }
        }
        return empty($this->errors);
    }

    public function length($field, $value, $min, $max)
    {
        $len = strlen($value);
        if ($len >= $min && $len <= $max) {
            return true;
        }
        $this->errors[$field] =
      "Le champ {$field} doit avoir une longueur comprise entre {$min} et {$max} caractères.";
        return false;
    }

    public function email($field, $value)
    {
        if (preg_match("/^(\w|-)+@(\w|-)+\.(\w|-|\.)+$/", $value)) {
            return true;
        }
        $this->errors[$field] = "Le champ {$field} n'est pas une adresse email valide.";
        return false;
    }

    public function confirm($field, $value)
    {
        if (isset($this->fields[$field."_confirm"]) && $value == $this->fields[$field."_confirm"]) {
            return true;
        }
        $this->errors[$field] = "Le champ {$field} a mal été confirmé.";
        return false;
    }

    public function unique($field, $value, $table, $ignore = null)
    {
        $item = $this->db->queryWithParameters(
    "SELECT id FROM {$table}
    WHERE {$field} = ?", [$value], null, true);
        if (empty($item)) {
            return true;
        }
        if (isset($ignore) && $ignore == $item->id) {
            return true;
        }
        $this->errors[$field] = "{$value} est déjà été pris pour le champ {$field}.";
        return false;
    }
}
