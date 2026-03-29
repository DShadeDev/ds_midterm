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

  $data = json_decode(file_get_contents("php://input"));


  if (
    !isset($data->quote) ||
    !isset($data->author_id) ||
    !isset($data->category_id)
  ) {
    http_response_code(400);
    echo json_encode(['message' => 'Missing Required Parameters']);
    exit();
  }


  $quote->quotes = $data->quote;
  $quote->author_id = $data->author_id;
  $quote->category_id = $data->category_id;


  $author->id = $data->author_id;
  if (!$author->read_single()) {
    http_response_code(404);
    echo json_encode(['message' => 'author_id Not Found']);
    exit();
  }


  $category->id = $data->category_id;
  if (!$category->read_single()) {
    http_response_code(404);
    echo json_encode(['message' => 'category_id Not Found']);
    exit();
  }


  if ($quote->create()) {
    http_response_code(201);
    echo json_encode([
        'id' => $quote->id,
        'quote' => $quote->quotes,
        'author_id' => $quote->author_id,
        'category_id' => $quote->category_id
    ]);
  } else {
    http_response_code(500);
    echo json_encode(['message' => 'Quote Not Created']);
  }