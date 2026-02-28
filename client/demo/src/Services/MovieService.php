<?php
namespace MovieClient\Services;

class MovieService {
    private $api;
    
    public function __construct(APIClient $api) {
        $this->api = $api;
    }
    
    public function getMovies($filters = []) {
        return $this->api->get('/api/movies', $filters);
    }
    
    public function getMovie($id) {
        return $this->api->get("/api/movies/$id");
    }
    
    public function searchMovies($title, $type = null, $year = null, $rating = null) {
        $filters = ['search' => $title];
        
        if ($type) $filters['type'] = $type;
        if ($year) $filters['year'] = $year;
        if ($rating) $filters['rating'] = $rating;
        
        return $this->getMovies($filters);
    }
    
    public function getMovieGenres($movieId) {
        return $this->api->get("/api/movies/$movieId/genres");
    }
    
    public function getMovieCast($movieId, $category = null) {
        if ($category) {
            return $this->api->get("/api/movies/$movieId/persons/$category");
        }
        return $this->api->get("/api/movies/$movieId/persons");
    }
    
    public function getMovieMedia($movieId) {
        return $this->api->get("/api/movies/$movieId/media");
    }
    
    public function getSeries($seriesId) {
        return $this->api->get("/api/series/$seriesId");
    }
    
    public function getSeason($seasonId) {
        return $this->api->get("/api/series/season/$seasonId");
    }
}