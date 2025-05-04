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
      'name' => 'Johdddn',
  ];
  
  return $renderer->render($response, 'template.php', $viewData);
})->setName('home');

// Form submission
$app->post('/submit', function ($request, $response) {

  // Get all POST parameters
  $data = (array)$request->getParsedBody();

  // Return a 400 status code and a message if data any field is null
  if ($data['name'] == "" || $data['email'] == "" || $data['message'] == "") {
    $response->getBody()->write("All fields required.");
    return $response->withStatus(400)->withHeader('Content-Type', 'text/plain');
  }

  // Return a 400 status code and a message if email is invalid.
  if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    $response->getBody()->write("Invalid Email Format.");
    return $response->withStatus(400)->withHeader('Content-Type', 'text/plain');
  }

  // Write the response, correctly parsing the name field from the request body
  $response->getBody()->write("Message sent! Thank you, {$data['name']}.");
  return $response;
  
})->setName('submission');

$app->run();

?>