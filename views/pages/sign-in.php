<?php
$app->mustBeGuest();
$title = "Connexion";
$error = false;
if (isset($_POST["username"]) && isset($_POST["password"])) {
	$user = trim($_POST["username"]);
	$pwd = hash("whirlpool", $user.$_POST["password"].$user);
	$credentials = [
		"username" => $user,
		"password" => $pwd
	];
	if ($app->Auth->attempt($credentials)) {
		$app->Flash["alert"] = [
			"type" => "success",
			"message" => "Salut {$user}!",
			"delay" => 2000
		];
		$app->redirect("/gallery");
	}
	$error = "Identifiants incorrects !";
}
require PARTIALS."sign_header.php";
if ($error): ?>
	<div class="sign-error">
		<?= $error; ?>
	</div>
<?php endif; ?>
<form action="/sign-in" method="post">
	<input class="form-input" type="text" name="username" value="" placeholder="Nom d'utilisateur">
	<input class="form-input" type="password" name="password" value="" placeholder="Mot de passe">
	<button type="submit" class="awesome large orange form-btn" name="button">Se connecter »</button>
	<a class="awesome medium blue form-btn" href="/sign-42">Connexion via 42 »</a>
</form>
</div>
<div class="sign-box">
	<strong>Pas encore de compte ?</strong> <a href="/sign-up">S'enregister</a>
</div>
<?php
require PARTIALS."sign_footer.php";
