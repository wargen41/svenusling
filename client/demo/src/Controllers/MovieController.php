<?php
namespace MovieClient\Controllers;

use MovieClient\Services\MovieService;

class MovieController {
    private $movieService;
    
    public function __construct(MovieService $movieService) {
        $this->movieService = $movieService;
    }
    
    public function listMovies() {
        $filters = [
            'type' => $_GET['type'] ?? null,
            'year' => $_GET['year'] ?? null,
            'rating' => $_GET['rating'] ?? null,
            'limit' => $_GET['limit'] ?? 10,
            'skip' => $_GET['skip'] ?? 0
        ];
        
        // Remove empty filters
        $filters = array_filter($filters);
        
        $result = $this->movieService->getMovies($filters);
        
        return [
            'title' => 'Movies',
            'view' => 'movies/list',
            'movies' => $result['data'] ?? [],
            'pagination' => $result['pagination'] ?? [],
            'error' => !$result['success'] ? $result['error'] : null
        ];
    }
    
    public function detail($id) {
        $result = $this->movieService->getMovie($id);
        
        if (!$result['success']) {
            return [
                'title' => 'Movie Not Found',
                'view' => 'error',
                'error' => $result['error']
            ];
        }
        
        return [
            'title' => $result['data']['title'],
            'view' => 'movies/detail',
            'movie' => $result['data']
        ];
    }
    
    public function search() {
        $search = $_GET['q'] ?? '';
        $type = $_GET['type'] ?? null;
        
        if (empty($search)) {
            return [
                'title' => 'Search Movies',
                'view' => 'movies/search',
                'results' => null,
                'search' => ''
            ];
        }
        
        $result = $this->movieService->searchMovies($search, $type);
        
        return [
            'title' => "Search Results for '$search'",
            'view' => 'movies/search',
            'results' => $result['data'] ?? [],
            'search' => $search,
            'error' => !$result['success'] ? $result['error'] : null
        ];
    }
    
    public function series($id) {
        $result = $this->movieService->getSeries($id);
        
        if (!$result['success']) {
            return [
                'title' => 'Series Not Found',
                'view' => 'error',
                'error' => $result['error']
            ];
        }
        
        return [
            'title' => $result['data']['title'],
            'view' => 'series/detail',
            'series' => $result['data']
        ];
    }
}