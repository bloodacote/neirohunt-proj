<?php

	//---------------------------
	// Функция на подсчёт цены
	function getTextPrice($text, $start_price = 0, $free_limit = 100, $text_price_weight = 0.05) {
		$text_words_count = count(explode(" ", $text)); // Длина текста в словах
		$text_price = $start_price;

		// При достижении лимита цена растёт
		if ($text_words_count > $free_limit) {
			$text_price += ($text_words_count - $free_limit) * $text_price_weight;
		}

		$text_price = round($text_price);
		return $text_price;
	}

?>