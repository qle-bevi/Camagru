<?php
$app->mustBeGuest();
$title = "Enregistrement";
$errors = [];
if (!empty($_POST)) {
	if ($app->Users->Create($_POST))
		die("OK");
	$errors =  $app->Users->validator()->errors();
}
require PARTIALS."sign_header.php";
?>
<?php if(!empty($errors)): ?>
	<div class="sign-error">
		<ul>
			<?php foreach($errors as $k => $v): ?>
				<li><?= $v; ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
<?php endif; ?>
<form action="/sign-up" method="post">
	<input class="form-input" type="text" name="username" value="" placeholder="Nom d'utilisateur">
	<input class="form-input" type="text" name="email" value="" placeholder="Email">
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
