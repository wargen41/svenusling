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
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Public: Get all movies
     */
    public function listMovies(Request $request, Response $response): Response
    {
        try {
            $stmt = $this->db->query('
                SELECT id, title, description, year, director, rating, created_at
                FROM movies
                ORDER BY created_at DESC
            ');
            $movies = $stmt->fetchAll();

            return $this->jsonResponse($response, [
                'success' => true,
                'data' => $movies,
                'count' => count($movies)
            ]);
        } catch (\Exception $e) {
            return $this->jsonResponse(
                $response,
                ['error' => 'Failed to fetch movies'],
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
            return $this->jsonResponse(
                $response,
                ['error' => 'Failed to fetch movie'],
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
            $userId = $request->getAttribute('user_id');
            $userRole = $request->getAttribute('user_role');

            // Check authorization
            if ($userRole !== 'admin') {
                return $this->jsonResponse(
                    $response,
                    ['error' => 'Admin access required'],
                    403
                );
            }

            $data = $request->getParsedBody();

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

            $stmt->execute([
                $data['title'],
                $data['description'] ?? null,
                $data['year'] ?? null,
                $data['director'] ?? null,
                $userId
            ]);

            $movieId = $this->db->lastInsertId();

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
            return $this->jsonResponse(
                $response,
                ['error' => 'Failed to create movie'],
                500
            );
        }
    }

    /**
     * Protected: Update movie (admin only)
     */
    public function updateMovie(Request $request, Response $response, array $args): Response
    {
        try {
            $userId = $request->getAttribute('user_id');
            $userRole = $request->getAttribute('user_role');
            $movieId = $args['id'] ?? null;

            if ($userRole !== 'admin') {
                return $this->jsonResponse(
                    $response,
                    ['error' => 'Admin access required'],
                    403
                );
            }

            $data = $request->getParsedBody();

            // Verify movie exists and user created it or is admin
            $stmt = $this->db->prepare('SELECT id FROM movies WHERE id = ?');
            $stmt->execute([$movieId]);
            if (!$stmt->fetch()) {
                return $this->jsonResponse(
                    $response,
                    ['error' => 'Movie not found'],
                    404
                );
            }

            // Update movie
            $stmt = $this->db->prepare('
                UPDATE movies
                SET title = COALESCE(?, title),
                    description = COALESCE(?, description),
                    year = COALESCE(?, year),
                    director = COALESCE(?, director),
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = ?
            ');

            $stmt->execute([
                $data['title'] ?? null,
                $data['description'] ?? null,
                $data['year'] ?? null,
                $data['director'] ?? null,
                $movieId
            ]);

            return $this->jsonResponse($response, [
                'success' => true,
                'message' => 'Movie updated'
            ]);
        } catch (\Exception $e) {
            return $this->jsonResponse(
                $response,
                ['error' => 'Failed to update movie'],
                500
            );
        }
    }

    /**
     * Protected: Delete movie (admin only)
     */
    public function deleteMovie(Request $request, Response $response, array $args): Response
    {
        try {
            $userRole = $request->getAttribute('user_role');
            $movieId = $args['id'] ?? null;

            if ($userRole !== 'admin') {
                return $this->jsonResponse(
                    $response,
                    ['error' => 'Admin access required'],
                    403
                );
            }

            $stmt = $this->db->prepare('DELETE FROM movies WHERE id = ?');
            $stmt->execute([$movieId]);

            if ($stmt->rowCount() === 0) {
                return $this->jsonResponse(
                    $response,
                    ['error' => 'Movie not found'],
                    404
                );
            }

            return $this->jsonResponse($response, [
                'success' => true,
                'message' => 'Movie deleted'
            ]);
        } catch (\Exception $e) {
            return $this->jsonResponse(
                $response,
                ['error' => 'Failed to delete movie'],
                500
            );
        }
    }

    // Helper methods
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