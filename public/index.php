<?php

declare(strict_types=1);

use DI\Container;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

require  __DIR__ . '/../vendor/autoload.php';


// Create Container
$container = new Container();
AppFactory::setContainer($container);

// Set view in Container
$container->set('view', function() {
    return Twig::create(__DIR__ . '/../Templates',
        ['cache' => false]);
});

// Create App
$app = AppFactory::create();

// Add Twig-View Middleware
$app->add(TwigMiddleware::createFromContainer($app));

// Define named route
$app->get('/', function ($request, $response, $args) {
    return $this->get('view')->render($response, 'profile.html', [
        'name' => $args['name']
    ]);
})->setName('profile');

$app->post('/', function (ServerRequestInterface $request, ResponseInterface $response) {
    $requestBody = $request->getParsedBody();
    $responseBody = $response->getBody();
    $reversedText = mb_strrev($requestBody['text']);
    $responseBody->write($reversedText);
    $response->withBody($responseBody);
    return $response;
});


// Run app
$app->run();


function mb_strrev($str, $encoding='UTF-8'){
    return mb_convert_encoding( strrev( mb_convert_encoding($str, 'UTF-16BE', $encoding) ), $encoding, 'UTF-16LE');
}