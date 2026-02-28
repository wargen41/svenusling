<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class MovieAPIClient {
    private $client;
    private $baseUrl;
    private $token;
    
    public function __construct($baseUrl, $token = null) {
        $this->baseUrl = $baseUrl;
        $this->token = $token;
        
        $this->client = new Client([
            'base_uri' => $baseUrl,
            'timeout' => 10
        ]);
    }
    
    /**
     * Get all movies
     */
    public function getMovies($params = []) {
        try {
            $response = $this->client->get('/api/movies', [
                'query' => $params,
                'headers' => $this->getHeaders()
            ]);
            
            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            throw new Exception("API Error: " . $e->getMessage());
        }
    }
    
    /**
     * Get single movie
     */
    public function getMovie($movieId) {
        try {
            $response = $this->client->get("/api/movies/$movieId", [
                'headers' => $this->getHeaders()
            ]);
            
            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            throw new Exception("Movie not found: " . $e->getMessage());
        }
    }
    
    /**
     * Get movie genres
     */
    public function getMovieGenres($movieId) {
        try {
            $response = $this->client->get("/api/movies/$movieId/genres", [
                'headers' => $this->getHeaders()
            ]);
            
            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            throw new Exception("API Error: " . $e->getMessage());
        }
    }
    
    /**
     * Get movie cast
     */
    public function getMovieCast($movieId, $category = 'actor') {
        try {
            $response = $this->client->get("/api/movies/$movieId/persons/$category", [
                'headers' => $this->getHeaders()
            ]);
            
            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            throw new Exception("API Error: " . $e->getMessage());
        }
    }
    
    /**
     * Get movie media
     */
    public function getMovieMedia($movieId) {
        try {
            $response = $this->client->get("/api/movies/$movieId/media", [
                'headers' => $this->getHeaders()
            ]);
            
            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            throw new Exception("API Error: " . $e->getMessage());
        }
    }
    
    /**
     * Get person
     */
    public function getPerson($personId) {
        try {
            $response = $this->client->get("/api/persons/$personId", [
                'headers' => $this->getHeaders()
            ]);
            
            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            throw new Exception("API Error: " . $e->getMessage());
        }
    }
    
    /**
     * Get person filmography
     */
    public function getPersonFilmography($personId) {
        try {
            $response = $this->client->get("/api/persons/$personId", [
                'headers' => $this->getHeaders()
            ]);
            
            $data = json_decode($response->getBody(), true);
            return $data['data']['filmography'] ?? [];
        } catch (RequestException $e) {
            throw new Exception("API Error: " . $e->getMessage());
        }
    }
    
    /**
     * Get genres
     */
    public function getGenres() {
        try {
            $response = $this->client->get('/api/genres', [
                'headers' => $this->getHeaders()
            ]);
            
            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            throw new Exception("API Error: " . $e->getMessage());
        }
    }
    
    /**
     * Get series with seasons and episodes
     */
    public function getSeries($seriesId) {
        try {
            $response = $this->client->get("/api/series/$seriesId", [
                'headers' => $this->getHeaders()
            ]);
            
            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            throw new Exception("API Error: " . $e->getMessage());
        }
    }
    
    /**
     * Get reviews for movie
     */
    public function getMovieReviews($movieId) {
        try {
            $response = $this->client->get("/api/movies/$movieId", [
                'headers' => $this->getHeaders()
            ]);
            
            $data = json_decode($response->getBody(), true);
            return $data['data']['reviews'] ?? [];
        } catch (RequestException $e) {
            throw new Exception("API Error: " . $e->getMessage());
        }
    }
    
    /**
     * Set authentication token
     */
    public function setToken($token) {
        $this->token = $token;
    }
    
    /**
     * Get request headers
     */
    private function getHeaders() {
        $headers = [
            'Content-Type' => 'application/json'
        ];
        
        if ($this->token) {
            $headers['Authorization'] = 'Bearer ' . $this->token;
        }
        
        return $headers;
    }
}

// Usage example
try {
    $api = new MovieAPIClient('http://localhost:8000');
    
    // Get all movies
    $movies = $api->getMovies(['type' => 'film', 'year' => '2023', 'limit' => 10]);
    echo "Total movies: " . $movies['pagination']['total'] . "\n";
    
    // Get single movie with all related data
    $movie = $api->getMovie(1);
    echo "Movie: " . $movie['data']['title'] . "\n";
    echo "Director: " . ($movie['data']['persons']['director'][0]['name'] ?? 'N/A') . "\n";
    echo "Cast: " . count($movie['data']['persons']['actor'] ?? []) . " actors\n";
    
    // Get movie genres
    $genres = $api->getMovieGenres(1);
    echo "Genres: " . implode(', ', array_column($genres['data']['genres'], 'en')) . "\n";
    
    // Get movie cast
    $cast = $api->getMovieCast(1, 'actor');
    echo "Cast members: " . count($cast['data']['persons']) . "\n";
    foreach ($cast['data']['persons'] as $actor) {
        echo "  - " . $actor['name'] . " as " . $actor['role_name'] . "\n";
    }
    
    // Get movie media
    $media = $api->getMovieMedia(1);
    echo "Media files: " . count($media['data']['media']) . "\n";
    
    // Get person
    $person = $api->getPerson(1);
    echo "\nPerson: " . $person['data']['name'] . "\n";
    echo "Birth: " . $person['data']['birth_date'] . "\n";
    echo "Filmography: " . count($person['data']['filmography']) . " titles\n";
    
    // Get genres
    $genres = $api->getGenres();
    echo "\nGenres: " . $genres['count'] . " available\n";
    
    // Get series with all seasons/episodes
    $series = $api->getSeries(1);
    echo "\nSeries: " . $series['data']['title'] . "\n";
    echo "Seasons: " . count($series['data']['seasons']) . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}