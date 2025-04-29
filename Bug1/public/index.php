<?php

use Slim\Factory\AppFactory;
use Slim\Views\PhpRenderer;

require __DIR__ . '/../vendor/autoload.php';

// Create App
$app = AppFactory::create();

// Default route
$app->get('/', function ($request, $response) {
  $renderer = new PhpRenderer('../templates');
  
  $viewData = [
      'name' => 'John',
  ];
  
  return $renderer->render($response, 'template.php', $viewData);
})->setName('home');

// Form submission
$app->post('/submit', function ($request, $response) {
  // Get all POST parameters
  $data = (array)$request->getParsedBody();

  if ($data['name'] == "" || $data['email'] == "" || $data['message'] == "") {
    echo "All fields required.";
    return;
  }

  if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email format";
    return;
  }

  $response->getBody()->write("Message sent! Thank you, $data['name'].");
  return $response;
})->setName('submission');

$app->run();