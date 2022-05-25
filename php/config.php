<?php
	define("MYSQLI_HOST", "localhost");
	define("MYSQLI_USERNAME", "root");
	define("MYSQLI_PASSWORD", "");
	define("MYSQLI_MAIN_DB", "bauka");

	define("IFTTT_KEY", "7eREetsMXm3MV0leDQJI1");
	date_default_timezone_set("Asia/Almaty");

	header("Content-Type: text/html; charset=utf-8");
	header("Access-Control-Allow-Origin: " . $_SERVER["HTTP_ORIGIN"]);
	header("Access-Control-Allow-Credentials: true");
	header("Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type,Authorization");

	require_once($_SERVER["DOCUMENT_ROOT"] . "/php/functions.php");