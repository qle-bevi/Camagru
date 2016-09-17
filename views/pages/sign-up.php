<?php
$app->mustBeGuest();
$title = "Enregistrement";
$errors = [];
$fields = [];
if (!empty($_POST)) {
	if ($app->Users->create($_POST))
	{
		$app->Flash["alert"] = [
			"type" => "success",
			"message" => "Votre compte a été créé toutefois vous devez l'activer via le lien qui viens de vous être envoyer à {$_POST["email"]} !"
		];
		$app->redirect("/sign-in");
	}
	$errors =  $app->Users->validator()->errors();
	$fields = array_diff_key($_POST, $errors);
}
require PARTIALS."sign_header.php";
if(!empty($errors)): ?>
	<div class="sign-error">
		<ul>
			<?php foreach($errors as $k => $v): ?>
				<li><?= $v; ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
<?php endif; ?>
<form action="/sign-up" method="post">
	<input class="form-input" type="text" name="username" value="<?= $fields["username"] ?? ""; ?>" placeholder="Nom d'utilisateur">
	<input class="form-input" type="text" name="email" value="<?= $fields["email"] ?? ""; ?>" placeholder="Email">
	<input class="form-input" type="password" name="password" value="" placeholder="Mot de passe">
	<input class="form-input" type="password" name="password_confirm" value="" placeholder="Confirmation">
	<button type="submit" class="awesome large orange form-btn" name="button">S'enregistrer »</button>
</form>
</div>
<div class="sign-box">
	<strong>Déjà un compte ?</strong> <a href="/sign-in">Se connecter</a>
</div>
<?php
require PARTIALS."sign_footer.php";
