<?php

namespace Core;

class Auth
{
    private $table;
    private $identifier;

    public function __construct(Table $table, $identifier = "loggued_on_user")
    {
        $this->table = $table;
        $this->identifier = $identifier;
    }

    public function getIdentifier()
    {
        return $this->identifier;
    }

    public function attempt($params)
    {
        $fields = "";
        foreach (array_keys($params) as $key) {
            $fields .= $key."=? AND ";
        }
        $fields = rtrim($fields, "AND ");
        $res = $this->table->query(
            "SELECT id FROM {$this->table->tableName()} WHERE {$fields}", array_values($params), true);
        if (empty($res)) {
            return false;
        }
        $_SESSION[$this->identifier] = $res->id;
        return true;
    }

    public function isLoggued()
    {
        return isset($_SESSION[$this->identifier]);
    }

    public function logOut()
    {
        unset($_SESSION[$this->identifier]);
    }

    public function id()
    {
        return $_SESSION[$this->identifier];
    }

    public function user()
    {
        if (!$this->isLoggued()) {
            return null;
        }
        return $this->table->query(
            "SELECT * FROM {$this->table->tableName()} WHERE id=?", [$this->id()], true);
    }
}
