<?php

namespace App;

use Core\Database;
use Core\Table;
use Core\SimpleMailer;
use App\Validator;

class UserTable extends Table
{
    private $validator;
    private $mailer;

	protected $fillables = [
		"username",
		"password",
		"email",
		"confirmed",
		"token"
	];

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
        $this->update(["confirmed" => "1"], ["id" => $user->id]);
        return 1;
    }

    public function create42($fields)
    {
		$rules = [
			"username" => "required|alphanumdash|length:3:15|unique:users"
    	];
        if (!$this->validator->validates($fields, $rules)) {
            return false;
        }
        $this->insert($fields);
        return true;
    }

	public function changePassword($user, $fields)
	{
		if ($user->password !== "") {
			$hop = salt($user->username, $fields["old_password"]);
			if ($user->password !== $hop) {
				$this->validator->addError("old_password", "Ancien mot de passe incorrect.");
				return false;
			}
		}
		$rules = ["new_password" => "required|length:6:150|confirm"];
		if (!$this->validator->validates($fields, $rules))
			return false;
		$hnp = salt($user->username, $fields["new_password"]);
		$this->update(["password" => $hnp], ["id" => $user->id]);
		return true;
	}

    public function create($fields)
    {
		$rules = [
          "username" => "required|alphanumdash|length:3:15|unique:users",
          "password" => "required|length:6:150|confirm",
          "email" => "required|email|unique:users"
	  	];
        if (!$this->validator->validates($fields, $rules)) {
            return false;
        }
        $fields["password"] = salt($fields["username"], $fields["password"]);
        $fields["token"] = $fields["username"]."_".randomString(50);
        unset($fields["password_confirm"]);
        $this->insert($fields);
        $this->mailer->send(MAILS."confirmation_mail.php", $fields["email"], "Confirmer votre mail", $fields);
        return true;
    }
}
