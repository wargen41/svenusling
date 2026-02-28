<?php
namespace MovieClient\Services;

class PersonService {
    private $api;
    
    public function __construct(APIClient $api) {
        $this->api = $api;
    }
    
    public function getPersons($filters = []) {
        return $this->api->get('/api/persons', $filters);
    }
    
    public function getPerson($id) {
        return $this->api->get("/api/persons/$id");
    }
    
    public function searchPersons($name, $category = null) {
        $filters = ['search' => $name];
        
        if ($category) $filters['category'] = $category;
        
        return $this->getPersons($filters);
    }
    
    public function getPersonFilmography($personId) {
        $response = $this->getPerson($personId);
        return $response['data']['filmography'] ?? [];
    }
    
    public function getPersonRelations($personId) {
        $response = $this->getPerson($personId);
        return $response['data']['relations'] ?? [];
    }
    
    public function getPersonMedia($personId) {
        return $this->api->get("/api/persons/$personId/media");
    }
}