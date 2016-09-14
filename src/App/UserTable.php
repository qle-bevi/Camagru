<?php

namespace App;

Use Core\Database;
use Core\Table;
use Core\Helpers;
use App\Validator;

class UserTable extends Table
{
  private $validator;

  public function __construct(Database $db, Validator $validator) {
    $this->validator = $validator;
    parent::__construct($db);
  }

  public function validator() {
    return $this->validator;
  }

  public function confirmMail($token) {
    $user = $this->query("SELECT id, confirmed FROM users WHERE token = ?", [$token], true);
    if (!$user)
      return -1;
    if (intval($user->confirmed))
      return 0;
    $this->query("UPDATE users SET confirmed = '1' WHERE id = '{$user->id}'");
    return 1;
  }

  public function create42($fields) {
    if (!$this->validator->validates($fields, [
      "username" => "required|alphanumdash|length:3:15|unique:users",
    ]))
      return false;
    $this->query("
    INSERT INTO {$this->table}
    (username, email, id_42)
    VALUES (?, ?, ?)", [
      $fields["username"],
      $fields["email"],
      $fields["id_42"]]);
    return true;
  }

  public function create($fields) {
    if (!$this->validator->validates($fields,
    [
      "username" => "required|alphanumdash|length:3:15|unique:users",
      "password" => "required|length:6:150|confirm",
      "email" => "required|email|unique:users"
    ]))
      return false;
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
    mail($fields["email"], "Bienvenue sur Camagru !", 'Cliquez sur ce lien pour vérifier votre compte <a href="http://'.$host.'/sign/confirm/'.$fields["token"].'">Confirmer</a>');
    return true;
  }
}
