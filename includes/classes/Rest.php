<?php 

	class Rest {

		protected $method = "";
		protected $params = [];
		protected $rawData;
		protected $dbConn = null;
		protected $user_id = null;

		public function __construct() {

			$handler = fopen('php://input', 'r');
			$this->rawData = stream_get_contents($handler);
		}


		public function getData() {
			return [
				"params" => $this->params,
				"data" => $this->rawData,
				"method" => $this->method
			];
		}


		public function validateRequest() {

			$requestMethod = $_SERVER["REQUEST_METHOD"];

			if(
				isset($_SERVER['CONTENT_TYPE']) && 
				$_SERVER['CONTENT_TYPE'] !== 'application/json'
			) {
				$this->throwError(REQUEST_CONTENTTYPE_NOT_VALID, 'Request content type is not valid for call request');
			}

			if(
				$requestMethod === "POST" || 
				$requestMethod === "PUT" || 
				$requestMethod === "DELETE"
			) {

				$data = json_decode($this->rawData, true);

				if(!isset($data['name']) || $data['name'] == "") {
					$this->throwError(API_NAME_REQUIRED, "Method is required for call request. Method name is missing or value is empty");
				}
	
				$this->method = $data['name'];
				$this->params = (isset($data['params']) ? $data['params'] : []);

				// if(!is_array($this->params) || empty($this->params)) {
				// 	$this->throwError(API_PARAM_REQUIRED, "Params are required for current call request. Parameters are missing or the values are empty");
				// }
			}
			else {
				// Here can extend some code
				$this->method = "getAll";
				$this->params = [];
			}

			return true;
		}


		public function validateParameter($fieldName, $value, $dataType, $required = true) {

			if($required == true && empty($value) == true) {
				$this->throwError(VALIDATE_PARAMETER_REQUIRED, $fieldName . " parameter is required. Missing or values is empty");
			}

			switch ($dataType) {
				case BOOLEAN:
					if(!is_bool($value)) {
						$this->throwError(VALIDATE_PARAMETER_DATATYPE, "Datatype is not valid for " . $fieldName . '. It should be boolean.');
					}
					break;
					
				case INTEGER:
					if(!is_numeric($value)) {
						$this->throwError(VALIDATE_PARAMETER_DATATYPE, "Datatype is not valid for " . $fieldName . '. It should be numeric.');
					}
					break;

				case STRING:
					if(!is_string($value)) {
						$this->throwError(VALIDATE_PARAMETER_DATATYPE, "Datatype is not valid for " . $fieldName . '. It should be string.');
					}
					break;
				
				default:
					$this->throwError(VALIDATE_PARAMETER_DATATYPE, "Datatype is not valid for " . $fieldName);
					break;
			}

			return $value;

		}


		public function throwError($code, $message) {

			return $this->returnResponse($code, $message, true);
		}


		public function returnResponse($code, $data, $error = false) {

			header("content-type: application/json");

			$message = '';

			if($error) {
				$message = $data;
				$data = [];
			}
			else {
				if(isset($data["message"])) {
					$message = $data["message"];
					unset($data["message"]);
				}	
			}

			$response = [
				'status' => $code, 
				"data" => $data, 
				'message'=>$message,
				'error' => false
			];

			if($error) {
				$response["error"] = true;
			}

			$response = json_encode([
					'response' => $response
				]
			);

			echo $response; 
			exit;
		}
	}