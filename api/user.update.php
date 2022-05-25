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
			"required" => false
		),
		"phone" => array(
			"type" => "string",
			"required" => false
		),
		"name" => array(
			"type" => "string",
			"required" => false
		),
		"secondname" => array(
			"type" => "string",
			"required" => false
		),
	);

	auth_user();
	validate($_POST, $_PARAMETERS, $_VALIDATION_FAILED);
	//validate_recaptcha($_POST["g-recaptcha-response"], "userSignin");

	//main code

	$pdo = new_pdo(MYSQLI_HOST, MYSQLI_USERNAME, MYSQLI_PASSWORD, MYSQLI_MAIN_DB);
	$query = $state = $data = array();
	
	$query_set = implode(",", array_map(function ($key, $value) {
		return "`" . $key . "` = " . (is_string($value) ? "'" . $value . "'" : $value);
	}, array_keys($_POST), array_values($_POST)));

	$query["update_info"] = $pdo->prepare("UPDATE `users` SET " . $query_set . " WHERE `id` = :id");
	$state["update_info"] = $query["update_info"]->execute(array("id" => $_SESSION["id"]));

	send(response($state["update_info"], false), true);
