# Copilot Chat Conversation Export: PHP for building APIs and databases

**User:** @wargen41
**Thread URL:** https://github.com/copilot/c/6c656ba4-6fda-4e09-86c0-1ab0d167f908

## @wargen41

How would you go about designing an API for a movie database, talking to an SQLite database? Most actions for retrieving data can be public, but actions for changing and adding data must of course not be public. And some retrieve actions may also not be publicly available.

## Copilot

I'll help you think through designing a secure API for a movie database with SQLite. This is a great architectural question! Let me break down the key considerations:

## Core Architecture Components

### 1. **API Framework Setup**
Use a modern web framework with built-in authentication support:
- **Python**: FastAPI (with built-in async/await and easy auth)
- **Node.js**: Express.js with middleware
- **Go**: Gin or Echo
- **Rust**: Actix-web

### 2. **Authentication & Authorization Strategy**

**For Public Endpoints (Read-only):**
- No authentication required for basic data retrieval
- Optional authentication for rate limiting per user
- Consider implementing API keys for tracking usage

**For Protected Endpoints:**
- **JWT (JSON Web Tokens)** - Stateless, scalable, great for APIs
- **OAuth 2.0** - If integrating with external services
- **API Keys** - Simple but less flexible
- **Session-based** - Traditional but requires server state

**Authorization Levels:**
```
- Public User: READ-ONLY on public data (movies, reviews)
- Authenticated User: READ + LIMITED WRITE (add reviews, ratings)
- Admin/Moderator: FULL WRITE ACCESS (create/modify movies, manage users)
```

### 3. **Database Structure (SQLite)**

**Key Tables:**
```sql
-- Movies (public read)
CREATE TABLE movies (
  id INTEGER PRIMARY KEY,
  title TEXT NOT NULL,
  year INTEGER,
  director TEXT,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);

-- Users (internal, with auth info)
CREATE TABLE users (
  id INTEGER PRIMARY KEY,
  username TEXT UNIQUE NOT NULL,
  email TEXT UNIQUE NOT NULL,
  password_hash TEXT NOT NULL,
  role TEXT DEFAULT 'user', -- 'user', 'admin'
  created_at TIMESTAMP
);

-- Reviews (public read, authenticated write)
CREATE TABLE reviews (
  id INTEGER PRIMARY KEY,
  movie_id INTEGER NOT NULL,
  user_id INTEGER NOT NULL,
  rating REAL,
  text TEXT,
  created_at TIMESTAMP,
  FOREIGN KEY(movie_id) REFERENCES movies(id),
  FOREIGN KEY(user_id) REFERENCES users(id)
);
```

### 4. **API Endpoint Design**

```
# Public Endpoints (no auth required)
GET  /api/movies                    - List movies
GET  /api/movies/{id}               - Get movie details
GET  /api/movies/{id}/reviews       - Get reviews (consider hiding user IDs)

# Authentication Endpoint
POST /api/auth/login                - Get JWT token
POST /api/auth/register             - Create account
POST /api/auth/refresh              - Refresh JWT

# Protected Endpoints (requires valid JWT)
POST /api/reviews                   - Add review (authenticated users)
PUT  /api/reviews/{id}              - Edit own review
DELETE /api/reviews/{id}            - Delete own review

# Admin-only Endpoints (requires admin role)
POST /api/movies                    - Create movie
PUT  /api/movies/{id}               - Update movie
DELETE /api/movies/{id}             - Delete movie
```

### 5. **Security Best Practices**

**Password Security:**
- Hash passwords with bcrypt/scrypt (NEVER store plaintext)
- Use strong salt values

**JWT Implementation:**
- Short expiration times (15-30 min)
- Implement refresh token rotation
- Store refresh tokens securely server-side
- Include user ID and role in JWT payload

**Request Protection:**
- HTTPS/TLS for all endpoints
- CORS configuration (restrict domains)
- Rate limiting per IP/user
- Input validation on ALL endpoints
- SQL injection prevention (use parameterized queries)

**Response Security:**
- Don't expose sensitive data (password hashes, email addresses)
- Implement field-level filtering based on user role
- Use HTTP-only cookies for auth tokens (not localStorage)

### 6. **Example Implementation (FastAPI + SQLite)**

Here's a minimal example structure:

```python
from fastapi import FastAPI, Depends, HTTPException, status
from fastapi.security import HTTPBearer, HTTPAuthCredentials
from sqlalchemy import create_engine
from sqlalchemy.orm import Session, sessionmaker
import jwt
from datetime import datetime, timedelta

# Setup
DATABASE_URL = "sqlite:///./movies.db"
engine = create_engine(DATABASE_URL)
SessionLocal = sessionmaker(bind=engine)
SECRET_KEY = "your-secret-key-change-this"
ALGORITHM = "HS256"

app = FastAPI()
security = HTTPBearer()

# Dependency: Get DB session
def get_db():
    db = SessionLocal()
    try:
        yield db
    finally:
        db.close()

# Dependency: Verify JWT token
def verify_token(credentials: HTTPAuthCredentials = Depends(security)):
    try:
        payload = jwt.decode(credentials.credentials, SECRET_KEY, algorithms=[ALGORITHM])
        user_id: int = payload.get("sub")
        role: str = payload.get("role")
        if user_id is None:
            raise HTTPException(status_code=401, detail="Invalid token")
        return {"user_id": user_id, "role": role}
    except jwt.InvalidTokenError:
        raise HTTPException(status_code=401, detail="Invalid token")

# Public endpoint
@app.get("/api/movies")
def list_movies(db: Session = Depends(get_db)):
    movies = db.query(Movie).all()
    return movies

# Protected endpoint (authenticated users only)
@app.post("/api/reviews")
def create_review(review_data: ReviewCreate, 
                  current_user = Depends(verify_token),
                  db: Session = Depends(get_db)):
    new_review = Review(
        movie_id=review_data.movie_id,
        user_id=current_user["user_id"],
        rating=review_data.rating,
        text=review_data.text
    )
    db.add(new_review)
    db.commit()
    return new_review

# Admin-only endpoint
@app.post("/api/movies")
def create_movie(movie_data: MovieCreate,
                current_user = Depends(verify_token),
                db: Session = Depends(get_db)):
    if current_user["role"] != "admin":
        raise HTTPException(status_code=403, detail="Admin access required")
    
    new_movie = Movie(**movie_data.dict())
    db.add(new_movie)
    db.commit()
    return new_movie
```

### 7. **Middleware & Error Handling**

```python
from fastapi.middleware.cors import CORSMiddleware

app.add_middleware(
    CORSMiddleware,
    allow_origins=["https://yourdomain.com"],  # Restrict in production
    allow_credentials=True,
    allow_methods=["GET", "POST", "PUT", "DELETE"],
    allow_headers=["*"],
)

@app.exception_handler(HTTPException)
async def http_exception_handler(request, exc):
    return {"detail": exc.detail}
```

## Key Takeaways

1. **Separate concerns**: Authentication → Authorization → Data access
2. **Use JWT for stateless auth** at scale
3. **Implement role-based access control (RBAC)**
4. **Always validate & sanitize inputs**
5. **Hash passwords** with industry-standard algorithms
6. **Use parameterized queries** to prevent SQL injection
7. **Return only necessary data** in responses
8. **Log security events** (failed auth attempts, etc.)
9. **Test permission boundaries** thoroughly
10. **Keep secrets** (API keys, SECRET_KEY) in environment variables, never in code

Would you like me to dive deeper into any specific aspect, such as JWT implementation details, testing security boundaries, or deployment considerations?

## @wargen41

Is Python the easiest getting started with for a novice when it comes to building APIs and databases?

## Copilot

Great question! **Python is indeed one of the best choices for beginners**, and here's why:

## Why Python is Excellent for Beginners

### **Strengths:**

1. **Readable, English-like syntax**
   - Indentation-based (forces good habits)
   - Less "syntax noise" than other languages
   - Easier to understand what code does

2. **Rich ecosystem for APIs & databases**
   - **FastAPI** - Modern, intuitive, auto-generates API documentation
   - **Flask** - Lightweight, minimal boilerplate
   - **Django** - "Batteries included," great for full projects
   - **SQLAlchemy** - Powerful ORM that abstracts database complexity

3. **Built-in SQLite support**
   - No external dependencies needed
   - `sqlite3` module comes with Python
   - Easy to get started locally

4. **Excellent documentation & community**
   - Huge number of tutorials
   - Stack Overflow answers for almost everything
   - Active community support

5. **Fast development cycle**
   - Errors are caught at runtime (easier debugging)
   - No compilation step
   - REPL for experimenting

6. **Great for learning concepts**
   - Focus on logic rather than language quirks
   - Easy to prototype ideas quickly

---

## Comparison with Other Languages

| Language | Ease | Setup Time | Best For | Learning Curve |
|----------|------|-----------|----------|---|
| **Python** | ⭐⭐⭐⭐⭐ | 5 min | Learning, MVPs, rapid development | Gentle |
| **JavaScript/Node.js** | ⭐⭐⭐⭐ | 10 min | Full-stack (same language frontend & backend) | Moderate |
| **Go** | ⭐⭐⭐ | 15 min | Performance, scalability | Moderate |
| **Rust** | ⭐⭐ | 30+ min | Systems programming, safety | Steep |
| **Java** | ⭐⭐ | 20 min | Enterprise apps | Steep |

---

## Getting Started with Python (Fastest Path)

Here's a minimal example to get you running in minutes:

### **Step 1: Install Python**
```bash
# macOS/Linux
python3 --version

# Windows
python --version
```

### **Step 2: Install FastAPI & SQLite**
```bash
pip install fastapi uvicorn sqlite3  # sqlite3 is built-in, but this won't hurt
```

### **Step 3: Create your first API (app.py)**
```python
from fastapi import FastAPI
import sqlite3

app = FastAPI()

# Initialize database
conn = sqlite3.connect('movies.db')
cursor = conn.cursor()
cursor.execute('''
    CREATE TABLE IF NOT EXISTS movies (
        id INTEGER PRIMARY KEY,
        title TEXT NOT NULL,
        year INTEGER
    )
''')
conn.commit()

# Public endpoint
@app.get("/api/movies")
def get_movies():
    conn = sqlite3.connect('movies.db')
    cursor = conn.cursor()
    cursor.execute("SELECT * FROM movies")
    movies = cursor.fetchall()
    conn.close()
    return {"movies": movies}

# Add a movie (simplified, no auth for now)
@app.post("/api/movies")
def add_movie(title: str, year: int):
    conn = sqlite3.connect('movies.db')
    cursor = conn.cursor()
    cursor.execute("INSERT INTO movies (title, year) VALUES (?, ?)", (title, year))
    conn.commit()
    conn.close()
    return {"message": "Movie added"}
```

### **Step 4: Run it**
```bash
uvicorn app:app --reload
```

Visit: `http://localhost:8000/docs` - FastAPI auto-generates interactive documentation! 🎉

---

## Beginner-Friendly Frameworks Ranked

### **1. FastAPI** (My recommendation for learning)
- ✅ Modern, fastest to learn
- ✅ Auto-generates API docs (huge confidence boost)
- ✅ Built-in validation
- ✅ Great for teaching REST concepts
- ❌ Slightly newer (less Stack Overflow posts)

### **2. Flask**
- ✅ Simple, minimal setup
- ✅ Excellent tutorials everywhere
- ✅ Great for understanding HTTP basics
- ❌ Less opinionated (more decisions to make)

### **3. Django**
- ✅ Comprehensive (includes admin panel, auth out of the box)
- ✅ Tons of documentation
- ❌ Steeper learning curve (lots of conventions)
- ❌ Heavier for simple projects

---

## Other Beginner-Friendly Languages

**JavaScript (Node.js)** could be competitive if you want:
- Full-stack development (same language frontend & backend)
- Familiarity with web development

**But Python wins for pure backend + database learning.**

---

## My Recommendation for You

**Start with Python + FastAPI** because:

1. You'll understand concepts (not language syntax)
2. Auto-generated docs give immediate feedback
3. Quick setup, quick wins
4. SQLite integration is seamless
5. Scaling to production is straightforward

Once comfortable, you can explore:
- **Django** for larger projects
- **Node.js** if you want full-stack
- **Go** if you care about performance
- **Rust** if you want extreme reliability

---

## Resources to Get Started

- **FastAPI Official Tutorial**: https://fastapi.tiangolo.com/tutorial/
- **Real Python Tutorials**: https://realpython.com/api-with-fastapi/
- **SQLite with Python**: https://docs.python.org/3/library/sqlite3.html

Would you like me to create a more complete example with proper error handling, database abstraction, and authentication built in? I can show you a solid foundation to build from!

## @wargen41

Is PHP totally out of the question?

## Copilot

**Not at all!** PHP is actually a **great choice for beginners** building APIs with databases. I may have undersold it! Let me give you a fair assessment:

## Why PHP is Actually Really Good for This

### **Strengths:**

1. **Designed specifically for web development**
   - Built-in for handling HTTP requests/responses
   - No need to learn async/await concepts
   - Simpler mental model than general-purpose languages

2. **Excellent database integration**
   - MySQLi and PDO are battle-tested and built-in
   - SQLite support is straightforward
   - Query execution is intuitive

3. **Low barrier to entry**
   - Forgiving syntax
   - Easy to get something working quickly
   - Can start simple and grow complexity gradually

4. **Massive hosting ecosystem**
   - Available on virtually every shared hosting provider
   - Cheap/free hosting options to deploy quickly
   - Well-established deployment patterns

5. **Proven, stable frameworks**
   - **Laravel** - Modern, beautiful, great documentation
   - **Slim** - Lightweight microframework (perfect for APIs)
   - **Symfony** - Powerful, modular
   - All have excellent community support

6. **Familiar to millions**
   - Powers ~77% of the web (WordPress, Drupal, etc.)
   - Tons of tutorials and Stack Overflow answers
   - Job market is still strong

### **Weaknesses to Consider:**

- Older perception (unfairly judged)
- Modern PHP requires learning modern practices (not the outdated `<?php echo $var; ?>` style)
- Synchronous by default (less efficient under high load than Node.js or Go)
- Not ideal for real-time applications

---

## PHP vs Python for Your Use Case

| Factor | PHP | Python |
|--------|-----|--------|
| Learning ease | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ |
| Database integration | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐ |
| API development | ⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ |
| Hosting availability | ⭐⭐⭐⭐⭐ | ⭐⭐⭐ |
| Hosting cost | ⭐⭐⭐⭐⭐ | ⭐⭐⭐ |
| Authentication/Auth libraries | ⭐⭐⭐⭐ | ⭐⭐⭐⭐ |
| Modern frameworks | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ |

---

## Getting Started with PHP (Fastest Path)

### **Step 1: Install PHP**
```bash
# macOS (via Homebrew)
brew install php

# Windows: Download from php.net or use XAMPP
# Linux
sudo apt-get install php php-sqlite3
```

### **Step 2: Install a micro-framework**
```bash
# Using Composer (PHP's package manager)
composer create-project slim/slim-skeleton movie-api
cd movie-api
composer require slim/psr7
```

### **Step 3: Create your API (public/index.php)**

```php
<?php
require __DIR__ . '/../vendor/autoload.php';
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

$app = AppFactory::create();

// Initialize SQLite database
$db = new PDO('sqlite:movies.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Create table if not exists
$db->exec('
    CREATE TABLE IF NOT EXISTS movies (
        id INTEGER PRIMARY KEY,
        title TEXT NOT NULL,
        year INTEGER,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
');

// Public endpoint: Get all movies
$app->get('/api/movies', function (Request $request, Response $response) use ($db) {
    $stmt = $db->query('SELECT * FROM movies');
    $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $response->getBody()->write(json_encode(['movies' => $movies]));
    return $response->withHeader('Content-Type', 'application/json');
});

// Public endpoint: Get single movie
$app->get('/api/movies/{id}', function (Request $request, Response $response, $args) use ($db) {
    $stmt = $db->prepare('SELECT * FROM movies WHERE id = ?');
    $stmt->execute([$args['id']]);
    $movie = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$movie) {
        $response->getBody()->write(json_encode(['error' => 'Movie not found']));
        return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
    }
    
    $response->getBody()->write(json_encode($movie));
    return $response->withHeader('Content-Type', 'application/json');
});

// Protected endpoint: Add movie (simplified auth for demo)
$app->post('/api/movies', function (Request $request, Response $response) use ($db) {
    $data = json_decode($request->getBody(), true);
    
    // TODO: Add JWT verification here
    
    $stmt = $db->prepare('INSERT INTO movies (title, year) VALUES (?, ?)');
    $stmt->execute([$data['title'], $data['year']]);
    
    $response->getBody()->write(json_encode(['message' => 'Movie added', 'id' => $db->lastInsertId()]));
    return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
});

$app->run();
```

### **Step 4: Run it**
```bash
php -S localhost:8000 -t public
```

Visit: `http://localhost:8000/api/movies`

---

## Better PHP Example with Authentication

If you want modern PHP with JWT authentication:

```php
<?php
require __DIR__ . '/../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$app = AppFactory::create();
$SECRET_KEY = 'your-secret-key-change-this';

// Middleware: Verify JWT token
$verifyToken = function (Request $request, Response $response, callable $next) use ($SECRET_KEY) {
    $authHeader = $request->getHeader('Authorization')[0] ?? '';
    
    if (!preg_match('/Bearer\s+(.+)/', $authHeader, $matches)) {
        $response->getBody()->write(json_encode(['error' => 'Missing token']));
        return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
    }
    
    try {
        $decoded = JWT::decode($matches[1], new Key($SECRET_KEY, 'HS256'));
        $request = $request->withAttribute('user', $decoded);
    } catch (Exception $e) {
        $response->getBody()->write(json_encode(['error' => 'Invalid token']));
        return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
    }
    
    return $next($request, $response);
};

// Public endpoint
$app->get('/api/movies', function (Request $request, Response $response) use ($db) {
    $stmt = $db->query('SELECT id, title, year FROM movies');
    $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $response->getBody()->write(json_encode(['movies' => $movies]));
    return $response->withHeader('Content-Type', 'application/json');
});

// Protected endpoint
$app->post('/api/movies', function (Request $request, Response $response) use ($db) {
    $user = $request->getAttribute('user');
    
    // Verify user is admin
    if ($user->role !== 'admin') {
        $response->getBody()->write(json_encode(['error' => 'Unauthorized']));
        return $response->withStatus(403)->withHeader('Content-Type', 'application/json');
    }
    
    $data = json_decode($request->getBody(), true);
    $stmt = $db->prepare('INSERT INTO movies (title, year) VALUES (?, ?)');
    $stmt->execute([$data['title'], $data['year']]);
    
    $response->getBody()->write(json_encode(['message' => 'Movie added']));
    return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->run();
```

---

## PHP Frameworks Ranked for Beginners

### **1. Laravel** (Most popular, best docs)
- ✅ Excellent documentation and tutorials
- ✅ Built-in authentication, database migrations
- ✅ Beautiful, expressive syntax
- ✅ Great for scaling
- ❌ Heavier framework

### **2. Slim** (Perfect for APIs)
- ✅ Lightweight, minimal overhead
- ✅ Perfect for RESTful APIs
- ✅ Easy to understand
- ❌ Less "batteries included"

### **3. Symfony** (Enterprise-grade)
- ✅ Very powerful and modular
- ✅ Excellent routing
- ❌ Steeper learning curve

---

## How PHP Compares to Python for Your Project

**PHP is arguably BETTER for:**
- Learning HTTP basics (it forces you to think about requests/responses)
- Deploying to cheap hosting
- Building a complete project quickly
- Learning databases fundamentally

**Python is arguably BETTER for:**
- Async operations
- Machine learning integration (if needed later)
- Larger scaling operations
- Modern "cool" factor

---

## My Honest Take

**If you:**
- Want the easiest possible path to a working API → **PHP with Laravel**
- Want to learn how databases & HTTP work → **PHP with Slim**
- Want modern best practices & elegant code → **Python with FastAPI**
- Want maximum job market potential → **Either is fine, but Python trending up**

**PHP is NOT "out of the question" — it's actually a fantastic choice for what you're building.** The stigma around PHP is mostly outdated. Modern PHP is:
- Type-safe (with strict types)
- Object-oriented
- Has excellent frameworks
- Perfectly suitable for production APIs

---

## My Recommendation

**Start with Laravel** if you want:
- Everything built-in (auth, migrations, validation)
- Amazing tutorials and community
- Get to features fast

**Start with Slim** if you want:
- Something lightweight
- To understand the fundamentals better
- Just an API without extra features

Would you like me to create a complete Laravel example with authentication and all the security considerations we discussed earlier?

## @wargen41

Show me an example using Slim

## Copilot

Perfect! I'll show you a complete, production-ready example of a movie database API using Slim. This will include:

- SQLite database setup
- RESTful endpoints (public and protected)
- JWT authentication
- Input validation
- Error handling
- Security best practices

## Project Setup

```bash
# Create project directory
mkdir movie-api && cd movie-api

# Initialize Composer project
composer init

# Install dependencies
composer require slim/slim:4.* slim/psr7 firebase/php-jwt
composer require --dev phpunit/phpunit
```

## Project Structure

```
movie-api/
├── public/
│   └── index.php                 # Entry point
├── src/
│   ├── Config/
│   │   └── Database.php          # Database connection
│   ├── Middleware/
│   │   └── AuthMiddleware.php    # JWT verification
│   ├── Controllers/
│   │   ├── MovieController.php
│   │   ├── ReviewController.php
│   │   └── AuthController.php
│   └── Routes.php                # Route definitions
├── config.php                    # Configuration
└── composer.json
```

## Implementation Files

### 1. Configuration (config.php)

```php name=config.php
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
```

### 2. Database Configuration (src/Config/Database.php)

```php name=src/Config/Database.php
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
        try {
            $this->connection = new PDO(
                'sqlite:' . DB_PATH,
                null,
                null,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
        } catch (PDOException $e) {
            die('Database connection failed: ' . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function initializeTables()
    {
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
    }
}
```

### 3. Authentication Middleware (src/Middleware/AuthMiddleware.php)

```php name=src/Middleware/AuthMiddleware.php
<?php
namespace App\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class AuthMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        $authHeader = $request->getHeader('Authorization');
        
        if (empty($authHeader)) {
            return $this->jsonResponse(
                ['error' => 'Missing authorization header'],
                401
            );
        }

        $token = str_replace('Bearer ', '', $authHeader[0]);

        try {
            $decoded = JWT::decode(
                $token,
                new Key(JWT_SECRET, JWT_ALGORITHM)
            );
            
            // Attach user info to request
            $request = $request
                ->withAttribute('user_id', $decoded->sub)
                ->withAttribute('user_role', $decoded->role)
                ->withAttribute('user', $decoded);
                
        } catch (ExpiredException $e) {
            return $this->jsonResponse(['error' => 'Token expired'], 401);
        } catch (SignatureInvalidException $e) {
            return $this->jsonResponse(['error' => 'Invalid token signature'], 401);
        } catch (\Exception $e) {
            return $this->jsonResponse(['error' => 'Invalid token'], 401);
        }

        return $handler->handle($request);
    }

    private function jsonResponse($data, $status = 200)
    {
        $response = new \Slim\Psr7\Response();
        $response->getBody()->write(json_encode($data));
        return $response
            ->withStatus($status)
            ->withHeader('Content-Type', 'application/json');
    }
}
```

### 4. Movie Controller (src/Controllers/MovieController.php)

```php name=src/Controllers/MovieController.php
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
```

### 5. Review Controller (src/Controllers/ReviewController.php)

```php name=src/Controllers/ReviewController.php
<?php
namespace App\Controllers;

use App\Config\Database;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ReviewController
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Protected: Add review (authenticated users)
     */
    public function addReview(Request $request, Response $response): Response
    {
        try {
            $userId = $request->getAttribute('user_id');
            $data = $request->getParsedBody();

            // Validate input
            $errors = $this->validateReview($data);
            if (!empty($errors)) {
                return $this->jsonResponse(
                    $response,
                    ['error' => 'Validation failed', 'details' => $errors],
                    422
                );
            }

            // Check if movie exists
            $stmt = $this->db->prepare('SELECT id FROM movies WHERE id = ?');
            $stmt->execute([$data['movie_id']]);
            if (!$stmt->fetch()) {
                return $this->jsonResponse(
                    $response,
                    ['error' => 'Movie not found'],
                    404
                );
            }

            // Check if user already reviewed this movie
            $stmt = $this->db->prepare('
                SELECT id FROM reviews
                WHERE movie_id = ? AND user_id = ?
            ');
            $stmt->execute([$data['movie_id'], $userId]);
            if ($stmt->fetch()) {
                return $this->jsonResponse(
                    $response,
                    ['error' => 'You have already reviewed this movie'],
                    409
                );
            }

            // Insert review
            $stmt = $this->db->prepare('
                INSERT INTO reviews (movie_id, user_id, rating, comment)
                VALUES (?, ?, ?, ?)
            ');

            $stmt->execute([
                $data['movie_id'],
                $userId,
                $data['rating'],
                $data['comment'] ?? null
            ]);

            // Update movie average rating
            $this->updateMovieRating($data['movie_id']);

            return $this->jsonResponse(
                $response,
                [
                    'success' => true,
                    'message' => 'Review added',
                    'id' => (int)$this->db->lastInsertId()
                ],
                201
            );
        } catch (\Exception $e) {
            return $this->jsonResponse(
                $response,
                ['error' => 'Failed to add review'],
                500
            );
        }
    }

    /**
     * Protected: Update own review
     */
    public function updateReview(Request $request, Response $response, array $args): Response
    {
        try {
            $userId = $request->getAttribute('user_id');
            $reviewId = $args['id'] ?? null;
            $data = $request->getParsedBody();

            // Check if review exists and belongs to user
            $stmt = $this->db->prepare('
                SELECT movie_id FROM reviews
                WHERE id = ? AND user_id = ?
            ');
            $stmt->execute([$reviewId, $userId]);
            $review = $stmt->fetch();

            if (!$review) {
                return $this->jsonResponse(
                    $response,
                    ['error' => 'Review not found or you do not have permission'],
                    404
                );
            }

            // Update review
            $stmt = $this->db->prepare('
                UPDATE reviews
                SET rating = COALESCE(?, rating),
                    comment = COALESCE(?, comment),
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = ?
            ');

            $stmt->execute([
                $data['rating'] ?? null,
                $data['comment'] ?? null,
                $reviewId
            ]);

            // Update movie rating
            $this->updateMovieRating($review['movie_id']);

            return $this->jsonResponse($response, [
                'success' => true,
                'message' => 'Review updated'
            ]);
        } catch (\Exception $e) {
            return $this->jsonResponse(
                $response,
                ['error' => 'Failed to update review'],
                500
            );
        }
    }

    /**
     * Protected: Delete own review
     */
    public function deleteReview(Request $request, Response $response, array $args): Response
    {
        try {
            $userId = $request->getAttribute('user_id');
            $reviewId = $args['id'] ?? null;

            // Check if review exists and belongs to user
            $stmt = $this->db->prepare('
                SELECT movie_id FROM reviews
                WHERE id = ? AND user_id = ?
            ');
            $stmt->execute([$reviewId, $userId]);
            $review = $stmt->fetch();

            if (!$review) {
                return $this->jsonResponse(
                    $response,
                    ['error' => 'Review not found or you do not have permission'],
                    404
                );
            }

            // Delete review
            $stmt = $this->db->prepare('DELETE FROM reviews WHERE id = ?');
            $stmt->execute([$reviewId]);

            // Update movie rating
            $this->updateMovieRating($review['movie_id']);

            return $this->jsonResponse($response, [
                'success' => true,
                'message' => 'Review deleted'
            ]);
        } catch (\Exception $e) {
            return $this->jsonResponse(
                $response,
                ['error' => 'Failed to delete review'],
                500
            );
        }
    }

    private function updateMovieRating($movieId)
    {
        $stmt = $this->db->prepare('
            SELECT AVG(rating) as avg_rating FROM reviews
            WHERE movie_id = ?
        ');
        $stmt->execute([$movieId]);
        $result = $stmt->fetch();

        $stmt = $this->db->prepare('
            UPDATE movies SET rating = ? WHERE id = ?
        ');
        $stmt->execute([$result['avg_rating'] ?? 0, $movieId]);
    }

    private function validateReview($data)
    {
        $errors = [];

        if (empty($data['movie_id'])) {
            $errors['movie_id'] = 'Movie ID is required';
        }

        if (empty($data['rating']) || !is_numeric($data['rating']) || $data['rating'] < 1 || $data['rating'] > 10) {
            $errors['rating'] = 'Rating must be between 1 and 10';
        }

        if (!empty($data['comment']) && strlen($data['comment']) > 1000) {
            $errors['comment'] = 'Comment must be less than 1000 characters';
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
```

### 6. Auth Controller (src/Controllers/AuthController.php)

```php name=src/Controllers/AuthController.php
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
```

### 7. Routes Definition (src/Routes.php)

```php name=src/Routes.php
<?php
namespace App;

use App\Controllers\MovieController;
use App\Controllers\ReviewController;
use App\Controllers\AuthController;
use App\Middleware\AuthMiddleware;
use Slim\App;

class Routes
{
    public static function register(App $app)
    {
        // Authentication routes (public)
        $app->post('/api/auth/register', [AuthController::class, 'register']);
        $app->post('/api/auth/login', [AuthController::class, 'login']);

        // Movie routes (public read)
        $app->get('/api/movies', [MovieController::class, 'listMovies']);
        $app->get('/api/movies/{id}', [MovieController::class, 'getMovie']);

        // Movie routes (protected - admin only)
        $app->post('/api/movies', [MovieController::class, 'createMovie'])
            ->add(new AuthMiddleware());
        $app->put('/api/movies/{id}', [MovieController::class, 'updateMovie'])
            ->add(new AuthMiddleware());
        $app->delete('/api/movies/{id}', [MovieController::class, 'deleteMovie'])
            ->add(new AuthMiddleware());

        // Review routes (public read)
        // (Reviews shown with movie details)

        // Review routes (protected - authenticated users)
        $app->post('/api/reviews', [ReviewController::class, 'addReview'])
            ->add(new AuthMiddleware());
        $app->put('/api/reviews/{id}', [ReviewController::class, 'updateReview'])
            ->add(new AuthMiddleware());
        $app->delete('/api/reviews/{id}', [ReviewController::class, 'deleteReview'])
            ->add(new AuthMiddleware());
    }
}
```

### 8. Entry Point (public/index.php)

```php name=public/index.php
<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config.php';

use Slim\Factory\AppFactory;
use App\Config\Database;
use App\Routes;

// Initialize database
$db = Database::getInstance();
$db->initializeTables();

// Create Slim app
$app = AppFactory::create();

// Add error handling middleware
$app->addErrorMiddleware(ENVIRONMENT === 'development', true, true);

// Add CORS middleware
$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');
});

// Handle preflight requests
$app->options('/{routes:.+}', function ($request, $response) {
    return $response;
});

// Register routes
Routes::register($app);

// Run app
$app->run();
```

### 9. Composer.json

```json name=composer.json
{
    "name": "movie-api/slim-api",
    "description": "A movie database API built with Slim Framework",
    "require": {
        "php": "^8.0",
        "slim/slim": "^4.11",
        "slim/psr7": "^1.6",
        "firebase/php-jwt": "^6.8"
    },
    "require-dev": {
        "phpunit/phpunit": "^10.0"
    },
    "autoload": {
        "psr-4": {
            "App\\": "src/"
        }
    }
}
```

## Running the API

```bash
# Install dependencies
composer install

# Run development server
php -S localhost:8000 -t public

# Visit the API
curl http://localhost:8000/api/movies
```

## Example Requests

### Register a user:
```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "username": "john_doe",
    "email": "john@example.com",
    "password": "secure_password_123"
  }'
```

### Login:
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "secure_password_123"
  }'
```

### Get all movies (public):
```bash
curl http://localhost:8000/api/movies
```

### Add a movie (admin only):
```bash
curl -X POST http://localhost:8000/api/movies \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -d '{
    "title": "The Matrix",
    "description": "A sci-fi masterpiece",
    "year": 1999,
    "director": "The Wachowskis"
  }'
```

### Add a review (authenticated users):
```bash
curl -X POST http://localhost:8000/api/reviews \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -d '{
    "movie_id": 1,
    "rating": 9.5,
    "comment": "Amazing film!"
  }'
```

## Key Features This Example Demonstrates

✅ **Public endpoints** - List and view movies  
✅ **Authentication** - JWT tokens with registration/login  
✅ **Authorization** - Role-based access control (admin vs user)  
✅ **Input validation** - All data is validated  
✅ **SQL injection prevention** - Parameterized queries  
✅ **Password security** - Bcrypt hashing  
✅ **Error handling** - Proper HTTP status codes  
✅ **Clean architecture** - Separation of concerns  
✅ **Database relationships** - Foreign keys and joins

This is a solid foundation you can build on! Would you like me to explain any specific part in more detail, or show you how to add testing or deploy this?

## @wargen41

Could you show what a setup with similar functionality would look like using Python + FastAPI instead?

## Copilot

I'll show you a complete Python + FastAPI implementation with the same functionality as the Slim example. This will have the same endpoints, security, and features.

## Project Setup

```bash
# Create project directory
mkdir movie-api-fastapi && cd movie-api-fastapi

# Create virtual environment
python3 -m venv venv
source venv/bin/activate  # On Windows: venv\Scripts\activate

# Create requirements.txt and install dependencies
cat > requirements.txt << EOF
fastapi==0.104.1
uvicorn[standard]==0.24.0
sqlalchemy==2.0.23
python-jose[cryptography]==3.3.0
passlib[bcrypt]==1.7.4
pydantic==2.5.0
pydantic-settings==2.1.0
python-multipart==0.0.6
EOF

pip install -r requirements.txt
```

## Project Structure

```
movie-api-fastapi/
├── app/
│   ├── __init__.py
│   ├── main.py                    # Entry point
│   ├── config.py                  # Configuration
│   ├── database.py                # Database setup
│   ├── models.py                  # SQLAlchemy models
│   ├── schemas.py                 # Pydantic schemas
│   ├── security.py                # Authentication/JWT
│   ├── dependencies.py            # Dependency injection
│   ├── routers/
│   │   ├── __init__.py
│   │   ├── auth.py                # Auth endpoints
│   │   ├── movies.py              # Movie endpoints
│   │   └── reviews.py             # Review endpoints
│   └── crud/
│       ├── __init__.py
│       ├── user.py                # User CRUD operations
│       ├── movie.py               # Movie CRUD operations
│       └── review.py              # Review CRUD operations
├── .env                           # Environment variables
├── requirements.txt
└── run.py                         # Development runner
```

## Implementation Files

### 1. Configuration (app/config.py)

```python name=app/config.py
from pydantic_settings import BaseSettings
from typing import Optional

class Settings(BaseSettings):
    """Application settings"""
    
    # API
    api_title: str = "Movie Database API"
    api_version: str = "1.0.0"
    api_description: str = "A simple movie database API with reviews"
    
    # Database
    database_url: str = "sqlite:///./movies.db"
    
    # JWT
    secret_key: str = "your-secret-key-change-in-production"
    algorithm: str = "HS256"
    access_token_expire_minutes: int = 60
    
    # CORS
    allowed_origins: list = [
        "http://localhost:3000",
        "http://localhost:8000",
        "https://yourdomain.com"
    ]
    
    class Config:
        env_file = ".env"

settings = Settings()
```

### 2. Database Setup (app/database.py)

```python name=app/database.py
from sqlalchemy import create_engine
from sqlalchemy.orm import sessionmaker, declarative_base
from app.config import settings

# Create engine
engine = create_engine(
    settings.database_url,
    connect_args={"check_same_thread": False}  # SQLite specific
)

# Create session factory
SessionLocal = sessionmaker(
    autocommit=False,
    autoflush=False,
    bind=engine
)

# Base class for models
Base = declarative_base()

# Dependency to get database session
def get_db():
    db = SessionLocal()
    try:
        yield db
    finally:
        db.close()
```

### 3. SQLAlchemy Models (app/models.py)

```python name=app/models.py
from sqlalchemy import Column, Integer, String, Float, Text, DateTime, ForeignKey, Boolean
from sqlalchemy.orm import relationship
from datetime import datetime
from app.database import Base

class User(Base):
    __tablename__ = "users"
    
    id = Column(Integer, primary_key=True, index=True)
    username = Column(String(50), unique=True, index=True, nullable=False)
    email = Column(String(100), unique=True, index=True, nullable=False)
    password_hash = Column(String(255), nullable=False)
    role = Column(String(20), default="user")  # "user" or "admin"
    created_at = Column(DateTime, default=datetime.utcnow)
    updated_at = Column(DateTime, default=datetime.utcnow, onupdate=datetime.utcnow)
    
    # Relationships
    movies = relationship("Movie", back_populates="creator")
    reviews = relationship("Review", back_populates="author")


class Movie(Base):
    __tablename__ = "movies"
    
    id = Column(Integer, primary_key=True, index=True)
    title = Column(String(255), nullable=False, index=True)
    description = Column(Text, nullable=True)
    year = Column(Integer, nullable=True)
    director = Column(String(255), nullable=True)
    rating = Column(Float, default=0.0)  # Average rating
    created_by = Column(Integer, ForeignKey("users.id"), nullable=True)
    created_at = Column(DateTime, default=datetime.utcnow)
    updated_at = Column(DateTime, default=datetime.utcnow, onupdate=datetime.utcnow)
    
    # Relationships
    creator = relationship("User", back_populates="movies")
    reviews = relationship("Review", back_populates="movie", cascade="all, delete-orphan")


class Review(Base):
    __tablename__ = "reviews"
    
    id = Column(Integer, primary_key=True, index=True)
    movie_id = Column(Integer, ForeignKey("movies.id"), nullable=False)
    user_id = Column(Integer, ForeignKey("users.id"), nullable=False)
    rating = Column(Float, nullable=False)  # 1-10
    comment = Column(Text, nullable=True)
    created_at = Column(DateTime, default=datetime.utcnow)
    updated_at = Column(DateTime, default=datetime.utcnow, onupdate=datetime.utcnow)
    
    # Relationships
    movie = relationship("Movie", back_populates="reviews")
    author = relationship("User", back_populates="reviews")
```

### 4. Pydantic Schemas (app/schemas.py)

```python name=app/schemas.py
from pydantic import BaseModel, EmailStr, Field, validator
from typing import Optional, List
from datetime import datetime

# ==================== User Schemas ====================

class UserBase(BaseModel):
    username: str = Field(..., min_length=3, max_length=50)
    email: EmailStr

class UserRegister(UserBase):
    password: str = Field(..., min_length=8)
    
    @validator('password')
    def password_must_be_strong(cls, v):
        if not any(c.isupper() for c in v):
            raise ValueError('Password must contain at least one uppercase letter')
        return v

class UserLogin(BaseModel):
    email: EmailStr
    password: str

class UserResponse(UserBase):
    id: int
    role: str
    created_at: datetime
    
    class Config:
        from_attributes = True

class UserInDB(UserResponse):
    password_hash: str

# ==================== Movie Schemas ====================

class MovieBase(BaseModel):
    title: str = Field(..., min_length=2, max_length=255)
    description: Optional[str] = Field(None, max_length=2000)
    year: Optional[int] = Field(None, ge=1800, le=2100)
    director: Optional[str] = Field(None, max_length=255)

class MovieCreate(MovieBase):
    pass

class MovieUpdate(BaseModel):
    title: Optional[str] = Field(None, min_length=2, max_length=255)
    description: Optional[str] = Field(None, max_length=2000)
    year: Optional[int] = Field(None, ge=1800, le=2100)
    director: Optional[str] = Field(None, max_length=255)

class MovieResponse(MovieBase):
    id: int
    rating: float
    created_at: datetime
    updated_at: datetime
    
    class Config:
        from_attributes = True

class MovieWithReviews(MovieResponse):
    reviews: List['ReviewResponse'] = []

# ==================== Review Schemas ====================

class ReviewBase(BaseModel):
    movie_id: int
    rating: float = Field(..., ge=1, le=10)
    comment: Optional[str] = Field(None, max_length=1000)

class ReviewCreate(ReviewBase):
    pass

class ReviewUpdate(BaseModel):
    rating: Optional[float] = Field(None, ge=1, le=10)
    comment: Optional[str] = Field(None, max_length=1000)

class ReviewResponse(BaseModel):
    id: int
    movie_id: int
    rating: float
    comment: Optional[str]
    created_at: datetime
    author: UserResponse
    
    class Config:
        from_attributes = True

class ReviewWithMovie(ReviewResponse):
    movie: MovieResponse

# ==================== Auth Schemas ====================

class TokenResponse(BaseModel):
    access_token: str
    token_type: str = "bearer"

class AuthResponse(BaseModel):
    success: bool
    message: str
    token: str
    user: UserResponse

# Update forward references for nested models
MovieWithReviews.model_rebuild()
ReviewResponse.model_rebuild()
ReviewWithMovie.model_rebuild()
```

### 5. Security & JWT (app/security.py)

```python name=app/security.py
from datetime import datetime, timedelta, timezone
from typing import Optional
from jose import JWTError, jwt
from passlib.context import CryptContext
from fastapi.security import HTTPBearer, HTTPAuthCredentials
from fastapi import HTTPException, status
from app.config import settings

# Password hashing
pwd_context = CryptContext(schemes=["bcrypt"], deprecated="auto")

# HTTP Bearer scheme
security = HTTPBearer()

class SecurityUtils:
    @staticmethod
    def hash_password(password: str) -> str:
        """Hash a password using bcrypt"""
        return pwd_context.hash(password)
    
    @staticmethod
    def verify_password(plain_password: str, hashed_password: str) -> bool:
        """Verify a password against its hash"""
        return pwd_context.verify(plain_password, hashed_password)
    
    @staticmethod
    def create_access_token(data: dict, expires_delta: Optional[timedelta] = None) -> str:
        """Create a JWT access token"""
        to_encode = data.copy()
        
        if expires_delta:
            expire = datetime.now(timezone.utc) + expires_delta
        else:
            expire = datetime.now(timezone.utc) + timedelta(
                minutes=settings.access_token_expire_minutes
            )
        
        to_encode.update({"exp": expire})
        
        encoded_jwt = jwt.encode(
            to_encode,
            settings.secret_key,
            algorithm=settings.algorithm
        )
        return encoded_jwt
    
    @staticmethod
    def verify_token(token: str) -> dict:
        """Verify and decode a JWT token"""
        try:
            payload = jwt.decode(
                token,
                settings.secret_key,
                algorithms=[settings.algorithm]
            )
            user_id: int = payload.get("sub")
            role: str = payload.get("role")
            
            if user_id is None:
                raise HTTPException(
                    status_code=status.HTTP_401_UNAUTHORIZED,
                    detail="Invalid token"
                )
            
            return {"user_id": user_id, "role": role}
            
        except JWTError:
            raise HTTPException(
                status_code=status.HTTP_401_UNAUTHORIZED,
                detail="Invalid token"
            )
        except Exception as e:
            raise HTTPException(
                status_code=status.HTTP_401_UNAUTHORIZED,
                detail="Token validation failed"
            )
```

### 6. Dependencies (app/dependencies.py)

```python name=app/dependencies.py
from fastapi import Depends, HTTPException, status
from sqlalchemy.orm import Session
from app.database import get_db
from app.models import User
from app.security import SecurityUtils, security
from fastapi.security import HTTPAuthCredentials

async def get_current_user(
    credentials: HTTPAuthCredentials = Depends(security),
    db: Session = Depends(get_db)
) -> User:
    """Get the current authenticated user"""
    token = credentials.credentials
    token_data = SecurityUtils.verify_token(token)
    
    user = db.query(User).filter(User.id == token_data["user_id"]).first()
    
    if not user:
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="User not found"
        )
    
    return user

async def get_admin_user(
    current_user: User = Depends(get_current_user)
) -> User:
    """Ensure current user is admin"""
    if current_user.role != "admin":
        raise HTTPException(
            status_code=status.HTTP_403_FORBIDDEN,
            detail="Admin access required"
        )
    
    return current_user
```

### 7. User CRUD (app/crud/user.py)

```python name=app/crud/user.py
from sqlalchemy.orm import Session
from app.models import User
from app.schemas import UserRegister
from app.security import SecurityUtils

class UserCRUD:
    @staticmethod
    def get_user_by_email(db: Session, email: str) -> User:
        return db.query(User).filter(User.email == email).first()
    
    @staticmethod
    def get_user_by_username(db: Session, username: str) -> User:
        return db.query(User).filter(User.username == username).first()
    
    @staticmethod
    def get_user_by_id(db: Session, user_id: int) -> User:
        return db.query(User).filter(User.id == user_id).first()
    
    @staticmethod
    def create_user(db: Session, user: UserRegister) -> User:
        """Create a new user"""
        db_user = User(
            username=user.username,
            email=user.email,
            password_hash=SecurityUtils.hash_password(user.password),
            role="user"
        )
        db.add(db_user)
        db.commit()
        db.refresh(db_user)
        return db_user
    
    @staticmethod
    def authenticate_user(db: Session, email: str, password: str) -> User:
        """Authenticate user with email and password"""
        user = UserCRUD.get_user_by_email(db, email)
        
        if not user:
            return None
        
        if not SecurityUtils.verify_password(password, user.password_hash):
            return None
        
        return user
```

### 8. Movie CRUD (app/crud/movie.py)

```python name=app/crud/movie.py
from sqlalchemy.orm import Session
from sqlalchemy import func
from app.models import Movie, Review
from app.schemas import MovieCreate, MovieUpdate

class MovieCRUD:
    @staticmethod
    def get_all_movies(db: Session, skip: int = 0, limit: int = 100):
        """Get all movies with pagination"""
        return db.query(Movie).offset(skip).limit(limit).all()
    
    @staticmethod
    def get_movie_by_id(db: Session, movie_id: int) -> Movie:
        return db.query(Movie).filter(Movie.id == movie_id).first()
    
    @staticmethod
    def create_movie(db: Session, movie: MovieCreate, user_id: int) -> Movie:
        """Create a new movie"""
        db_movie = Movie(
            title=movie.title,
            description=movie.description,
            year=movie.year,
            director=movie.director,
            created_by=user_id
        )
        db.add(db_movie)
        db.commit()
        db.refresh(db_movie)
        return db_movie
    
    @staticmethod
    def update_movie(db: Session, movie_id: int, movie_update: MovieUpdate) -> Movie:
        """Update a movie"""
        db_movie = MovieCRUD.get_movie_by_id(db, movie_id)
        
        if not db_movie:
            return None
        
        update_data = movie_update.model_dump(exclude_unset=True)
        for key, value in update_data.items():
            setattr(db_movie, key, value)
        
        db.add(db_movie)
        db.commit()
        db.refresh(db_movie)
        return db_movie
    
    @staticmethod
    def delete_movie(db: Session, movie_id: int) -> bool:
        """Delete a movie"""
        db_movie = MovieCRUD.get_movie_by_id(db, movie_id)
        
        if not db_movie:
            return False
        
        db.delete(db_movie)
        db.commit()
        return True
    
    @staticmethod
    def update_movie_rating(db: Session, movie_id: int):
        """Recalculate and update average rating for a movie"""
        avg_rating = db.query(func.avg(Review.rating)).filter(
            Review.movie_id == movie_id
        ).scalar()
        
        db_movie = MovieCRUD.get_movie_by_id(db, movie_id)
        if db_movie:
            db_movie.rating = avg_rating or 0.0
            db.add(db_movie)
            db.commit()
```

### 9. Review CRUD (app/crud/review.py)

```python name=app/crud/review.py
from sqlalchemy.orm import Session
from app.models import Review
from app.schemas import ReviewCreate, ReviewUpdate
from app.crud.movie import MovieCRUD

class ReviewCRUD:
    @staticmethod
    def get_review_by_id(db: Session, review_id: int) -> Review:
        return db.query(Review).filter(Review.id == review_id).first()
    
    @staticmethod
    def get_movie_reviews(db: Session, movie_id: int):
        """Get all reviews for a movie"""
        return db.query(Review).filter(Review.movie_id == movie_id).all()
    
    @staticmethod
    def user_already_reviewed(db: Session, movie_id: int, user_id: int) -> bool:
        """Check if user has already reviewed this movie"""
        return db.query(Review).filter(
            Review.movie_id == movie_id,
            Review.user_id == user_id
        ).first() is not None
    
    @staticmethod
    def create_review(db: Session, review: ReviewCreate, user_id: int) -> Review:
        """Create a new review"""
        db_review = Review(
            movie_id=review.movie_id,
            user_id=user_id,
            rating=review.rating,
            comment=review.comment
        )
        db.add(db_review)
        db.commit()
        db.refresh(db_review)
        
        # Update movie rating
        MovieCRUD.update_movie_rating(db, review.movie_id)
        
        return db_review
    
    @staticmethod
    def update_review(db: Session, review_id: int, review_update: ReviewUpdate) -> Review:
        """Update a review"""
        db_review = ReviewCRUD.get_review_by_id(db, review_id)
        
        if not db_review:
            return None
        
        update_data = review_update.model_dump(exclude_unset=True)
        for key, value in update_data.items():
            setattr(db_review, key, value)
        
        db.add(db_review)
        db.commit()
        db.refresh(db_review)
        
        # Update movie rating
        MovieCRUD.update_movie_rating(db, db_review.movie_id)
        
        return db_review
    
    @staticmethod
    def delete_review(db: Session, review_id: int) -> bool:
        """Delete a review"""
        db_review = ReviewCRUD.get_review_by_id(db, review_id)
        
        if not db_review:
            return False
        
        movie_id = db_review.movie_id
        db.delete(db_review)
        db.commit()
        
        # Update movie rating
        MovieCRUD.update_movie_rating(db, movie_id)
        
        return True
```

### 10. Auth Routes (app/routers/auth.py)

```python name=app/routers/auth.py
from fastapi import APIRouter, Depends, HTTPException, status
from sqlalchemy.orm import Session
from datetime import timedelta
from app.schemas import UserRegister, UserLogin, TokenResponse, AuthResponse, UserResponse
from app.crud.user import UserCRUD
from app.security import SecurityUtils
from app.database import get_db
from app.config import settings

router = APIRouter(prefix="/api/auth", tags=["authentication"])

@router.post("/register", response_model=AuthResponse, status_code=status.HTTP_201_CREATED)
async def register(user: UserRegister, db: Session = Depends(get_db)):
    """Register a new user"""
    
    # Check if user already exists
    if UserCRUD.get_user_by_email(db, user.email):
        raise HTTPException(
            status_code=status.HTTP_409_CONFLICT,
            detail="Email already registered"
        )
    
    if UserCRUD.get_user_by_username(db, user.username):
        raise HTTPException(
            status_code=status.HTTP_409_CONFLICT,
            detail="Username already taken"
        )
    
    # Create user
    db_user = UserCRUD.create_user(db, user)
    
    # Generate token
    access_token_expires = timedelta(minutes=settings.access_token_expire_minutes)
    token = SecurityUtils.create_access_token(
        data={"sub": db_user.id, "role": db_user.role},
        expires_delta=access_token_expires
    )
    
    return AuthResponse(
        success=True,
        message="User registered successfully",
        token=token,
        user=UserResponse.model_validate(db_user)
    )

@router.post("/login", response_model=AuthResponse)
async def login(credentials: UserLogin, db: Session = Depends(get_db)):
    """Login user and get access token"""
    
    # Authenticate user
    user = UserCRUD.authenticate_user(db, credentials.email, credentials.password)
    
    if not user:
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Invalid credentials"
        )
    
    # Generate token
    access_token_expires = timedelta(minutes=settings.access_token_expire_minutes)
    token = SecurityUtils.create_access_token(
        data={"sub": user.id, "role": user.role},
        expires_delta=access_token_expires
    )
    
    return AuthResponse(
        success=True,
        message="Login successful",
        token=token,
        user=UserResponse.model_validate(user)
    )
```

### 11. Movie Routes (app/routers/movies.py)

```python name=app/routers/movies.py
from fastapi import APIRouter, Depends, HTTPException, status, Query
from sqlalchemy.orm import Session
from typing import List
from app.models import User
from app.schemas import MovieCreate, MovieUpdate, MovieResponse, MovieWithReviews, ReviewResponse
from app.crud.movie import MovieCRUD
from app.crud.review import ReviewCRUD
from app.database import get_db
from app.dependencies import get_current_user, get_admin_user

router = APIRouter(prefix="/api/movies", tags=["movies"])

@router.get("", response_model=List[MovieResponse])
async def list_movies(
    skip: int = Query(0, ge=0),
    limit: int = Query(10, ge=1, le=100),
    db: Session = Depends(get_db)
):
    """Get all movies (public endpoint)"""
    movies = MovieCRUD.get_all_movies(db, skip=skip, limit=limit)
    return movies

@router.get("/{movie_id}", response_model=MovieWithReviews)
async def get_movie(movie_id: int, db: Session = Depends(get_db)):
    """Get a single movie with its reviews (public endpoint)"""
    movie = MovieCRUD.get_movie_by_id(db, movie_id)
    
    if not movie:
        raise HTTPException(
            status_code=status.HTTP_404_NOT_FOUND,
            detail="Movie not found"
        )
    
    # Get reviews
    reviews = ReviewCRUD.get_movie_reviews(db, movie_id)
    movie.reviews = reviews
    
    return movie

@router.post("", response_model=dict, status_code=status.HTTP_201_CREATED)
async def create_movie(
    movie: MovieCreate,
    current_user: User = Depends(get_admin_user),
    db: Session = Depends(get_db)
):
    """Create a new movie (admin only)"""
    db_movie = MovieCRUD.create_movie(db, movie, current_user.id)
    
    return {
        "success": True,
        "message": "Movie created",
        "id": db_movie.id
    }

@router.put("/{movie_id}", response_model=dict)
async def update_movie(
    movie_id: int,
    movie_update: MovieUpdate,
    current_user: User = Depends(get_admin_user),
    db: Session = Depends(get_db)
):
    """Update a movie (admin only)"""
    movie = MovieCRUD.get_movie_by_id(db, movie_id)
    
    if not movie:
        raise HTTPException(
            status_code=status.HTTP_404_NOT_FOUND,
            detail="Movie not found"
        )
    
    updated_movie = MovieCRUD.update_movie(db, movie_id, movie_update)
    
    return {
        "success": True,
        "message": "Movie updated"
    }

@router.delete("/{movie_id}", response_model=dict)
async def delete_movie(
    movie_id: int,
    current_user: User = Depends(get_admin_user),
    db: Session = Depends(get_db)
):
    """Delete a movie (admin only)"""
    if not MovieCRUD.delete_movie(db, movie_id):
        raise HTTPException(
            status_code=status.HTTP_404_NOT_FOUND,
            detail="Movie not found"
        )
    
    return {
        "success": True,
        "message": "Movie deleted"
    }
```

### 12. Review Routes (app/routers/reviews.py)

```python name=app/routers/reviews.py
from fastapi import APIRouter, Depends, HTTPException, status
from sqlalchemy.orm import Session
from app.models import User
from app.schemas import ReviewCreate, ReviewUpdate, ReviewResponse
from app.crud.review import ReviewCRUD
from app.crud.movie import MovieCRUD
from app.database import get_db
from app.dependencies import get_current_user

router = APIRouter(prefix="/api/reviews", tags=["reviews"])

@router.post("", response_model=dict, status_code=status.HTTP_201_CREATED)
async def create_review(
    review: ReviewCreate,
    current_user: User = Depends(get_current_user),
    db: Session = Depends(get_db)
):
    """Create a new review (authenticated users only)"""
    
    # Check if movie exists
    if not MovieCRUD.get_movie_by_id(db, review.movie_id):
        raise HTTPException(
            status_code=status.HTTP_404_NOT_FOUND,
            detail="Movie not found"
        )
    
    # Check if user already reviewed this movie
    if ReviewCRUD.user_already_reviewed(db, review.movie_id, current_user.id):
        raise HTTPException(
            status_code=status.HTTP_409_CONFLICT,
            detail="You have already reviewed this movie"
        )
    
    db_review = ReviewCRUD.create_review(db, review, current_user.id)
    
    return {
        "success": True,
        "message": "Review created",
        "id": db_review.id
    }

@router.put("/{review_id}", response_model=dict)
async def update_review(
    review_id: int,
    review_update: ReviewUpdate,
    current_user: User = Depends(get_current_user),
    db: Session = Depends(get_db)
):
    """Update your own review"""
    
    review = ReviewCRUD.get_review_by_id(db, review_id)
    
    if not review:
        raise HTTPException(
            status_code=status.HTTP_404_NOT_FOUND,
            detail="Review not found"
        )
    
    # Check authorization
    if review.user_id != current_user.id:
        raise HTTPException(
            status_code=status.HTTP_403_FORBIDDEN,
            detail="You can only update your own reviews"
        )
    
    ReviewCRUD.update_review(db, review_id, review_update)
    
    return {
        "success": True,
        "message": "Review updated"
    }

@router.delete("/{review_id}", response_model=dict)
async def delete_review(
    review_id: int,
    current_user: User = Depends(get_current_user),
    db: Session = Depends(get_db)
):
    """Delete your own review"""
    
    review = ReviewCRUD.get_review_by_id(db, review_id)
    
    if not review:
        raise HTTPException(
            status_code=status.HTTP_404_NOT_FOUND,
            detail="Review not found"
        )
    
    # Check authorization
    if review.user_id != current_user.id:
        raise HTTPException(
            status_code=status.HTTP_403_FORBIDDEN,
            detail="You can only delete your own reviews"
        )
    
    ReviewCRUD.delete_review(db, review_id)
    
    return {
        "success": True,
        "message": "Review deleted"
    }
```

### 13. Router Registration (app/routers/__init__.py)

```python name=app/routers/__init__.py
from fastapi import APIRouter
from app.routers import auth, movies, reviews

# Create combined router
api_router = APIRouter()

# Include all sub-routers
api_router.include_router(auth.router)
api_router.include_router(movies.router)
api_router.include_router(reviews.router)
```

### 14. CRUD Initialization (app/crud/__init__.py)

```python name=app/crud/__init__.py
# This file intentionally left empty
```

### 15. Main Application (app/main.py)

```python name=app/main.py
from fastapi import FastAPI, HTTPException, status
from fastapi.middleware.cors import CORSMiddleware
from fastapi.responses import JSONResponse
from fastapi.exceptions import RequestValidationError
from app.config import settings
from app.database import engine, Base
from app.routers import api_router
from app.models import User, Movie, Review

# Create tables
Base.metadata.create_all(bind=engine)

# Create FastAPI app
app = FastAPI(
    title=settings.api_title,
    description=settings.api_description,
    version=settings.api_version,
    docs_url="/docs",
    redoc_url="/redoc",
    openapi_url="/openapi.json"
)

# Add CORS middleware
app.add_middleware(
    CORSMiddleware,
    allow_origins=settings.allowed_origins,
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# Global exception handler for validation errors
@app.exception_handler(RequestValidationError)
async def validation_exception_handler(request, exc):
    """Custom handler for validation errors"""
    return JSONResponse(
        status_code=status.HTTP_422_UNPROCESSABLE_ENTITY,
        content={
            "error": "Validation failed",
            "details": exc.errors()
        }
    )

# Health check endpoint
@app.get("/health", tags=["health"])
async def health_check():
    """Health check endpoint"""
    return {
        "status": "healthy",
        "version": settings.api_version
    }

# Include all routers
app.include_router(api_router)

# Root endpoint
@app.get("/", tags=["root"])
async def root():
    """Root endpoint with API information"""
    return {
        "title": settings.api_title,
        "version": settings.api_version,
        "description": settings.api_description,
        "docs": "/docs",
        "redoc": "/redoc"
    }

if __name__ == "__main__":
    import uvicorn
    uvicorn.run(
        "app.main:app",
        host="0.0.0.0",
        port=8000,
        reload=True
    )
```

### 16. Application Init (app/__init__.py)

```python name=app/__init__.py
# This file intentionally left empty
```

### 17. Development Runner (run.py)

```python name=run.py
import uvicorn
import os

if __name__ == "__main__":
    # Load environment variables
    os.environ.setdefault("ENVIRONMENT", "development")
    
    uvicorn.run(
        "app.main:app",
        host="0.0.0.0",
        port=8000,
        reload=True,
        log_level="info"
    )
```

### 18. Environment Variables (.env)

```env name=.env
# Database
DATABASE_URL=sqlite:///./movies.db

# JWT
SECRET_KEY=your-super-secret-key-change-in-production-now
ALGORITHM=HS256
ACCESS_TOKEN_EXPIRE_MINUTES=60

# Environment
ENVIRONMENT=development
```

### 19. Requirements (requirements.txt)

```text name=requirements.txt
fastapi==0.104.1
uvicorn[standard]==0.24.0
sqlalchemy==2.0.23
python-jose[cryptography]==3.3.0
passlib[bcrypt]==1.7.4
pydantic==2.5.0
pydantic-settings==2.1.0
python-multipart==0.0.6
```

## Running the Application

```bash
# Install dependencies
pip install -r requirements.txt

# Run development server
python run.py

# Or use uvicorn directly
uvicorn app.main:app --reload

# Or use the included runner
uvicorn app.main:app --host 0.0.0.0 --port 8000 --reload
```

Visit:
- **API Docs**: http://localhost:8000/docs (Swagger UI - interactive!)
- **Alternative Docs**: http://localhost:8000/redoc
- **Health Check**: http://localhost:8000/health

## Example Requests

### Register a user:
```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "username": "john_doe",
    "email": "john@example.com",
    "password": "SecurePassword123"
  }'
```

### Login:
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "SecurePassword123"
  }'
```

### Get all movies (public):
```bash
curl http://localhost:8000/api/movies
```

### Get single movie with reviews (public):
```bash
curl http://localhost:8000/api/movies/1
```

### Create a movie (admin only):
```bash
curl -X POST http://localhost:8000/api/movies \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -d '{
    "title": "The Matrix",
    "description": "A sci-fi masterpiece",
    "year": 1999,
    "director": "The Wachowskis"
  }'
```

### Add a review (authenticated):
```bash
curl -X POST http://localhost:8000/api/reviews \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -d '{
    "movie_id": 1,
    "rating": 9.5,
    "comment": "Amazing film!"
  }'
```

## Key Differences from Slim (PHP)

| Feature | FastAPI | Slim |
|---------|---------|------|
| **Type Hints** | Built-in, enforced | Manual (PHP 7.4+) |
| **Auto Docs** | Automatic Swagger UI | Manual setup |
| **Validation** | Pydantic (built-in) | Manual or 3rd party |
| **Async** | Native async/await | Possible but not native |
| **Learning Curve** | Steeper (but very rewarding) | Gentler |
| **Performance** | Very fast (async) | Fast (sync) |
| **Ecosystem** | Smaller but growing | More established |

## Why FastAPI is Great

✅ **Automatic interactive documentation** - Swagger UI out of the box  
✅ **Type safety** - Catches errors before runtime  
✅ **Built-in validation** - Pydantic handles all input validation  
✅ **Async by default** - Better performance under load  
✅ **Fast development** - Less boilerplate than traditional frameworks  
✅ **Modern Python** - Uses latest Python features (3.7+)  
✅ **Easy testing** - TestClient built-in  
✅ **Great error messages** - Clear validation feedback

Would you like me to show you how to add testing, deploy this, or explain any specific part in more detail?
