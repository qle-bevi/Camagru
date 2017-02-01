<?php

	function dd($print)
	{
		die(var_dump($print));
	}

	function randomString($len)
	{
		$chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	    $charsLen = strlen($chars);
	    $str = "";
	    for ($i = 0; $i < $len; $i++) {
	        $str .= $chars[rand(0, $charsLen - 1)];
	    }
	    return $str;
	}

	function csrf_protect()
	{
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$token = $_SESSION["CSRF_TOKEN"] ?? null;
			$value = $_SERVER["HTTP_CSRF_TOKEN"] ?? $_POST["_csrf"] ?? null;
			if (!$token || !$value || !hash_equals($token, $value))
			{
				http_response_code(403);
				die("Forbidden !");
			}
		}
		$_SESSION["CSRF_TOKEN"] = bin2hex(random_bytes(32));
	}

	function csrf_token()
	{
		return $_SESSION["CSRF_TOKEN"];
	}

	function csrf_meta_tag()
	{
		?>
			<meta content="<?= csrf_token(); ?>" name="csrf-token" />
		<?php
	}

	function csrf_input()
	{
		?>
			<input type="hidden" name="_csrf" value="<?= csrf_token(); ?>">
		<?php
	}

	function render_json($data)
	{
		header('Content-Type: application/json');
		echo json_encode($data);
		exit;
	}

	function salt($a, $b)
	{
		return hash("whirlpool", $a.$b.$a);
	}

	function localUrl($url, $params = [])
	{
	    $url = ltrim($url, "/");
	    $url = "http://".$_SERVER["HTTP_HOST"]."/".$url;
	    if (!empty($params)) {
	        $url .= "?".http_build_query($params);
	    }
	    return $url;
	}
