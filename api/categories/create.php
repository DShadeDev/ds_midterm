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
  include_once '../../models/Category.php';

  $database = new Database();
  $db = $database->connect();

  $category = new Category($db);

  $data = json_decode(file_get_contents("php://input"));

  if(!isset($data->category)) {
    echo json_encode(['message' => 'Missing Required Parameters']);
    exit();
  }

  $category->category = $data->category;
  $result = $category->create();
  
  if ($result) {
    echo json_encode([
        'id' => $result,   
        'category' => $category->category
    ]);
  } else {
    http_response_code(500);
    echo json_encode(['message' => 'Category Not Created']);
  }