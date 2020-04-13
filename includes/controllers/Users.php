<?php

    require_once("./includes/controllers/Base.php");

    class UsersController extends BaseController {

		public function __construct($validateToken = true) {

            parent::__construct($validateToken);
        }
        

        public function getAll() {

            return $this->getAllAccountUsers();
        }


		public function getAllAccountUsers() {

            $restData = $this->restData;
            $params = $restData["params"];

            $searchTextSql = '';
            $searchText = (isset($params["searchText"]) && strlen($params["searchText"]) > 0) ? strtolower(trim($params["searchText"])) : '';
            if($searchText) {
                $searchTextSql = "
                    AND LOWER(usr.name) LIKE '%" . $searchText . "%'
                ";
            }

			$conn = $this->db->prepare("
                SELECT 
                    usr.*,
                    usr.id AS value
                FROM 
                    users AS usr
                WHERE
                    usr.acc_id = '" . $this->acc_id . "'
                    $searchTextSql
            ");

			$conn->execute();
            $rows = $conn->fetchAll(PDO::FETCH_ASSOC);
            
            if(!$rows) {
                return $this->api->throwError(SUCCESS_RESPONSE, []);
            }

			return $this->api->returnResponse(SUCCESS_RESPONSE, $rows);
		}
    }