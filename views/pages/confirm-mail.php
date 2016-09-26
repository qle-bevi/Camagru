<?php
$app->mustBeGuest();
if (!isset($_GET["token"]))
    $app->redirect("/sign-in");
switch ($app->Users->confirmMail($_GET["token"]))
{
    case 1:
        $type = "success";
        $message = "Votre compte a été activé! Vous pouvez vous connecter.";
        break;
    case 0:
        $type = "info";
        $message = "Ce compte a déjà été activé!";
        break;
    case -1:
        $type = "error";
        $message = "Token invalide!";
        break;
}

$app->Flash["alert"] = ["type" => $type, "message" => $message];
$app->redirect("/sign-in");