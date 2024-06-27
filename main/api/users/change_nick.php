<?php

	// Загрузка WAFLE
	require $_SERVER["DOCUMENT_ROOT"] . "/lib/wafle-exp2.php";
	require $_SERVER["DOCUMENT_ROOT"] . "/api/main.php";


	// Входные данные
	check_user_input("token", "string", null, 512);
	check_user_input("nick", "string", $main_info["min_login_length"], $main_info["max_login_length"]);


	//- - - - - - - - - - - - - - - - - - - - - -
	// Проверка пользователя
	$user_token = get_input("token");
	$user_id = clientGetId($user_token);

	//- - - - - - - - - - - - - - - - - - - - - -
	// Смена ника с проверкой
	$new_nick = get_input("nick");

	if (preg_match($main_info["login_pattern"], $new_nick) == true) {
		add_error(422, "wrong-nick-format");
	}
	
	db_query($db, "UPDATE users SET nickname = :nick WHERE id = :id", array(
		"nick" => $new_nick,
		"id" => $user_id
	));

	//- - - - - - - - - - - - - - - - - - - - - -
	// Вход
	set_output(null, true);
	send_output_data();

?>