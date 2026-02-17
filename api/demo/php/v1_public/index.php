<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config.php';

use Slim\Factory\AppFactory;
use App\Config\Database;
use App\Routes;

// Initialize database
$db = Database::getInstance();
$db->initializeTables();

// Create Slim app
$app = AppFactory::create();

// Add error handling middleware
$app->addErrorMiddleware(ENVIRONMENT === 'development', true, true);

// Add CORS middleware
$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');
});

// Handle preflight requests
$app->options('/{routes:.+}', function ($request, $response) {
    return $response;
});

// Register routes
Routes::register($app);

// Run app
$app->run();