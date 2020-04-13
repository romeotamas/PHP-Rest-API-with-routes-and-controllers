<?php

    require_once("./includes/controllers/Base.php");

    class HomeController extends BaseController {

		public function __construct($validateToken = true) {

			parent::__construct($validateToken);
		}


        public function getAll() {

            return $this->api->returnResponse(SUCCESS_RESPONSE, []);
        }

    }