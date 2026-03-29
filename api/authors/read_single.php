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
  include_once '../../models/Author.php';

  $database = new Database();
  $db = $database->connect();

  $author = new Author($db);

  if(!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['message' => 'Invalid or Missing ID']);
    exit();
  }

  $author->id = $_GET['id'];
  if(!$author->read_single()) {
    http_response_code(404);
    echo json_encode(['message' => 'author_id Not Found']);
    exit();
  }
  $author_arr = [
    'id' => $author->id,
    'category' => $author->author
  ];
  http_response_code(200);
  echo json_encode($author_arr);