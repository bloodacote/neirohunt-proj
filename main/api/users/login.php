<?php

	// Загрузка WAFLE
	require $_SERVER["DOCUMENT_ROOT"] . "/lib/wafle-exp2.php";
	require $_SERVER["DOCUMENT_ROOT"] . "/api/main.php";


	// Входные данные
	check_user_input("nickname", "string", $main_info["min_login_length"], $main_info["max_login_length"]);
	check_user_input("pass", "string", $main_info["min_pass_length"], 64);


	//- - - - - - - - - - - - - - - - - - - - - -
	// Проверка существующего пользователя
	$find_users = db_fetch_all($db, "SELECT id, pass, is_verified FROM users WHERE nickname = :nick", array(
		"nick" => get_input("nickname")
	));

	// Проверка пользователя
	if (count($find_users) == 0) {
		add_error(422, "wrong-login");
	}
	

	//- - - - - - - - - - - - - - - - - - - - - -
	// Проверка пароля
	$find_users = $find_users[0]; // Получение инфы о пользователе
	
	if (passhash_check(get_input("pass"), $find_users["pass"]) != true) {
		add_error(422, "wrong-pass");
	}


	//- - - - - - - - - - - - - - - - - - - - - -
	// Проверка верификации
	if ($find_users["is_verified"] == false) {
		add_error(422, "not-verified");
	}


	//- - - - - - - - - - - - - - - - - - - - - -
	// Вход
	$new_user_token = createUserToken($find_users["id"]);

	set_output("token", $new_user_token);
	send_output_data();

?>