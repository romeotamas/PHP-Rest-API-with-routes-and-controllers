<?php 

	class Api extends Rest {


        public function __construct() {
            parent::__construct();
        }


        public function invokeServiceNameMethod($className, $classInstance) {

			$restData = $this->getData();
            try {

                if(
                    !$restData["method"] || 
                    !method_exists($classInstance, $restData["method"])
                ) {
                    $this->throwError(API_DOST_NOT_EXIST, "Missing method or does not exist in controller");
                }
                $rMethod = new reflectionMethod($className, $restData["method"]);
				$rMethod->invoke($classInstance);
				
            } catch (Exception $e) {

                $this->throwError(API_DOST_NOT_EXIST, "Call methods does not work in " . $className . " controller");
            }
        }


		public function run() {

			global $route, $controller, $db;

			$uri = $_SERVER['REQUEST_URI'];
            if(empty($route) || $route !== $uri) {
                $this->throwError(INVALID_ROUTE, 'Invalid route for call API request');
			}
			
			if($this->validateRequest()) {

				$dbInst = new DbConnect();
				$db = $dbInst->connect();
	
				$className = $controller . "Controller";
				$classInst = new $className();

				$this->invokeServiceNameMethod($className, $classInst);
			}
		}
	}
