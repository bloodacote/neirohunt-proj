<?php

	//-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
	// Загрузка WAFLE
	require $_SERVER["DOCUMENT_ROOT"] . "/lib/wafle-exp2.php";
	require $_SERVER["DOCUMENT_ROOT"] . "/api/main.php";

	// Получение текста
	check_user_input("text", "string", 18, $main_info["max_text_length"]);
	$text = get_input("text");


	//-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
	// Подсчёт
	$text_words_count = count(explode(" ", $text)); // Длина текста в словах
	$text_price = getTextPrice($text, 0, 100, 0.05); // Подсчёт цены

	//-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
	// Вывод
	set_output("text_length", $text_words_count);
	set_output("text_price", $text_price);
	send_output_data();

?>