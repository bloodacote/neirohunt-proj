<?php

	// Загрузка WAFLE
	require $_SERVER["DOCUMENT_ROOT"] . "/lib/wafle-exp2.php";
	require $_SERVER["DOCUMENT_ROOT"] . "/api/main.php";


	// Входные данные
	check_user_input("token", "string", null, 512);
	check_user_input("pass", "string", $main_info["min_pass_length"], 64);
	check_user_input("pass2", "string", $main_info["min_pass_length"], 64);


	//- - - - - - - - - - - - - - - - - - - - - -
	// Проверка пользователя
	$user_token = get_input("token");
	$user_id = clientGetId($user_token);
	
	//- - - - - - - - - - - - - - - - - - - - - -
	// Проверка повтора пароля
	if (get_input("pass") != get_input("pass2")) {
		add_error(422, "pass-dont-match");
	}

	//- - - - - - - - - - - - - - - - - - - - - -
	// Смена пароля
	$hashed_pass = passhash_crypt(get_input("pass"));
	
	db_query($db, "UPDATE users SET pass = :pass WHERE id = :id", array(
		"pass" => $hashed_pass,
		"id" => $user_id
	));

	//- - - - - - - - - - - - - - - - - - - - - -
	// Вход
	set_output(null, true);
	send_output_data();

?>