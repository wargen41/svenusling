<?php
namespace MovieClient\Controllers;

use MovieClient\Services\PersonService;

class PersonController {
    private $personService;
    
    public function __construct(PersonService $personService) {
        $this->personService = $personService;
    }
    
    public function listPersons() {
        $filters = [
            'category' => $_GET['category'] ?? null,
            'limit' => $_GET['limit'] ?? 10,
            'skip' => $_GET['skip'] ?? 0
        ];
        
        // Remove empty filters
        $filters = array_filter($filters);
        
        $result = $this->personService->getPersons($filters);
        
        return [
            'title' => 'Persons',
            'view' => 'persons/list',
            'persons' => $result['data'] ?? [],
            'pagination' => $result['pagination'] ?? [],
            'error' => !$result['success'] ? $result['error'] : null
        ];
    }
    
    public function detail($id) {
        $result = $this->personService->getPerson($id);
        
        if (!$result['success']) {
            return [
                'title' => 'Person Not Found',
                'view' => 'error',
                'error' => $result['error']
            ];
        }
        
        return [
            'title' => $result['data']['name'],
            'view' => 'persons/detail',
            'person' => $result['data']
        ];
    }
    
    public function search() {
        $search = $_GET['q'] ?? '';
        $category = $_GET['category'] ?? null;
        
        if (empty($search)) {
            return [
                'title' => 'Search Persons',
                'view' => 'persons/search',
                'results' => null,
                'search' => ''
            ];
        }
        
        $result = $this->personService->searchPersons($search, $category);
        
        return [
            'title' => "Search Results for '$search'",
            'view' => 'persons/search',
            'results' => $result['data'] ?? [],
            'search' => $search,
            'error' => !$result['success'] ? $result['error'] : null
        ];
    }
}