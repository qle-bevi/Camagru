<?php

class Autoloader
{
	static function register()
	{
		spl_autoload_register([__class__, "load"]);
	}

	static function load($classname)
	{
		$classname = str_replace("\\", "/", $classname);
		require ROOT."/src/".$classname.".php";
	}
}
