<?php
session_start();

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config.php';

use MovieClient\Services\APIClient;
use MovieClient\Services\MovieService;
use MovieClient\Services\PersonService;
use MovieClient\Controllers\MovieController;
use MovieClient\Controllers\PersonController;

// Initialize services
$apiClient = new APIClient(API_BASE_URL);

// Check if token exists in session
if (isset($_SESSION['token'])) {
    $apiClient->setToken($_SESSION['token']);
}

$movieService = new MovieService($apiClient);
$personService = new PersonService($apiClient);

// Route handling
$route = $_GET['page'] ?? 'home';
$id = $_GET['id'] ?? null;

$data = ['title' => 'Movie Database', 'view' => 'home'];

$movieController = new MovieController($movieService);
$personController = new PersonController($personService);

switch ($route) {
    case 'movies':
        $data = $movieController->listMovies();
        break;
    case 'movie':
        if ($id) {
            $data = $movieController->detail($id);
        } else {
            header('Location: ?page=movies');
            exit;
        }
        break;
    case 'search':
        $data = $movieController->search();
        break;
    case 'series':
        if ($id) {
            $data = $movieController->series($id);
        } else {
            header('Location: ?page=movies');
            exit;
        }
        break;
    case 'persons':
        $data = $personController->listPersons();
        break;
    case 'person':
        if ($id) {
            $data = $personController->detail($id);
        } else {
            header('Location: ?page=persons');
            exit;
        }
        break;
    case 'home':
    default:
        // Get featured movies
        $featuredResult = $movieService->getMovies(['limit' => 5]);
        $data = [
            'title' => 'Home',
            'view' => 'home',
            'featured' => $featuredResult['data'] ?? []
        ];
}

// Include layout
include '../src/Views/layout.php';
