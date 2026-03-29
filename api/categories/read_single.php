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

  if(!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['message' => 'Invalid or Missing ID']);
    exit();
  }

  $category->id = $_GET['id'];
  if(!$category->read_single()) {
    echo json_encode(['message' => 'category_id Not Found']);
    exit();
  }
  $category_arr = [
    'id' => $category->id,
    'category' => $category->category
  ];
  http_response_code(200);
  echo json_encode($category_arr);