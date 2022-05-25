<?php
	function error($code = "unknown_error") {
		$data = array(
			"unknown_error" => array(
				"type" => "Ошибка",
				"message" => "Неизвестная ошибка. Попробуйте позже."
			),

			"parameters_type_error" => array(
				"type" => "Ошибка",
				"message" => "Неверный тип одного из переданных параметров. Попробуйте позже."
			),
			"parameters_no_required" => array(
				"type" => "Ошибка",
				"message" => "Один из обязательных параметров не передан."
			),
			"validation_error" => array(
				"type" => "Ошибка",
				"message" => "Ошибка валидации."
			),
			"too_long_subject_name" => array(
				"type" => "Ошибка",
				"message" => "Название предмета не может превышать 100 символов."
			),
			"too_short_subject_name" => array(
				"type" => "Ошибка",
				"message" => "Слишком короткое название предмета."
			),
			"too_big_file" => array(
				"type" => "Ошибка",
				"message" => "Превышен максимальный размер файла (4Mb)."
			),
			"file_extension_error" => array(
				"type" => "Ошибка",
				"message" => "Недопустимое расширение файла. Вы можете загружать файлы с расширением .doc, .docx, .txt или .pdf."
			),


			"query_preparation_error" => array(
				"type" => "Ошибка",
				"message" => "Во время подготовки запроса к базе был найден пустой вопрос или ответ."
			),


			"input_phone_error" => array(
				"type" => "Ошибка",
				"message" => "Вы ввели неверный номер телефона."
			),
			"input_password_error" => array(
				"type" => "Ошибка",
				"message" => "Пароль может содержать от 6-ти до 32-х символов и должен состоять из цифр и букв латинского алфавита."
			),
			"passwords_not_match" => array(
				"type" => "Ошибка",
				"message" => "Пароли не совпадают."
			),
			"input_email_error" => array(
				"type" => "Ошибка",
				"message" => "Вы ввели неверный e-mail адрес."
			),


			"key_not_found" => array(
				"type" => "Ошибка",
				"message" => "Неверный регистрационный ключ."
			),
			"recaptcha_error" => array(
				"type" => "Ошибка",
				"message" => "Вы не прошли проверку reCAPTCHA. Попробуйте позже. Если ошибка повторяется, свяжитесь с <a target='_blank' rel='noopener noreferrer' href='https://wa.me/77002622563?text=" . urldecode("Привет. У меня проблемы с доступом к Тестнику. Не получается пройти проверку reCAPTCHA.") . "'>нами в WhatsApp</a>."
			),
			"db_error" => array(
				"type" => "Ошибка",
				"message" => "Запрос к базе данных вернул ошибку."
			),
			"auth_error" => array(
				"type" => "Ошибка",
				"message" => "Ошибка авторизации. <a rel='noopener noreferrer' href='/signin'>Войдите повторно</a> или <a rel='noopener noreferrer' href='/signup'>зарегистрируйтесь</a>."
			),
			"admin_auth_error" => array(
				"type" => "Ошибка",
				"message" => "Ошибка авторизации. <a rel='noopener noreferrer' href='/admin'>Войдите повторно</a>."
			),
			"email_taken" => array(
				"type" => "Ошибка",
				"message" => "Этот e-mail уже занят. <a rel='noopener noreferrer' href='/signin'>Войдите</a>, если вы уже зарегистрированы."
			),
			"admin_not_found" => array(
				"type" => "Ошибка",
				"message" => "Вы не являетесь администратором."
			),
			"wrong_password_or_email" => array(
				"type" => "Ошибка",
				"message" => "Неверный e-mail или пароль."
			),
			"password_not_key" => array(
				"type" => "Ошибка",
				"message" => "Похоже вы пытаетесь ввести ключ вместо пароля."
			),
			"too_much_forgots" => array(
				"type" => "Ошибка",
				"message" => "Вы можете отправлять запрос на восстановление пароля не более, чем раз в 15 минут."
			),
			"accout_blocked" => array(
				"type" => "Ошибка",
				"message" => "Ваш аккаунт был автоматически заблокирован в связи с частой сменой устройства. Мы расцениваем это, как попытку передачи своего аккаунта кому-то еще. Свяжитесь с <a target='_blank' rel='noopener noreferrer' href='https://wa.me/77002622563?text=" . urldecode("Привет. Мой аккаунт в Тестнике заблокировался. Можно ли его как-то восстановить?") . "'>нами в WhatsApp</a>. Администрация может отказать вам в просьбе восстановить аккаунт."
			),
			"accout_frozen" => array(
				"type" => "Ошибка",
				"message" => "Аккаунт приостановлен до повторной оплаты. Чтобы продолжить пользоваться Тестником свяжитесь с <a target='_blank' rel='noopener noreferrer' href='https://wa.me/77002622563?text=" . urldecode("Привет. Я бы хотел(-а) продлить аккаунт и продолжить пользоваться Тестником.") . "'>нами в WhatsApp</a>."
			),
			"trial_ended" => array(
				"type" => "Ошибка",
				"message" => "Тестовый период окончен. Аккаунт временно заблокирован. Для дальнейшего использования сервиса свяжитесь с <a target='_blank' rel='noopener noreferrer' href='https://wa.me/77002622563?text=" . urldecode("Привет. Мой тестовый период закончился. Можно ли как-то продлить?") . "'>нами в WhatsApp</a>."
			),
			"subject_not_found" => array(
				"type" => "Ошибка",
				"message" => "Предмет не найден. Возможно он был удален или помещен в архив"
			),

			"file_size_error" => array(
				"type" => "Ошибка",
				"message" => "Рзмер файла не может быть больше 4-х мб."
			),
			"file_extension_error" => array(
				"type" => "Ошибка",
				"message" => "Недопустимое расширение файла."
			),
			"file_uploading_error" => array(
				"type" => "Ошибка",
				"message" => "Не удалось добавить файл. Запрос к базе данных вернул ошибку. Свяжитесь с <a target='_blank' rel='noopener noreferrer' href='https://wa.me/77002622563?text=" . urldecode("Привет. У меня не получилось загрузить файл в Тестник. Что можно сделать в этой ситуации?") . "'>нами в WhatsApp</a>."
			),
			"mail_error" => array(
				"type" => "Ошибка",
				"message" => "Не удалось доставить заявку. Свяжитесь с <a target='_blank' rel='noopener noreferrer' href='https://wa.me/77002622563?text=" . urldecode("Привет. У меня не получилось загрузить файл в Тестник. Что можно сделать в этой ситуации?") . "'>нами в WhatsApp</a>."
			),

		);

		return $data[$code];
	}