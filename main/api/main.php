<?php

	//---------------------------
	// Подключение БД
	$db_host = "127.0.0.1";
	$db_user = "root";
	$db_pass = "";
	$db_name = "neirohunt-db";

	$db = db_connect($db_host, $db_user, $db_pass, $db_name);
	$secret_token_key = "test";


	//---------------------------
	// Подключение редакторов текста
	$tools_dir = $root_dir . "/api/api-tools/";

	function loadTool($tool_name) {
		global $tools_dir;
		require $tools_dir . $tool_name . ".php";
	}

	loadTool("tokens");
	loadTool("login");
	loadTool("pricing");


	//---------------------------
	// Получение информации о сайте
	$main_info = file_get_contents($root_dir . "/main-data.json");
	$main_info = json_decode($main_info, true);

?>