<?php

	// Загрузка WAFLE
	require $_SERVER["DOCUMENT_ROOT"] . "/lib/wafle-exp2.php";
	require $_SERVER["DOCUMENT_ROOT"] . "/api/main.php";


	// Входные данные
	check_user_input("nickname", "string", $main_info["min_login_length"], $main_info["max_login_length"]);
	check_user_input("email", "string", 5, 128);
	check_user_input("pass", "string", $main_info["min_pass_length"], 64);
	check_user_input("pass2", "string", $main_info["min_pass_length"] ,64);


	//- - - - - - - - - - - - - - - - - - - - - -
	// Проверка логина на паттерн
	$user_nick = get_input("nickname");

	if (preg_match($main_info["login_pattern"], $user_nick) == true) {
		add_error(422, "wrong-nick-format");
	}


	//- - - - - - - - - - - - - - - - - - - - - -
	// Проверка почты на паттерн
	$user_email = get_input("email");

	if (preg_match("/[\w\.]*@[a-zA-Z\-\.]*\.[a-zA-Z]*/", $user_email) == false) {
		add_error(422, "wrong-email-format");
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
	// Проверка существующей почты
	$find_users = db_fetch_count($db, "SELECT COUNT(*) FROM users WHERE email = :email", array(
		"email" => $user_email
	));

	if ($find_users > 0) {
		add_error(422, "this-email-was-used");
	}


	//- - - - - - - - - - - - - - - - - - - - - -
	// Проверка повтора пароля
	if (get_input("pass") != get_input("pass2")) {
		add_error(422, "pass-dont-match");
	}


	//- - - - - - - - - - - - - - - - - - - - - -
	// Создание пользователя
	$hashed_pass = passhash_crypt(get_input("pass"));

	db_query($db, "INSERT INTO users (nickname, email, pass, cash) VALUES (:nick, :email, :pass, :cash)", array(
		"nick" => $user_nick,
		"email" => $user_email,
		"pass" => $hashed_pass,
		"cash" => $main_info["start_cash_amount"]
	));

	$new_user_id = db_get_last_id($db);
	$new_user_token = createUserToken($new_user_id);

	$new_user = array(
		"id" => $new_user_id,
		"nick" => $user_nick,
		"email" => $user_email,
		"token" => $new_user_token
	);

	//- - - - - - - - - - - - - - - - - - - - - -
	// Подтверждение по почте
	$rand_symbols = str_split("QWERTYUIOPASDFGHJKLZXCVBNM0123456789");
	$rand_code = "";

	for ($i = 0; $i < 5; $i++) {
		$rand_symb = $rand_symbols[rand(0, count($rand_symbols) - 1)];
		$rand_code .= $rand_symb;
	}

	db_query($db, "INSERT INTO email_suggests (user_id, code) VALUES (:id, :code)", array(
		"id" => $new_user_id,
		"code" => $rand_code
	));

	$site_domen = "http" . str_repeat("s", $_SERVER["HTTPS"]) . "://" . $_SERVER["HTTP_HOST"];
	mail($user_email, "[NEIRO HUNT] Верификация почты", "
		Код для верификации: $rand_code\n
		Для верификации также можно перейти по ссылке: $site_domen/verify\n\n
		Если это были не вы, то отменить можно по ссылке: $site_domen/reverify");


	//- - - - - - - - - - - - - - - - - - - - - -
	// Вывод
	set_output("new_user", $new_user);
	set_output("match", preg_match($main_info["login_pattern"], $user_nick));
	send_output_data();

?>