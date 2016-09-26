<?php
$app->mustBeLoggued();
$username = $app->user()->username;
$app->Auth->logOut();
$app->Flash["alert"] = [
	"type" => "info",
	"message" => "Aurevoir {$username}!",
	"delay" => 2000
];
$app->redirect("/sign-in");
