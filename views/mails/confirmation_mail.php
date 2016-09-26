<?php
use Core\Helpers;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">
<html xmlns="http://www.w3.ord/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bienvenue sur Camagru <?= $username; ?>!</title>
</head>
<body>
    <a href="<?= Helpers::url("/confirm-mail", [
        "token" => $token
    ]); ?>">CLiquez ici pour confirmer votre compte</a>
</body>
</html>