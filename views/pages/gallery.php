<?php
$app->mustBeLoggued();
?>

<a href="/sign-out">Déconnecter <?= $app->user()->username ?></a>
