<?php
namespace MovieClient\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class APIClient {
    private $client;
    private $baseUrl;
    private $token;
    
    public function __construct($baseUrl, $token = null) {
        $this->baseUrl = $baseUrl;
        $this->token = $token;
        
        $this->client = new Client([
            'base_uri' => $baseUrl,
            'timeout' => 10,
            'verify' => false // Only for development!
        ]);
    }
    
    public function setToken($token) {
        $this->token = $token;
    }
    
    public function getToken() {
        return $this->token;
    }
    
    private function getHeaders() {
        $headers = ['Content-Type' => 'application/json'];
        
        if ($this->token) {
            $headers['Authorization'] = 'Bearer ' . $this->token;
        }
        
        return $headers;
    }
    
    public function get($endpoint, $params = []) {
        try {
            $response = $this->client->get($endpoint, [
                'query' => $params,
                'headers' => $this->getHeaders()
            ]);
            
            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'status' => $e->getResponse()?->getStatusCode()
            ];
        }
    }
    
    public function post($endpoint, $data = []) {
        try {
            $response = $this->client->post($endpoint, [
                'json' => $data,
                'headers' => $this->getHeaders()
            ]);
            
            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'status' => $e->getResponse()?->getStatusCode()
            ];
        }
    }
}