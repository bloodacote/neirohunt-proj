<?php

	// Загрузка WAFLE
	require $_SERVER["DOCUMENT_ROOT"] . "/lib/wafle-exp2.php";
	require $_SERVER["DOCUMENT_ROOT"] . "/api/main.php";

	check_user_input("user", "string");

	set_output("msg", get_input("user") . ": Hello world!");

	send_output_data();

?>