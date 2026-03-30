<?php
    class Author {
        private $conn;
        private $table = 'authors';

        public $id;
        public $author;

        public function __construct($db) {
            $this->conn = $db;
        }

        public function read() {
            $query = 'SELECT 
                    id, author
                    FROM '
                    . $this->table;

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        }

        public function read_single(){
            $query = 'SELECT
                    id, author
                    FROM '
                    . $this->table . 
                    ' WHERE 
                    id = ? 
                    LIMIT 1 OFFSET 0';

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if($row) {
                $this->id = $row['id'];
                $this->author = $row['author'];
                return true;
            }
            return false;
            
        }

        public function create() {
            $query = 'INSERT INTO authors (author)
                    VALUES (:author)
                    ON CONFLICT (author) DO UPDATE
                    SET author = EXCLUDED.author
                    RETURNING id';

            $stmt = $this->conn->prepare($query);
            $this->author = htmlspecialchars(strip_tags($this->author));
            $stmt->bindParam(':author', $this->author);
            if($stmt->execute()) {
                return $stmt->fetchColumn();
            }
            return false;
        }

        public function update() {
            $query = 'UPDATE '
                    . $this->table . 
                    ' SET author = :author
                    WHERE id = :id';

            $stmt = $this->conn->prepare($query);
            $this->author = htmlspecialchars(strip_tags($this->author));
            $this->id = htmlspecialchars(strip_tags($this->id));
            $stmt->bindParam(':author', $this->author);
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