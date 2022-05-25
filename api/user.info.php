<?php
	require_once($_SERVER["DOCUMENT_ROOT"] . "/php/config.php");

	auth_user();
	$token = preg_replace("/Token /", "", apache_request_headers()["Authorization"]);

	$pdo = new_pdo(MYSQLI_HOST, MYSQLI_USERNAME, MYSQLI_PASSWORD, MYSQLI_MAIN_DB);
	$query = $state = $data = array();

	$query["info"] = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :id");
	$state["info"] = $query["info"]->execute(array("id" => $token));
	$data["info"] = process_query($query["info"]);

	if ($state["info"] && count($data["info"]) === 1) {
		send(response(true, $data["info"][0]), true);
	}
	else {
		send(response(false, "unknown_error"), true);
	}