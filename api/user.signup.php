<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . "/php/config.php");

	input_to_post();

	$_PARAMETERS = array(
		"email" => array(
			"type" => "string",
			"validator" => function ($val) {
				if (!filter_var($val, FILTER_VALIDATE_EMAIL)) {
					send(response(false, "input_email_error"), true);
				}
				else {
					return true;
				}
			},
			"required" => true
		),
		"password" => array(
			"type" => "string",
			"validator" => function ($val) {
				if (!preg_match("/^[a-zA-Z0-9-_\.]{6,32}$/", $val)) {
					send(response(false, "input_password_error"), true);
				}
				else {
					return true;
				}
			},
			"required" => true
		),
	);

	validate($_POST, $_PARAMETERS, $_VALIDATION_FAILED);
	//validate_recaptcha($_POST["g-recaptcha-response"], "userSignup");

	$_POST["email"] = mb_strtolower($_POST["email"]);

	//main code

	$pdo = new_pdo(MYSQLI_HOST, MYSQLI_USERNAME, MYSQLI_PASSWORD, MYSQLI_MAIN_DB);
	$query = $state = $data = array();


	/*$query["user"] = $pdo->prepare("SELECT `id` FROM `users` WHERE `email` = :email");
	$query["user"] = $query["user"]->execute(array(
		"email" =>  $_POST["email"]
	));
	$data["user"] = process_query($query["user"]);
	if (!empty($data["user"])) {
		send(response(false, array(
			"id" => $data["user"][0]["id"]
		)), true);
	}*/

	$query["register"] = $pdo->prepare("INSERT INTO `users` (`email`,`password`) VALUES (:email,:password)");
	$query["register"] = $query["register"]->execute(array(
		"email" => $_POST["email"],
		"password" => $_POST["password"],
	));
	$last_insert_user_id = $pdo->lastInsertId();

	send(response(true, array(
		"id" => $last_insert_user_id,
	)), true);