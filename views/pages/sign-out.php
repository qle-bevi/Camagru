<?php
$app->mustBeLoggued();
$username = $app->user()->username;
$app->Auth->logOut();
$app->Flash["alert"] = [
	"type" => "info",
	"message" => "Aurevoir {$username}!"
];
$app->redirect("/sign-in");
