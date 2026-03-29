<?php
class Quote {
    private $conn;
    private $table = 'quotes';

    public $id;
    public $quotes;
    public $author;
    public $category;
    public $author_id;
    public $category_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read($author_id = null, $category_id = null) {
        $query = 'SELECT quotes.id, quotes.quotes, categories.category, authors.author
                FROM ' . $this->table . ' 
                LEFT JOIN categories ON quotes.category_id = categories.id 
                LEFT JOIN authors ON quotes.author_id = authors.id';
        $conditions = [];
        $params = [];
        if ($author_id) {
            $conditions[] = 'quotes.author_id = :author_id';
            $params[':author_id'] = $author_id;
        }
        if ($category_id) {
            $conditions[] = 'quotes.category_id = :category_id';
            $params[':category_id'] = $category_id;
        }
        if (!empty($conditions)) {
            $query .= ' WHERE ' . implode(' AND ', $conditions);
        }
        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => &$val) {
            $stmt->bindParam($key, $val);
        }
        $stmt->execute();
        return $stmt;
    }

    public function read_single() {
        $query = 'SELECT quotes.id, quotes.quotes, quotes.category_id, quotes.author_id, categories.category, authors.author 
                FROM ' . $this->table . ' 
                LEFT JOIN categories ON quotes.category_id = categories.id 
                LEFT JOIN authors ON quotes.author_id = authors.id
                WHERE quotes.id = ?
                LIMIT 0,1';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row) {
            $this->id = $row['id'];
            $this->quotes = $row['quotes'];
            $this->category_id = $row['category_id'];
            $this->author_id = $row['author_id'];
            $this->author = $row['author'];
            $this->category = $row['category'];

            return true;
        }
        return false;
    }

    public function create() {
     $query = 'INSERT INTO ' . $this->table . '
              SET quotes = :quotes, author_id = :author_id, category_id = :category_id';

    $stmt = $this->conn->prepare($query);

    $this->quotes = htmlspecialchars(strip_tags($this->quotes));

    $stmt->bindParam(':quotes', $this->quotes);
    $stmt->bindParam(':author_id', $this->author_id);
    $stmt->bindParam(':category_id', $this->category_id);

    if ($stmt->execute()) {
        $this->id = $this->conn->lastInsertId();
        return true;
    }

    return false;
}
    

    public function update() {
        $this->quotes = htmlspecialchars(strip_tags($this->quotes));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $query = 'UPDATE '
        . $this->table . 
        ' SET 
         quotes = :quotes, author_id = :author_id, category_id = :category_id 
         WHERE
         id = :id';

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':quotes', $this->quotes);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':id', $this->id);
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }

    public function delete() {
        $query = 'DELETE FROM '
                . $this->table . 
                ' WHERE 
                id = :id';
    try{
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);
        if($stmt->execute()) {
            return true;
        }
        return false;
    } catch(PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return false;
    }
}

}