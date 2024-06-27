<?php

	// Загрузка WAFLE
	require $_SERVER["DOCUMENT_ROOT"] . "/lib/wafle-exp2.php";
	require $_SERVER["DOCUMENT_ROOT"] . "/api/main.php";


	// Входные данные
	check_user_input("email", "string", 5, 128);
	check_user_input("code", "string", 5, 5);


	//- - - - - - - - - - - - - - - - - - - - - -
	// Проверка почты на паттерн
	$user_email = get_input("email");

	if (preg_match("/[\w\.]*@[a-zA-Z\-\.]*\.[a-zA-Z]*/", $user_email) == false) {
		add_error(422, "wrong-email-format");
	}


	//- - - - - - - - - - - - - - - - - - - - - -
	// Поиск аккаунта с этой почтой
	$find_users = db_fetch_all($db, "SELECT id FROM users WHERE email = :email", array(
		"email" => $user_email
	));

	if (count($find_users) == 0) {
		add_error(422, "email-not-registered");
	}

	$find_users = $find_users[0];


	//- - - - - - - - - - - - - - - - - - - - - -
	// Поиск реквеста на эту почту
	$find_suggests = db_fetch_all($db, "SELECT * FROM email_suggests WHERE user_id = :id", array(
		"id" => $find_users["id"]
	));

	if (count($find_suggests) == 0) {
		add_error(422, "email-not-registered");
	}

	$find_suggests = $find_suggests[0];


	//- - - - - - - - - - - - - - - - - - - - - -
	// Проверка кода
	if ($find_suggests["code"] != get_input("code")) {
		add_error(422, "wrong-code");
	}


	//- - - - - - - - - - - - - - - - - - - - - -
	// Верификация почты

	db_query($db, "DELETE FROM email_suggests WHERE user_id = :id", array(
		"id" => $find_users["id"]
	));

	db_query($db, "UPDATE users SET is_verified = 1 WHERE id = :id", array(
		"id" => $find_users["id"]
	));


	//- - - - - - - - - - - - - - - - - - - - - -
	// Вывод
	set_output("email", $user_email);
	send_output_data();

?>