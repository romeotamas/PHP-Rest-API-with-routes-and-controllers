<?php

    require_once("./includes/controllers/Base.php");

    class AuthController extends BaseController {

        public function __construct($validateToken = true) {
            
			parent::__construct($validateToken);
        }


		public function checkToken() {

			$data = [
				'token' => $this->token, 
				"valid" => ($this->user_id ? true : false)
			];

			if($this->user_id) {
				$this->api->returnResponse(SUCCESS_RESPONSE, $data);
			}
			else {
				$this->api->throwError(INVALID_TOKEN, 'Invalid token.');
			}
		}


		public function generateToken() {

            if($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->api->throwError(REQUEST_METHOD_NOT_VALID, 'Request method is not valid.');
            }

			$email = $this->api->validateParameter('email', $this->restData["params"]['email'], STRING);
            $pass = $this->api->validateParameter('pass', $this->restData["params"]['pass'], STRING);
			
			if($email) {
				if(!Utils::isValidEmail($email)) {

					$this->api->throwError(VALIDATE_PARAMETER_DATATYPE, 'Invalid email!');
					return false;
				}
			}

			try {
				$stmt = $this->db->prepare("
					SELECT 
						u.* 
					FROM 
						users AS u 
					WHERE 
						u.email = :email AND 
						u.password = MD5(:pass)

				");
				$stmt->bindParam(":email", $email);
				$stmt->bindParam(":pass", $pass);
				$stmt->execute();
				$user = $stmt->fetch(PDO::FETCH_ASSOC);
				if(!is_array($user)) {
					$this->api->throwError(INVALID_USER_PASS, "Email or Password is incorrect.");
					return false;
				}

				if( (int)$user['inactive'] ) {
					$this->api->throwError(USER_NOT_ACTIVE, "User is inactive. Please contact to admin.");
					return false;
				}

				$payload = [
					'iat' => time(),
					'iss' => 'localhost',
					'exp' => time() + (TOKEN_EXPIRE_MINUTE*60),
					'user_id' => $user['id'],
					'user_name' => $user['name']
				];

				$token = JWT::encode($payload, SECRETE_KEY);
				$data = ['token' => $token];
				$this->api->returnResponse(SUCCESS_RESPONSE, $data);

			} catch (Exception $e) {

				$this->api->throwError(JWT_PROCESSING_ERROR, $e->getMessage());
			}
		}


		public function registerUser() {

			$params = $this->restData["params"];

			$stmt = $this->db->prepare("
				INSERT INTO users SET
					email = :email,
					password = MD5(:pass),
					name = :name,
					created_on = NOW()
			");
			$stmt->bindParam(":email", $params["email"]);
			$stmt->bindParam(":pass", $params["password"]);
			$stmt->bindParam(":name", $params["name"]);
			$stmt->execute();

			$user_id = (int)$this->db->lastInsertId();
			$params["user_id"] = $user_id;

			$data = [
				"user_id" => ($user_id ? $user_id : 0), 
				"userInfo" => json_encode($params)
			];
			if($user_id) {
				$this->api->returnResponse(SUCCESS_RESPONSE, $data);
			}
			else {
				$this->api->throwError(INVALID_TOKEN, 'Invalid user to register.');
			}
		}
    }