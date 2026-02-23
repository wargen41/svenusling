<?php
namespace App;

use App\Controllers\GenreController;
use App\Controllers\MovieController;
use App\Controllers\SeriesController;
use App\Controllers\PersonController;
use App\Controllers\ReviewController;
use App\Controllers\MediaController;
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

        // Genre routes (public read)
        $app->get('/api/genres', [GenreController::class, 'listGenres']);
        $app->get('/api/genres/{id}', [GenreController::class, 'getGenre']);

        // Genre routes (protected - admin only)
        $app->post('/api/genres', [GenreController::class, 'createGenre'])
            ->add(new AuthMiddleware());
        $app->put('/api/genres/{id}', [GenreController::class, 'updateGenre'])
            ->add(new AuthMiddleware());
        $app->delete('/api/genres/{id}', [GenreController::class, 'deleteGenre'])
            ->add(new AuthMiddleware());

        // Movie routes (public read)
        $app->get('/api/movies', [MovieController::class, 'listMovies']);
        $app->get('/api/movies/{id}', [MovieController::class, 'getMovie']);
        $app->get('/api/series/{id}', [SeriesController::class, 'getSeries']);
        $app->get('/api/season/{id}', [SeriesController::class, 'getSeason']);

        // Movie routes (protected - admin only)
        $app->post('/api/movies', [MovieController::class, 'createMovie'])
            ->add(new AuthMiddleware());
        $app->put('/api/movies/{id}', [MovieController::class, 'updateMovie'])
            ->add(new AuthMiddleware());
        $app->delete('/api/movies/{id}', [MovieController::class, 'deleteMovie'])
            ->add(new AuthMiddleware());

        // Person routes (public read)
        $app->get('/api/persons', [PersonController::class, 'listPersons']);
        $app->get('/api/persons/{id}', [PersonController::class, 'getPerson']);

        // Person routes (protected - admin only)
        $app->post('/api/persons', [PersonController::class, 'createPerson'])
            ->add(new AuthMiddleware());
        $app->put('/api/persons/{id}', [PersonController::class, 'updatePerson'])
            ->add(new AuthMiddleware());
        $app->delete('/api/persons/{id}', [PersonController::class, 'deletePerson'])
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

        // Media routes (public read)
        $app->get('/api/media', [MediaController::class, 'listMedia']);
        $app->get('/api/media/{id}', [MediaController::class, 'getMedia']);

        // Media routes (protected - admin only)
        $app->post('/api/media', [MediaController::class, 'createMedia'])
            ->add(new AuthMiddleware());
        $app->put('/api/media/{id}', [MediaController::class, 'updateMedia'])
            ->add(new AuthMiddleware());
        $app->delete('/api/media/{id}', [MediaController::class, 'deleteMedia'])
            ->add(new AuthMiddleware());
    }
}
