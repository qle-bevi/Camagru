<?php
$app->mustBeLoggued();
require PARTIALS."sign_header.php"
?>
<a href="/sign-out">Déconnecter <?= $app->user()->username ?></a>
<?php
require PARTIALS."sign_footer.php";
