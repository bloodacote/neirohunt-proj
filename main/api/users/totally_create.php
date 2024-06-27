<?php

	// Загрузка WAFLE
	require $_SERVER["DOCUMENT_ROOT"] . "/lib/wafle-exp2.php";
	require $_SERVER["DOCUMENT_ROOT"] . "/api/main.php";


	// Входные данные
	check_user_input("nickname", "string", $main_info["min_login_length"], $main_info["max_login_length"]);
	check_user_input("pass", "string", $main_info["min_pass_length"], 64);
	check_user_input("pass2", "string", $main_info["min_pass_length"] ,64);


	//- - - - - - - - - - - - - - - - - - - - - -
	// Проверка логина на паттерн
	$user_nick = get_input("nickname");

	if (preg_match($main_info["login_pattern"], $user_nick) == true) {
		add_error(422, "wrong-nick-format");
	}

	//- - - - - - - - - - - - - - - - - - - - - -
	// Проверка существующего пользователя
	$find_users = db_fetch_count($db, "SELECT COUNT(*) FROM users WHERE nickname = :nick", array(
		"nick" => $user_nick
	));

	if ($find_users > 0) {
		add_error(422, "this-user-exists");
	}

	//- - - - - - - - - - - - - - - - - - - - - -
	// Проверка повтора пароля
	if (get_input("pass") != get_input("pass2")) {
		add_error(422, "pass-dont-match");
	}

	//- - - - - - - - - - - - - - - - - - - - - -
	// Создание пользователя
	$hashed_pass = passhash_crypt(get_input("pass"));

	db_query($db, "INSERT INTO users (nickname, pass, cash) VALUES (:nick, :pass, :cash)", array(
		"nick" => $user_nick,
		"pass" => $hashed_pass,
		"cash" => $main_info["start_cash_amount"]
	));

	$new_user_id = db_get_last_id($db);
	$new_user_token = createUserToken($new_user_id);

	$new_user = array(
		"id" => $new_user_id,
		"nick" => $user_nick,
		"token" => $new_user_token
	);

	//- - - - - - - - - - - - - - - - - - - - - -
	// Вывод
	set_output("new_user", $new_user);
	set_output("match", preg_match($main_info["login_pattern"], $user_nick));
	send_output_data();

?>