<?php

	//---------------------------
	// Получение айди пользователя
	function clientGetId($token) {
		global $db;

		// Проверка токена
		$user_id = checkUserToken($token);
		if ($user_id == -1) {
			add_error(422, "wrong-token");
		}

		// Проверка на существование
		$is_exists = db_fetch_count($db, "SELECT COUNT(*) FROM users WHERE id = :id", array(
			"id" => $user_id
		));

		if ($is_exists == 0) {
			add_error(422, "user-not-exist");
		}

		return $user_id;
	}

	//---------------------------
	// Получение роли пользователя
	function clientGetRole($user_id) {
		global $db;

		// Проверка на существование
		$is_exists = db_fetch_count($db, "SELECT COUNT(*) FROM users WHERE id = :id", array(
			"id" => $user_id
		));

		if ($is_exists == 0) {
			add_error(422, "user-not-exist");
		}

		// Получени инфы о пользователе
		$user_data = db_fetch_all($db, "SELECT role FROM users WHERE id = :id", array(
			"id" => $user_id
		));
		
		return $user_data["role"];
	}

	//---------------------------
	// Проверки прав у роли
	function checkRolePermission($role_name, $role_perm) {
		global $db;

		// Проверка на существование
		$is_exists = db_fetch_count($db, "SELECT COUNT(*) FROM roles WHERE name = :name", array(
			"name" => $role_name
		));

		if ($is_exists == 0) {
			add_error(422, "role-not-exist");
		}

		// Проверка прав
		$role_data = db_fetch_all($db, "SELECT * FROM roles WHERE name = :name", array(
			"name" => $role_name
		));

		if (!array_key_exists($role_perm, $role_data)) {
			add_error(422, "perm-not-exist");
		}
		
		return $role_data[$role_perm];
	}

?>