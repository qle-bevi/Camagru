<?php
$app->mustBeGuest();
$title = "Inscription 42";
if (!isset($_SESSION["42_USER"])) {
    if (!isset($_GET["code"])) {
        $app->Api42->authorize();
    }
    if (($token = $app->Api42->getAccessToken($_GET["code"])) === false
        || ($userData = $app->Api42->getUserData($token)) === false) {
        $app->Flash["alert"] = [
            "type" => "error",
            "message" => "Une erreur est survenue avec l'API de 42!",
            "delay" => 3000
        ];
        $app->redirect("/sign");
    }
    if ($app->Auth->Attempt(["id_42" => $userData->id])) {
        $user = $app->Auth->user()->username;
        $app->Flash["alert"] = [
            "type" => "success",
            "message" => "Salut {$user}!",
            "delay" => 2000
        ];
        $app->redirect("/gallery");
    }

    $_SESSION["42_USER"] = [
        "id_42" => $userData->id,
        "username" => $userData->login,
        "email" => $userData->email,
        "avatar" => $userData->image_url
    ];
}

$username = $_SESSION["42_USER"]["username"];
$errors = [];

if (isset($_POST["username"])) {
    $fields = array_merge($_SESSION["42_USER"], ["username" => trim($_POST["username"])]);
    if ($app->Users->create42($fields)) {
        unset($_SESSION["42_USER"]);
        $app->Auth->attempt(["id_42" => $fields["id_42"]]);
        $app->Flash["alert"] = [
            "type" => "success",
            "message" => "Salut {$fields["username"]}!"
        ];
        $app->redirect('/gallery');
    }
    $errors = $app->Users->validator()->errors();
    if ($username === $fields["username"]) {
        $username = "";
    }
}
require PARTIALS."sign_header.php";
if (!empty($errors)): ?>
    <div class="sign-error">
        <ul>
            <?php foreach ($errors as $k => $v): ?>
                <li><?= $v; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
    Choisissez un pseudo
    <hr>
    <form action="/sign-42" method="post">
        <input class="form-input" type="text" name="username" value="<?= $username ?>" placeholder="Nom d'utilisateur">
        <button type="submit" class="awesome large orange form-btn" name="button">S'enregistrer »</button>
    </form>
<?php
require PARTIALS."sign_footer.php";
