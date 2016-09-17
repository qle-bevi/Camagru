<?php

namespace App;

use Core\Container;

class Application extends Container {

	private $user;

	public function user() {
		return $this->user;
	}

	public function mustBeLoggued() {
		if (!$this->Auth->isLogged()) {
			$this->Flash["alert"] = [
				"type" => "info",
				"message" => "Vous devez vous connecter pour accéder à Camagru!"
			];
			$this->redirect("/sign-in");
		}
		$this->user = $this->Auth->user();
	}

	public function mustBeGuest() {
		if ($this->Auth->isLogged())
			$this->redirect("/gallery");
	}

    public function mustBeConfirmed() {
        $this->mustBeLoggued();
        if ($this->user->confirmed)
            return ;
        $this->Auth->logOut();
        $this->Flash["alert"] = [
            "type" => "info",
            "message" => "Ce compte n'a pas été confirmé! <a href=\"/send-confirm-mail\">Réenvoyer mail d'activation</a>"
        ];
        $this->redirect("/sign-in");
    }

	public function redirect($url) {
		header("Location: {$url}");
		exit;
	}
}
