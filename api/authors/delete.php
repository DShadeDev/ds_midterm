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

  $data = json_decode(file_get_contents("php://input"));

  if(!isset($data->id)) {
    http_response_code(400);
    echo json_encode(['message'=> 'Missing Required Parameters']);
    exit();
  }

  $author->id = $data->id;
  if(!$author->read_single()) {
    http_response_code(404);
    echo json_encode(['message' => 'author_id not Found']);
    exit();
  }
  if($author->delete()) {
    http_response_code(200);
    echo json_encode([
      'id' => $author->id
    ]);
  } else {
    http_response_code(500);
    echo json_encode(['message' => 'Author Not Deleted']);
  }