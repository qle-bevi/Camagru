<?php
$app->mustBeGuest();
if (!isset($_GET["code"]))
	$app->Api42->authorize();

if (($token = $app->Api42->getAccessToken($_GET["code"])) === false
|| ($userData = $app->Api42->getUserData($token)) === false) {
		$app->redirect("/sign");
}

if ($app->Auth->Attempt(["id_42" => $userData->id]))
		$app->redirect("/gallery");
