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

  switch ($method) {
    case 'GET':
      if(isset($_GET['id'])){
        include './read_single.php';
      } else {
      include './read.php';
      }
      break;

    case 'POST':
      include './create.php';
      break;

    case 'PUT' :
      include './update.php';
      break;

    case 'DELETE' :
      include './delete.php';
      break;

    default:
      echo "Invalid request.";
      break;

  }