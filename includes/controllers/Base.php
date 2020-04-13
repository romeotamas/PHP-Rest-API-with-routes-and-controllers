<?php

    require_once("./includes/classes/Utils.php");

    class BaseController {

        public $db = null;
        public $api = null;
        public $restData = [];
        public $uri = null;
        public $token = '';

        public $user_id = null;
        public $user_name = null;
        public $acc_id = null;

        public function __construct($validateToken = true) {

            global $db, $api;

            $this->db = $db;
            $this->api = $api;

            $this->restData =  $this->api->getData();
            
            if( 
                $validateToken &&
                'generatetoken' !== strtolower( $this->restData["method"]) &&
                'registeruser' !== strtolower( $this->restData["method"]) &&
                'generateforgotpasswordhash' !== strtolower( $this->restData["method"])
             ) {

                $this->validateToken();
            }
        }

        public function callController($className) {

            $classInstance = false;

            if(file_exists(CONTROLLERS_PATH . $className . ".php")) {

                require_once(CONTROLLERS_PATH . $className . ".php");
                
                $className = $className . "Controller";
                $classInstance = new $className();
            }

            return $classInstance;
        }


        private function validateToken() {
            try {
                $this->token = $this->getBearerToken();
                $payload = JWT::decode($this->token, SECRETE_KEY, ['HS256']);
        
                $conn = $this->db->prepare("
                    SELECT 
                        * 
                    FROM 
                        users 
                    WHERE 
                        id = :user_id
                ");

                $conn->bindParam(":user_id", $payload->user_id);
                $conn->execute();
                $user = $conn->fetch(PDO::FETCH_ASSOC);

                if(!is_array($user)) {
                    $this->api->returnResponse(INVALID_USER_PASS, "This user is not found in our database.");
                }
        
                if( $user['inactive'] ) {
                    $this->api->returnResponse(USER_NOT_ACTIVE, "This user is inactive. Please contact to admin.");
                }

                $this->user_id = $payload->user_id;
                $this->user_name = $user["name"];
                $this->acc_id = $user["acc_id"];

            } catch (Exception $e) {
                $this->api->throwError(ACCESS_TOKEN_ERRORS, $e->getMessage());
            }
        }


        private function getBearerToken() {

            $headers = $this->getAuthorizationHeader();

            if (!empty($headers)) {
                if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                    return $matches[1];
                }
            }
            $this->api->throwError( ATHORIZATION_HEADER_NOT_FOUND, 'Access Token Not found');
        }
        

        private function getAuthorizationHeader(){

            $headers = null;
            if (isset($_SERVER['Authorization'])) {
                $headers = trim($_SERVER["Authorization"]);
            }
            else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { 
                $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
            } elseif (function_exists('apache_request_headers')) {

                $requestHeaders = apache_request_headers();
                $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
                if (isset($requestHeaders['Authorization'])) {
                    $headers = trim($requestHeaders['Authorization']);
                }
            }
            return $headers;
	      }
    }