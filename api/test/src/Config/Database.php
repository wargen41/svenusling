<?php
namespace App\Config;

use PDO;
use PDOException;

class Database
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        error_log('Database::__construct() called');
        
        try {
            // Get the directory where the database should be stored
            $dbDir = dirname(DB_PATH);
            error_log('Database directory: ' . $dbDir);
            
            // Create directory if it doesn't exist
            if (!is_dir($dbDir)) {
                error_log('Database directory does not exist, creating...');
                if (!mkdir($dbDir, 0755, true)) {
                    throw new \Exception("Could not create database directory: $dbDir");
                }
                error_log("✓ Created database directory: $dbDir");
            } else {
                error_log('Database directory exists');
            }
            
            // Check if directory is writable
            if (!is_writable($dbDir)) {
                throw new \Exception("Database directory is not writable: $dbDir");
            }
            
            error_log('Database directory is writable');
            error_log('Connecting to database: ' . DB_PATH);
            
            // THIS IS THE KEY LINE - Create PDO connection
            // This will create the file if it doesn't exist
            $this->connection = new PDO(
                'sqlite:' . DB_PATH,
                null,
                null,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
            
            error_log('✓ PDO connection created');
            
            // Verify file was created
            if (file_exists(DB_PATH)) {
                error_log('✓ Database file confirmed to exist: ' . DB_PATH);
                $size = filesize(DB_PATH);
                error_log('  File size: ' . $size . ' bytes');
            } else {
                error_log('✗ Database file NOT found after PDO creation');
            }
            
            // Enable foreign keys
            $this->connection->exec('PRAGMA foreign_keys = ON');
            error_log('✓ Foreign keys enabled');
            
        } catch (PDOException $e) {
            error_log('✗ PDOException in Database constructor: ' . $e->getMessage());
            error_log('Code: ' . $e->getCode());
            throw new \Exception('Database connection failed: ' . $e->getMessage(), 0, $e);
        } catch (\Exception $e) {
            error_log('✗ Exception in Database constructor: ' . $e->getMessage());
            throw $e;
        }
    }

    public static function getInstance()
    {
        error_log('Database::getInstance() called');
        
        if (self::$instance === null) {
            error_log('Creating new Database instance...');
            self::$instance = new self();
            error_log('✓ Database instance created');
        } else {
            error_log('Returning existing Database instance');
        }
        
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function initializeTables()
    {
        error_log('Database::initializeTables() called');
        
        try {
            // Check if tables already exist
            error_log('Checking for existing tables...');
            $tables = $this->connection->query("SELECT name FROM sqlite_master WHERE type='table'")->fetchAll();
            $existingTables = array_column($tables, 'name');
            error_log('Found tables: ' . json_encode($existingTables));
            
            if (in_array('movies', $existingTables)) {
                error_log('✓ Tables already exist, skipping creation');
                return;
            }
            
            error_log('Creating users table...');
            $this->connection->exec('
                CREATE TABLE IF NOT EXISTS users (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    username TEXT UNIQUE NOT NULL,
                    email TEXT UNIQUE NOT NULL,
                    password_hash TEXT NOT NULL,
                    role TEXT DEFAULT "user",
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )
            ');
            error_log('✓ Users table created');

            error_log('Creating movies table...');
            $this->connection->exec('
                CREATE TABLE IF NOT EXISTS movies (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    title TEXT NOT NULL,
                    description TEXT,
                    year INTEGER,
                    director TEXT,
                    rating REAL DEFAULT 0,
                    created_by INTEGER,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (created_by) REFERENCES users(id)
                )
            ');
            error_log('✓ Movies table created');

            error_log('Creating reviews table...');
            $this->connection->exec('
                CREATE TABLE IF NOT EXISTS reviews (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    movie_id INTEGER NOT NULL,
                    user_id INTEGER NOT NULL,
                    rating REAL NOT NULL,
                    comment TEXT,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (movie_id) REFERENCES movies(id),
                    FOREIGN KEY (user_id) REFERENCES users(id)
                )
            ');
            error_log('✓ Reviews table created');
            
            error_log('✓ All tables created successfully');
            
        } catch (PDOException $e) {
            error_log('✗ PDOException in initializeTables: ' . $e->getMessage());
            throw new \Exception('Table creation failed: ' . $e->getMessage(), 0, $e);
        } catch (\Exception $e) {
            error_log('✗ Exception in initializeTables: ' . $e->getMessage());
            throw $e;
        }
    }
}