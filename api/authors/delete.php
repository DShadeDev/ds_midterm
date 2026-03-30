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

  $author->id = $data->id;
  $result = $author->delete();

  if ($result === true) {
    echo json_encode([
      'id' => $author->id
    ]);
  } elseif ($result === 'constraint') {
    echo json_encode([
      'message' => "Cannot delete author in use"
    ]);
  } else {
    http_response_code(500);
    echo json_encode([
      'message' => 'Author Not Deleted'
    ]);
  }