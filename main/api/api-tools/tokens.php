<?php

	//---------------------------
	// Проверка и создание токена

	function createUserToken($user_id) {
		global $secret_token_key;

		$payload = array(
			"user" => $user_id,
			"date" => date("Y-m-d H:i:s")
		);

		return jwt_gen($payload, $secret_token_key);
	}

	function checkUserToken($token) {
		global $secret_token_key;

		$token_data = jwt_degen($token, $secret_token_key);
		
		if ($token_data["valid"] == false) {
			return -1;
		} else {
			return $token_data["data"]["user"];
		}
	}

?>