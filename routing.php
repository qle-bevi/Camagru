<?php
if (strpos($_SERVER["REQUEST_URI"], "/static") === 0)
	return false;
require "index.php";
return true;
