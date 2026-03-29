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
  include_once '../../models/Author.php';
  include_once '../../models/Category.php';

  $database = new Database();
  $db = $database->connect();

  $quote = new Quote($db);
  $author = new Author($db);
  $category = new Category($db);

  $data = json_decode(file_get_contents("php://input"), true);

  $quote->id = $data->id;
  $quote->quote = $data->quote;
  $quote->author_id = $data->author_id;
  $quote->category_id = $data->category_id;

  if($quote->create()) {
    http_response_code(200);
    echo json_encode(array(
      'id' => $quote->id;
      'quote' => $quote->quote;
      'author_id' => $quote->author_id;
      'category_id' => $category_id;
    ));
  } else {
    echo json_encode(array('message' => 'Not Created'));
  }