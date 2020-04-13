<?php 

	class DbConnect {

		private $server = DB_HOST;
		private $dbname = DB_NAME;
		private $user = DB_USER;
		private $pass = DB_PASS;

		public function connect() {
			try {

				$conn = new PDO('mysql:host=' .$this->server .';dbname=' . $this->dbname, $this->user, $this->pass);
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				return $conn;

			} catch (\Exception $e) {
				echo "Connect to database error: " . $e->getMessage();
			}
		}

		public function query($query, $params = array()) {

			try {
				$conn = $this->connect();
				$stmt = $conn->prepare($query);
				$stmt->execute($params);
				$data = $stmt->fetchAll();
				return $data;

			} catch(\Exception $e) {
				echo "Query error. Connection does not exist: " . $e->getMessage();
			}
		}
	}