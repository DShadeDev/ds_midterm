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

  if(!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['message' => 'Invalid or Missing ID']);
    exit();
  }

  $quote->id = $_GET['id'];
  if(!$quote->read_single()) {
    http_response_code(404);
    echo json_encode(['message' => 'No Quotes Found']);
    exit();
  }
  $quote_arr = [
    'id' => $quote->id,
    'quote' => $quote->quotes,
    'author' =>$quote-> author,
    'category' => $quote->category
  ];
  http_response_code(200);
  echo json_encode($quote_arr);