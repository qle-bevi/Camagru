<?php

namespace App;

use Core\Database;
use Core\Table;
use Core\SimpleMailer;
use Core\Helpers;
use App\Validator;

class UserTable extends Table
{
    private $validator;
    private $mailer;

    public function __construct(Database $db, Validator $validator, SimpleMailer $mailer)
    {
        $this->validator = $validator;
        $this->mailer = $mailer;
        parent::__construct($db);
    }

    public function validator()
    {
        return $this->validator;
    }

    public function confirmMail($token)
    {
        $user = $this->query("SELECT id, confirmed FROM users WHERE token = ?", [$token], true);
        if (!$user) {
            return -1;
        }
        if (intval($user->confirmed)) {
            return 0;
        }
        $this->query("UPDATE users SET confirmed = '1' WHERE id = '{$user->id}'");
        return 1;
    }

    public function create42($fields)
    {
        if (!$this->validator->validates($fields, [
      "username" => "required|alphanumdash|length:3:15|unique:users",
    ])) {
            return false;
        }
        $this->query("
    INSERT INTO {$this->table}
    (username, email, avatar, id_42, confirmed)
    VALUES (?, ?, ?, ?, ?)", [
        $fields["username"],
        $fields["email"],
        $fields["avatar"],
        $fields["id_42"],
        1
    ]);
        return true;
    }

    public function create($fields)
    {
        if (!$this->validator->validates($fields,
    [
      "username" => "required|alphanumdash|length:3:15|unique:users",
      "password" => "required|length:6:150|confirm",
      "email" => "required|email|unique:users"
    ])) {
            return false;
        }
        $password = hash("whirlpool", $fields["username"].$fields["password"].$fields["username"]);
        $fields["token"] = $fields["username"]."_".Helpers::randomString(50);
        $this->query("
    INSERT INTO {$this->table}
    (username, email, password, token)
    VALUES (?, ?, ?, ?)", [
      $fields["username"],
      $fields["email"],
      $password,
      $fields["token"]]);
        $host = $_SERVER["HTTP_HOST"];
        $this->mailer->send(MAILS."confirmation_mail.php", $fields["email"], "Confirmer votre mail", [
        "username" => $fields["username"],
        "token" => $fields["token"]
    ]);
        return true;
    }
}
