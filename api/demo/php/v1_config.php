<?php
// Database configuration
define('DB_PATH', __DIR__ . '/movies.db');

// JWT configuration
define('JWT_SECRET', getenv('JWT_SECRET') ?? 'your-secret-key-change-in-production');
define('JWT_ALGORITHM', 'HS256');
define('JWT_EXPIRATION', 3600); // 1 hour

// API configuration
define('API_VERSION', '1.0.0');
define('ENVIRONMENT', getenv('ENVIRONMENT') ?? 'development');

// CORS allowed origins
define('ALLOWED_ORIGINS', [
    'http://localhost:3000',
    'https://yourdomain.com'
]);

return [
    'db_path' => DB_PATH,
    'jwt_secret' => JWT_SECRET,
    'jwt_algorithm' => JWT_ALGORITHM,
    'jwt_expiration' => JWT_EXPIRATION,
];