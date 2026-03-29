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

  $data = json_decode(file_get_contents("php://input"));

  if(!isset($data->id)) {
    http_response_code(400);
    echo json_encode(['message'=> 'Missing Required Parameters']);
    exit();
  }

  $quote->id = $data->id;
  if(!$quote->read_single()) {
    http_response_code(404);
    echo json_encode(['message' => 'No Quotes Found']);
    exit();
  }
  if($quote->delete()) {
    http_response_code(200);
    echo json_encode([
      'id' => $quote->id
    ]);
  } else {
    http_response_code(500);
    echo json_encode(['message' => 'Quote Not Deleted']);
  }