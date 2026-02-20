<?php
namespace App\Controllers;

use App\Config\Database;
use Firebase\JWT\JWT;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Public: Register new user
     */
    public function register(Request $request, Response $response): Response
    {
        try {
            $data = $request->getParsedBody();

            // Validate input
            $errors = $this->validateRegister($data);
            if (!empty($errors)) {
                return $this->jsonResponse(
                    $response,
                    ['error' => 'Validation failed', 'details' => $errors],
                    422
                );
            }

            // Check if user exists
            $stmt = $this->db->prepare('
                SELECT id FROM users WHERE email = ? OR username = ?
            ');
            $stmt->execute([$data['email'], $data['username']]);
            if ($stmt->fetch()) {
                return $this->jsonResponse(
                    $response,
                    ['error' => 'Email or username already exists'],
                    409
                );
            }

            // Hash password
            $passwordHash = password_hash($data['password'], PASSWORD_BCRYPT);

            // Insert user
            $stmt = $this->db->prepare('
                INSERT INTO users (username, email, password_hash, role)
                VALUES (?, ?, ?, ?)
            ');

            $stmt->execute([
                $data['username'],
                $data['email'],
                $passwordHash,
                'user'
            ]);

            $userId = $this->db->lastInsertId();

            // Generate token
            $token = $this->generateToken($userId, 'user');

            return $this->jsonResponse(
                $response,
                [
                    'success' => true,
                    'message' => 'User registered',
                    'token' => $token,
                    'user' => [
                        'id' => (int)$userId,
                        'username' => $data['username'],
                        'email' => $data['email']
                    ]
                ],
                201
            );
        } catch (\Exception $e) {
            return $this->jsonResponse(
                $response,
                ['error' => 'Registration failed'],
                500
            );
        }
    }

    /**
     * Public: Login user
     */
    public function login(Request $request, Response $response): Response
    {
        try {
            $data = $request->getParsedBody();

            if (empty($data['email']) || empty($data['password'])) {
                return $this->jsonResponse(
                    $response,
                    ['error' => 'Email and password are required'],
                    400
                );
            }

            // Get user
            $stmt = $this->db->prepare('
                SELECT id, username, email, password_hash, role
                FROM users WHERE email = ?
            ');
            $stmt->execute([$data['email']]);
            $user = $stmt->fetch();

            if (!$user || !password_verify($data['password'], $user['password_hash'])) {
                return $this->jsonResponse(
                    $response,
                    ['error' => 'Invalid credentials'],
                    401
                );
            }

            // Generate token
            $token = $this->generateToken($user['id'], $user['role']);

            return $this->jsonResponse($response, [
                'success' => true,
                'message' => 'Login successful',
                'token' => $token,
                'user' => [
                    'id' => (int)$user['id'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'role' => $user['role']
                ]
            ]);
        } catch (\Exception $e) {
            return $this->jsonResponse(
                $response,
                ['error' => 'Login failed'],
                500
            );
        }
    }

    private function generateToken($userId, $role)
    {
        $issuedAt = time();
        $payload = [
            'iat' => $issuedAt,
            'exp' => $issuedAt + JWT_EXPIRATION,
            'sub' => $userId,
            'role' => $role
        ];

        return JWT::encode($payload, JWT_SECRET, JWT_ALGORITHM);
    }

    private function validateRegister($data)
    {
        $errors = [];

        if (empty($data['username'])) {
            $errors['username'] = 'Username is required';
        } elseif (strlen($data['username']) < 3) {
            $errors['username'] = 'Username must be at least 3 characters';
        }

        if (empty($data['email'])) {
            $errors['email'] = 'Email is required';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format';
        }

        if (empty($data['password'])) {
            $errors['password'] = 'Password is required';
        } elseif (strlen($data['password']) < 8) {
            $errors['password'] = 'Password must be at least 8 characters';
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