<?php

    require_once("./includes/controllers/Base.php");

    class UserController extends BaseController {
        
		public function __construct($validateToken = true) {
		
			parent::__construct($validateToken);
		}


        public function getAll() {

            return $this->api->returnResponse(SUCCESS_RESPONSE, []);
        }
    }