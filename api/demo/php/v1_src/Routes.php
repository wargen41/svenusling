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