<?php
	require_once($_SERVER["DOCUMENT_ROOT"] . "/php/config.php");

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
			"required" => true,
		),
		"password" => array(
			"type" => "string",
			"validator" => function ($val) {
				if (preg_match("/^\d{4}$/", $val)) {
					send(response(false, "password_not_key"), true);
				}
				else if (!preg_match("/^[a-zA-Z0-9-_\.]{6,32}$/", $val)) {
					send(response(false, "wrong_password_or_email"), true);
				}
				else {
					return true;
				}
			},
			"required" => true
		),
		/*"g-recaptcha-response" => array(
			"type" => "string",
			"required" => true
		),*/
	);

	validate($_POST, $_PARAMETERS, $_VALIDATION_FAILED);
	//validate_recaptcha($_POST["g-recaptcha-response"], "userSignin");

	//main code

	$_POST["email"] = mb_strtolower($_POST["email"]);

	$pdo = new_pdo(MYSQLI_HOST, MYSQLI_USERNAME, MYSQLI_PASSWORD, MYSQLI_MAIN_DB);
	$query = $state = $data = array();
	
	$query["user"] = $pdo->prepare("SELECT * FROM `users` WHERE `email` = :email AND `password` = :password");
	$state["user"] = $query["user"]->execute(array("email" => $_POST["email"], "password" => $_POST["password"]));
	$data["user"] = process_query($query["user"]);

	if (empty($data["user"])) {
		send(response(false, "wrong_password_or_email"), true);
	}
	else {
		session_start();
		$_SESSION["id"] = $data["user"][0]["id"];

		send(response(true, $data["user"][0]), true);
	}