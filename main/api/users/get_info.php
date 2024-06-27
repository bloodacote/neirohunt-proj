<?php

	// Загрузка WAFLE
	require $_SERVER["DOCUMENT_ROOT"] . "/lib/wafle-exp2.php";
	require $_SERVER["DOCUMENT_ROOT"] . "/api/main.php";


	// Входные данные
	check_user_input("token", "string", null, 512);


	//- - - - - - - - - - - - - - - - - - - - - -
	// Проверка пользователя
	$user_token = get_input("token");
	$user_id = clientGetId($user_token);
	
	//- - - - - - - - - - - - - - - - - - - - - -
	// Проверка повтора пароля
	$user_info = db_fetch_one($db, "SELECT * FROM users WHERE id = :id", array(
		"id" => $user_id
	));

	//- - - - - - - - - - - - - - - - - - - - - -
	// Вход
	set_output(null, $user_info);
	send_output_data();

?>