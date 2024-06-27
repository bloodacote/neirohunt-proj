<?php


	//-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
	// Загрузка WAFLE
	require $_SERVER["DOCUMENT_ROOT"] . "/lib/wafle-exp2.php";
	require $_SERVER["DOCUMENT_ROOT"] . "/api/main.php";

	// Получение текста
	check_user_input("text", "string", $main_info["min_text_length"], $main_info["max_text_length"] * 2);
	$text = get_input("text");
	$modified_text = htmlspecialchars($text);


	//-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
	// Подготовка
	$text_words_count = count(explode(" ", $text)); // Длина текста в словах
	$neiro_score = 1; // Количество нейронщины
	$strange_moments = 0; // Количество странных моментов
	$neiro_percent = 0; // Процент нейронщины в тексте
	//$length_weight = 0.02; // Весы на размер текста

	// Весы
	$global_weight = 0.15; // Глобальные весы
	$repeat_weight = 0; // Весы повторения слов
	$suspicion_weight = 1.5; // Весы подозритеьности слов
	$tautology_weight = 0; // Весы тавтологии

	// Маркировка текста
	$mark_start_tag = "<b>"; // Начальный тег
	$mark_end_tag = "</b>"; // Конечный тег


	// Получение списка подозрительных слов
	$neiro_words = db_fetch_all($db, "SELECT word, regex, suspicion FROM gluewords ORDER BY suspicion DESC");


	// Создание регулярки для анализа текста и подсчёт очков
	$check_regex = array();
	$suspicion_list = array(); // Список очков за каждое слово


	// Цикл
	for ($i = 0; $i < count($neiro_words); $i++) {
		$word_name = $neiro_words[$i]["word"];
		$word_regex = $neiro_words[$i]["regex"];
		$word_score = $neiro_words[$i]["suspicion"];

		$suspicion_list[$word_name] = $word_score;
		array_push($check_regex, "(?<$word_name>$word_regex)");
	}

	// Создана регулярка
	$check_regex = implode("|", $check_regex);
	$check_regex = "/$check_regex/ui";
	set_output("regex", $check_regex);


	//-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
	// Поиск в тексте
	preg_match_all($check_regex, $text, $neiro_match_list, PREG_UNMATCHED_AS_NULL);

	// Окончательный анализ
	// Количество совпадений
	$strange_moments = count($neiro_match_list[0]);

	// Подсчёт нейронщины
	foreach ($neiro_match_list as $key => $match_list) {

		if (!is_numeric($key)) {

			// Фильтр пустых значений
			$match_list = array_filter($match_list, function($val) {
				return !is_null($val);
			});

			$word_score = $suspicion_list[$key];
			$match_count = count($match_list);

			// Бонус к тавтологии
			if ($match_count > 1) {
				$word_score *= (1 + $tautology_weight * $match_count);
			}

			//echo "\nWORD: $key -- SCORE: $word_score -- MATCHES: $match_count\n";

			for ($i = 0; $i < $match_count; $i++) {
				//$neiro_percent = $neiro_score / $text_words_count;
				$neiro_score += $word_score * $suspicion_weight;
				$neiro_score *= 1 + ($repeat_weight / ($text_words_count / 150));
			}


			// Покраска текста
			//echo "\nMATCH LIST: \n";
			$match_list = array_unique($match_list);
			//print_r($match_list);
			foreach ($match_list as $word) {
				//$word = $match_list[$i];
				$modified_text = str_replace($word, $mark_start_tag . $word . $mark_end_tag, $modified_text);
			}
		}
	}


	//-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
	// Подведение результатов
	$neiro_score -= 1;
	$neiro_percent = $neiro_score / $text_words_count * 100;
	//$neiro_percent = 100 - (100 - $neiro_percent) / $strange_moments;
	$neiro_percent *= $global_weight;

	$modified_text = nl2br($modified_text);

	// Возвращение
	set_output("text_words_count", $text_words_count);
	set_output("neiro_score", $neiro_score);
	set_output("gpt_match_count", $strange_moments);
	set_output("neiro_percent", $neiro_percent);

	set_output("matches", $neiro_match_list);
	set_output("edited_text", $modified_text);

	send_output_data();


?>