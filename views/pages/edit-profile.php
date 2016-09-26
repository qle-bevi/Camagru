<?php
$app->mustBeConfirmed();
$title = "Modifier profil";
array_push($scripts, "/public/js/edit-profile.js");
if (!empty($_POST))
{
    var_dump($_POST["old_password"]);
    exit;
}
require PARTIALS."header.php"
?>
        <div class="change-avatar box">
            <h4>Modifer avatar</h4>
            <img src="<?= $app->user()->image(200); ?>" height="200" width="200" alt="Modif avatar">
            <button type="button" class="awesome large blue form-btn" name="button">
                <i class="fa fa-upload"></i> Depuis mon ordinateur
            </button>
            <button type="button" class="awesome large blue form-btn" name="button">
                <i class="fa fa-picture-o"></i> Depuis ma gallerie
            </button>
            <form action="/edit-profile" method="post">
                <input class="form-input" type="text" name="image_url" value="" placeholder="http://">
                <button type="button" class="awesome large blue form-btn" name="button">
                    <i class="fa fa-link"></i> Depuis une URL
                </button>
            </form>
        </div>
        <div class="box">
            <h4>Modifer mot de passe</h4>
            <form action="/edit-profile" method="post">
                <input class="form-input" type="password" name="old_password" value="" placeholder="Ancien mot de passe">
                <input class="form-input" type="password" name="new_password" value="" placeholder="Nouveau mot de passe">
                <input class="form-input" type="password" name="new_password_confirm" value="" placeholder="Confirmation">
                <button type="submit" class="awesome large orange form-btn" name="button">Modifier »</button>
            </form>
        </div>
<?php
require PARTIALS."footer.php";
