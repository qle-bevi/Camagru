<?php

namespace App;

use Core\Container;

class Application extends Container {

	private $user;

	public function user() {
		return $this->user;
	}

	public function mustBeLoggued() {
		if (!$this->Auth->isLogged())
			$this->redirect("/sign-in");
		$this->user = $this->Auth->user();
	}

	public function mustBeGuest() {
		if ($this->Auth->isLogged())
			$this->redirect("/gallery");
	}

	public function redirect($url) {
		header("Location: {$url}");
		exit;
	}
}
