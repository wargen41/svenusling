<?php
namespace App\Controllers;

use App\Config\Database;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class MovieController
{
    private $db;

    public function __construct()
    {
        try {
            $this->db = Database::getInstance()->getConnection();
        } catch (\Exception $e) {
            error_log('MovieController init error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Public: Get all movies
     */
    public function listMovies(Request $request, Response $response): Response
    {
        try {
            error_log('listMovies called');
            
            $stmt = $this->db->query('
                SELECT id, title, description, year, director, rating, created_at
                FROM movies
                ORDER BY created_at DESC
            ');
            
            if ($stmt === false) {
                throw new \Exception('Query failed');
            }
            
            $movies = $stmt->fetchAll();
            error_log('Fetched ' . count($movies) . ' movies');

            return $this->jsonResponse($response, [
                'success' => true,
                'data' => $movies,
                'count' => count($movies)
            ]);
        } catch (\PDOException $e) {
            error_log('PDO Error in listMovies: ' . $e->getMessage());
            return $this->jsonResponse(
                $response,
                [
                    'error' => 'Database error',
                    'message' => $e->getMessage()
                ],
                500
            );
        } catch (\Exception $e) {
            error_log('Error in listMovies: ' . $e->getMessage());
            error_log('Trace: ' . $e->getTraceAsString());
            return $this->jsonResponse(
                $response,
                [
                    'error' => 'Failed to fetch movies',
                    'message' => $e->getMessage(),
                    'trace' => ENVIRONMENT === 'development' ? $e->getTraceAsString() : null
                ],
                500
            );
        }
    }

    /**
     * Public: Get single movie with reviews
     */
    public function getMovie(Request $request, Response $response, array $args): Response
    {
        try {
            $movieId = $args['id'] ?? null;

            if (!$movieId || !is_numeric($movieId)) {
                return $this->jsonResponse(
                    $response,
                    ['error' => 'Invalid movie ID'],
                    400
                );
            }

            // Get movie
            $stmt = $this->db->prepare('
                SELECT id, title, description, year, director, rating, created_at
                FROM movies
                WHERE id = ?
            ');
            
            if ($stmt === false) {
                throw new \Exception('Query preparation failed');
            }
            
            $stmt->execute([$movieId]);
            $movie = $stmt->fetch();

            if (!$movie) {
                return $this->jsonResponse(
                    $response,
                    ['error' => 'Movie not found'],
                    404
                );
            }

            // Get reviews for this movie
            $stmt = $this->db->prepare('
                SELECT r.id, r.rating, r.comment, r.created_at, u.username
                FROM reviews r
                JOIN users u ON r.user_id = u.id
                WHERE r.movie_id = ?
                ORDER BY r.created_at DESC
            ');
            $stmt->execute([$movieId]);
            $reviews = $stmt->fetchAll();

            $movie['reviews'] = $reviews;

            return $this->jsonResponse($response, [
                'success' => true,
                'data' => $movie
            ]);
        } catch (\Exception $e) {
            error_log('Error in getMovie: ' . $e->getMessage());
            return $this->jsonResponse(
                $response,
                ['error' => 'Failed to fetch movie', 'message' => $e->getMessage()],
                500
            );
        }
    }

    /**
     * Protected: Create movie (admin only)
     */
    public function createMovie(Request $request, Response $response): Response
    {
        try {
            error_log('createMovie() called');
            
            $userId = $request->getAttribute('user_id');
            $userRole = $request->getAttribute('user_role');
            
            error_log('User ID from request: ' . $userId);
            error_log('User role from request: ' . $userRole);
            error_log('User role type: ' . gettype($userRole));

            // Check authorization
            if ($userRole !== 'admin') {
                error_log('✗ User is not admin. Role is: ' . var_export($userRole, true));
                return $this->jsonResponse(
                    $response,
                    ['error' => 'Admin access required'],
                    403
                );
            }
            
            error_log('✓ User is admin, proceeding...');

            $data = $request->getParsedBody();
            error_log('Creating movie with data: ' . json_encode($data));

            // Validate input
            $errors = $this->validateMovieInput($data);
            if (!empty($errors)) {
                return $this->jsonResponse(
                    $response,
                    ['error' => 'Validation failed', 'details' => $errors],
                    422
                );
            }

            // Insert movie
            $stmt = $this->db->prepare('
                INSERT INTO movies (title, description, year, director, created_by)
                VALUES (?, ?, ?, ?, ?)
            ');

            if ($stmt === false) {
                throw new \Exception('Statement preparation failed');
            }

            $stmt->execute([
                $data['title'],
                $data['description'] ?? null,
                $data['year'] ?? null,
                $data['director'] ?? null,
                $userId
            ]);

            $movieId = $this->db->lastInsertId();
            error_log('Movie created with ID: ' . $movieId);

            return $this->jsonResponse(
                $response,
                [
                    'success' => true,
                    'message' => 'Movie created',
                    'id' => (int)$movieId
                ],
                201
            );
        } catch (\Exception $e) {
            error_log('Error in createMovie: ' . $e->getMessage());
            return $this->jsonResponse(
                $response,
                ['error' => 'Failed to create movie', 'message' => $e->getMessage()],
                500
            );
        }
    }

    // ... rest of the methods remain the same, with similar error logging ...

    private function validateMovieInput($data)
    {
        $errors = [];

        if (empty($data['title'])) {
            $errors['title'] = 'Title is required';
        } elseif (strlen($data['title']) < 2) {
            $errors['title'] = 'Title must be at least 2 characters';
        }

        if (!empty($data['year']) && (!is_numeric($data['year']) || $data['year'] < 1800 || $data['year'] > date('Y') + 5)) {
            $errors['year'] = 'Invalid year';
        }

        return $errors;
    }

    private function jsonResponse(Response $response, $data, $status = 200): Response
    {
        $response->getBody()->write(json_encode($data));
        return $response
            ->withStatus($status)
            ->withHeader('Content-Type', 'application/json');
    }
}