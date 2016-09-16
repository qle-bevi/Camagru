<?php

use App\Application;
use App\UserTable;
use App\Validator;
use App\Api42;
use Core\Database;
use Core\Flash;
use Core\Auth;

define("ROOT", __DIR__."/");
define("STORAGE", ROOT."storage/");
define("PAGES", ROOT."views/pages/");
define("PARTIALS", ROOT."views/partials/");

require ROOT."autoloader.php";
Autoloader::register();

session_start();

return Application::instance()

/*
** DEFINE SERVICES
*/

->singleton('DatabaseNoDb', function() {
	require ROOT."config/database.php";
	$dsn = str_replace("dbname=camagru;", "", $DB_DSN);
	return new Database($dsn, $DB_USER, $DB_PASSWORD);
})

->singleton('Database', function() {
	require ROOT."config/database.php";
	return new Database($DB_DSN, $DB_USER, $DB_PASSWORD);
})

->singleton('Flash', function() {
	return new Flash();
})

->singleton('Users', function($app) {
	return new UserTable($app->Database, $app->Validator);
})

->singleton('Auth', function($app) {
	return new Auth($app->Users);
})

->singleton('Api42', function() {
	require ROOT."config/api42.php";
	return new Api42($API_UID, $API_SECRET);
})

/*
** DEFINE FACTORIES
*/

->factory('Validator', function($app) {
	return new Validator($app->Database);
});
