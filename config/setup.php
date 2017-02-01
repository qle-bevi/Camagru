#!/usr/bin/php
<?php
$app = require "boot.php";
echo("\e[92m");
echo("     ___                                       ".PHP_EOL);
echo("    / __\__ _ _ __ ___   __ _  __ _ _ __ _   _ ".PHP_EOL);
echo("   / /  / _` | '_ ` _ \ / _` |/ _` | '__| | | |".PHP_EOL);
echo("  / /__| (_| | | | | | | (_| | (_| | |  | |_| |".PHP_EOL);
echo("  \____/\__,_|_| |_| |_|\__,_|\__, |_|   \__,_|".PHP_EOL);
echo("                              |___/            ".PHP_EOL);
echo("\e[39m".PHP_EOL);

/*
**  CREATE DATABASE
*/
echo("Creating database if it doesn't exists...".PHP_EOL);
$app->DatabaseNoDb->query("
  CREATE DATABASE IF NOT EXISTS `camagru`
  CHARACTER SET utf8 COLLATE utf8_general_ci;
  ");
echo("DONE !".PHP_EOL.PHP_EOL);

/*
**  CREATE USER TABLE
*/
echo("Creating users table if it doesn't exists...".PHP_EOL);
$app->Database->query("
  CREATE TABLE IF NOT EXISTS `users`
  (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_42 INT,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255),
    email VARCHAR(255) NOT NULL,
    confirmed BOOLEAN DEFAULT 0,
    token VARCHAR(255),
    created_at DATETIME DEFAULT NOW()
  )
  ");
  echo("DONE !".PHP_EOL);
