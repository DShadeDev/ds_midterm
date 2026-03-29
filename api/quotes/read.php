<?php

// CORS Header
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');

    exit();
  }

  include_once '../../config/Database.php';
  include_once '../../models/Quote.php';

  $database = new Database();
  $db = $database->connect();

  $quote = new Quote($db);

  $author_id = isset($_GET['author_id']) ? $_GET['author_id'] : null;
  $category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;

  $result = $quote->read($author_id, $category_id);

  $quotes = $result->fetchAll(PDO::FETCH_ASSOC);

  if ($quotes) {

    $data = [];

    foreach ($quotes as $row) {
        $data[] = [
            'id' => $row['id'],
            'quote' => $row['quotes'],
            'author' => $row['author'],
            'category' => $row['category']
        ];
    }

    http_response_code(200);
    echo json_encode($data);

} else {
    echo json_encode(array('message' => 'No Quotes Found'));
}