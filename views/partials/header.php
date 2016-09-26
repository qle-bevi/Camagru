<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no">
	<title>Camagru | <?= $title; ?></title>
	<link href="https://fonts.googleapis.com/css?family=Ubuntu:400,700|Kaushan+Script" rel="stylesheet">
	<link href="/public/css/font-awesome.min.css" rel="stylesheet">
	<link rel="stylesheet" href="/public/css/style.css">
</head>
<body>
	<?php require PARTIALS."alert.php"; ?>
	<div class="site-container">
		<header class="header">
			<a class="site-title">
				Camagru
			</a>
			<div class="site-buttons">
                <a <?= ($title != "Gallerie") ? 'href="/gallery"' : 'class="active"'; ?>>
                    <i class="fa fa-picture-o"></i> <span>Gallerie</span>
                </a>
                <a <?= ($title != "Nouvelle photo") ? 'href="/shoot"' : 'class="active"'; ?>>
                    <i class="fa fa-camera"></i> <span>Nouvelle photo</span>
                </a>
            </div>
            <div id="user-toggler" class="user">
				<img class="avatar" src="<?= $app->user()->image() ?>" height="60px" alt="avatar">
                <?= $app->user()->username; ?>
				<div id="user-actions" class="actions hide">
					<a href="/edit-profile"><i class="fa fa-user" aria-hidden="true"></i> Modifier profil</a>
					<a class="logout" href="/sign-out"><i class="fa fa-sign-out" aria-hidden="true"></i> Déconnexion</a>
				</div>
            </div>
		</header>
        <div class="page-content">
            <div class="page-title"><?= $title ?></div>
