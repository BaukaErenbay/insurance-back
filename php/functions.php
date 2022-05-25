<?php
	function auth_user() {
		session_start();
		$headers = apache_request_headers();

		if (isset($headers["Authorization"])) {//has token
			$token = preg_replace("/Token /", "", $headers["Authorization"]);
			$pdo = new_pdo(MYSQLI_HOST, MYSQLI_USERNAME, MYSQLI_PASSWORD, MYSQLI_MAIN_DB);
				$query = $stat = $data = array();
				$query["user"] = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :id");
				$state["user"] = $query["user"]->execute(array("id" => $token));
				$data["user"] = process_query($query["user"]);

				if (empty($data["user"])) {
					send(response(false, "auth_error"), true);
				}
				else {
					$_SESSION["id"] = $data["user"][0]["id"];
					//send(response(true, false), true);
				}
		}
		else {
			$_SESSION = array();
			send(response(false, "auth_error"), true);
		}
	};

	$_VALIDATION_FAILED = function ($cause, $error) {
		$err = error($error);
		$err["cause"] = $cause;
		send(response(false, $err), true);
	};

	function input_to_post() {
		$_POST = json_decode(file_get_contents("php://input"), true);
	};

	function is_session_started() {
	    if ( php_sapi_name() !== "cli" ) {
	        if ( version_compare(phpversion(), "5.4.0", ">=") ) {
	            return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
	        } else {
	            return session_id() === "" ? FALSE : TRUE;
	        }
	    }
	    return FALSE;
	};

	function ifttt_webhook($action, $data) {
		return file_get_contents("https://maker.ifttt.com/trigger/" . $action . "/with/key/" . IFTTT_KEY . "/?" . http_build_query($data));
	};

	function mail_send($params) {
		$_PARAMETERS = array(
			"reply_to" => array(
				"type" => "string",
				"required" => true,
				"validator" => function ($val) {
					return (filter_var($val, FILTER_VALIDATE_EMAIL));
				},
			),
			"address" => array(
				"type" => "string",
				"required" => true,
				"validator" => function ($val) {
					return (filter_var($val, FILTER_VALIDATE_EMAIL));
				},
			),
			"subject" => array(
				"type" => "string",
				"required" => true
			),
			"body" => array(
				"type" => "string",
				"required" => true
			),
			"file" => array(
				"type" => "array",
				"required" => false
			)
		);

		validate($params, $_PARAMETERS, $GLOBALS["_VALIDATION_FAILED"]);

		$mail = new PHPMailer;
		$mail->CharSet = "UTF-8";
		$mail->setFrom(PROJECT_EMAIL, PROJECT_NAME);
		$mail->AddReplyTo($params["reply_to"]); // Ваш Email
		$mail->addAddress($params["address"]); //addAddress($_POST["address"], 'Name');
		$mail->isHTML(true);
		$mail->Subject = $params["subject"]; // Заголовок письма
		$mail->Body = $params["body"]; // Текст письма

		if (isset($params["file"]) && !empty($params["file"])) {
			foreach ($params["file"] as $key => $value) {
				$_FILE_PARAMETERS = array(
					"url" => array(
						"type" => "string",
						"required" => true
					),
					"name" => array(
						"type" => "string",
						"required" => true
					),
				);
				validate($value, $_FILE_PARAMETERS, $GLOBALS["_VALIDATION_FAILED"]);
				$mail->addAttachment($value["url"], $value["name"]);
			}
		}

		if ($mail->send()) {
			return response(true, false);
		}
		else {
			return response(false, error("mail_error"));
		}
	};

	function validate($arr, $parameters, $fail = NULL, $success = NULL) {
		if ($fail === NULL) {
			$fail = function() {};
		}
		if ($success === NULL) {
			$success = function() {};
		}

		if (!is_array($arr) || empty($arr) || !is_array($parameters) || empty($parameters)) {
			$fail("array is empty", "validation_error");
		}

		foreach ($parameters as $parameter => $conditions) {
			if (
				!isset($conditions["required"])
			) {
				$fail("required is missing", "validation_error");
				return;
			}

			if (
				$conditions["required"] &&
				!array_key_exists($parameter, $arr)
			) {
				if (isset($conditions["fail"])) {
					$conditions["fail"]($parameter, "parameters_type_error");
				}
				$fail($parameter, "parameters_no_required");
				return;
			}

			if (
				isset($conditions["regexp"]) &&
				($conditions["type"] === "string" || $conditions["type"] === "int") &&
				($conditions["required"] || (!$conditions["required"] && array_key_exists($parameter, $arr))) && // идет на проверку если боязательный или не обязательный и существует
				!preg_match($conditions["regexp"], strval($arr[$parameter]))
			) {
				if (isset($conditions["fail"])) {
					$conditions["fail"]($parameter, "parameters_type_error");
				}
				$fail($parameter, "parameters_type_error");
				return;
			}

			if (
				isset($conditions["validator"]) &&
				($conditions["required"] || (!$conditions["required"] && array_key_exists($parameter, $arr))) && // идет на проверку если боязательный или не обязательный и существует
				!$conditions["validator"]($arr[$parameter])
			) {
				if (isset($conditions["fail"])) {
					$conditions["fail"]($parameter, "parameters_type_error");
				}
				$fail($parameter, "parameters_type_error");
				return;
			}

			if (
				$conditions["type"] === "string" &&
				($conditions["required"] || (!$conditions["required"] && array_key_exists($parameter, $arr))) &&
				!is_string($arr[$parameter])
			) {
				if (isset($conditions["fail"])) {
					$conditions["fail"]($parameter, "parameters_type_error");
				}
				$fail($parameter, "parameters_type_error");
				return;
			}

			if (
				$conditions["type"] === "int" &&
				($conditions["required"] || (!$conditions["required"] && array_key_exists($parameter, $arr))) &&
				!is_numeric($arr[$parameter])
			) {
				if (isset($conditions["fail"])) {
					$conditions["fail"]($parameter, "parameters_type_error");
				}
				$fail($parameter, "parameters_type_error");
				return;
			}

			if (
				$conditions["type"] === "array" &&
				($conditions["required"] || (!$conditions["required"] && array_key_exists($parameter, $arr))) &&
				!is_array($arr[$parameter])
			) {
				if (isset($conditions["fail"])) {
					$conditions["fail"]($parameter, "parameters_type_error");
				}
				$fail($parameter, "parameters_type_error");
				return;
			}
		}
		$success();
	};

	function response($status, $data) {
		return array(
			"status" => $status,
			"data" => $data
		);
	};

	function send($response, $exit) {
		$body = json_encode($response, JSON_UNESCAPED_UNICODE);
		header("Content-length: " . strlen($body));
		echo $body;
		if ($exit) {
			exit();
		}
	};

	function new_pdo($host, $username, $password, $db) {
		$dsn = "mysql:host=$host;dbname=$db;charset=utf8";
		$opt = [
			PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES   => false,
		];
		return new PDO($dsn, $username, $password, $opt);
	};

	function process_query($response) {
		$result = array();
		if ($response) {
			while ($row = $response->fetch(PDO::FETCH_ASSOC)) {
				array_push($result, $row);
			}
			return $result;
		}
		else {
			send(response(false, error("db_error")), true);
		}
	};

	function get_current_date() {
		date_default_timezone_set("Asia/Almaty");
		return date("Y-m-d H:i:s");
	};