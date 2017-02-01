<?php
$app->mustBeConfirmed();
$errors = [];
if (isset($_POST["change-password"]))
{
	if ($app->Users->changePassword($app->user(), $_POST))
	{
		$app->Flash["alert"] = [
			"type" => "success",
			"message" => "Mot de passe modifie !"
		];
		$app->redirect("/change-password");
	}
	$errors = $app->Users->validator()->errors();
}
require PARTIALS."header.php"
?>
	<div class="page-title"><?= $chOrCr; ?> mot de passe</div>
    <div class="box">
		<?php if (!empty($errors)): ?>
			<div class="sign-error">
				<ul>
					<?php foreach ($errors as $k => $v): ?>
						<li><?= $v; ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>
        <form action="/change-password" method="post">
			<?php csrf_input(); ?>
			<?php if ($user->password !== ""): ?>
            	<input class="form-input" type="password" name="old_password" value="" placeholder="Ancien mot de passe">
			<?php endif; ?>
            <input class="form-input" type="password" name="new_password" value="" placeholder="Nouveau mot de passe">
            <input class="form-input" type="password" name="new_password_confirm" value="" placeholder="Confirmation">
            <button type="submit" class="awesome large orange form-btn" name="change-password"><?= $chOrCr; ?> »</button>
        </form>
    </div>
<?php
require PARTIALS."footer.php";
