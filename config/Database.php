<?php
    class Database {
        private $host;
        private $port;
        private $dbname;
        private $username;
        private $password;
        private $conn;

        public function __construct() {
          $this->username = getenv('DB_USERNAME');
          $this->password = getenv('DB_PASSWORD');
          $this->dbname = getenv('DB_NAME');
          $this->host = getenv('DB_HOST');
          $this->port = getenv('DB_PORT');
        }

        public function connect() {
          if($this->conn) {
            return $this->conn;
          } else {

            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->dbname};sslmode=require";

            try{
              $this->conn = new PDO($dsn, $this->username, $this->password);
              $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
              return $this->conn;
            } catch(PDOException $e) {
              echo 'Connection Error: ' . $e->getMessage();
            }
          }
        }
    }