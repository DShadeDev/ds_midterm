<?php
    class Category {
        private $conn;
        private $table = 'categories';

        public $id;
        public $category;

        public function __construct($db) {
            $this->conn = $db;
        }

        public function read() {
            $query = 'SELECT 
                    id, category
                    FROM '
                    . $this->table;

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        }

        public function read_single(){
            $query = 'SELECT
                    id, category
                    FROM '
                    . $this->table . 
                    ' WHERE 
                    id = ? 
                    LIMIT 1 OFFSET 0';

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                $this->id = $row['id'];
                $this->category = $row['category'];
                return true;
            }
            return false;
        }

        public function create() {
            $query = 'SELECT id FROM ' . $this->table . ' WHERE category = :category LIMIT 1';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':category', $this->category);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                return $row['id']; 
            }

            $query = 'INSERT INTO '
                    . $this->table . 
                    ' (category) 
                    VALUES (:category)';

            $stmt = $this->conn->prepare($query);
            $this->category = htmlspecialchars(strip_tags($this->category));
            $stmt->bindParam(':category', $this->category);
            if($stmt->execute()) {
                return $this->conn->lastInsertId();
            }
            return false;
        }

        public function update() {
            $query = 'UPDATE '
                    . $this->table . 
                    ' SET category = :category
                    WHERE id = :id';

            $stmt = $this->conn->prepare($query);
            $this->category = htmlspecialchars(strip_tags($this->category));
            $this->id = htmlspecialchars(strip_tags($this->id));
            $stmt->bindParam(':category', $this->category);
            $stmt->bindParam(':id', $this->id);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        }

        public function delete() {
            $query = 'DELETE FROM '
                    .$this->table . 
                    ' WHERE id = :id';

            $stmt = $this->conn->prepare($query);
            $this->id = htmlspecialchars(strip_tags($this->id));
            $stmt->bindParam(':id', $this->id);

             if ($stmt->execute()) {
                return true;
            }
            return false;
        }
    }
    ?>