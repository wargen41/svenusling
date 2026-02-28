<?php
namespace App;

use App\Controllers\GenreController;
use App\Controllers\MovieController;
use App\Controllers\SeriesController;
use App\Controllers\PersonController;
use App\Controllers\AwardsController;
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

        // Genre routes
        $app->get('/api/genres', [GenreController::class, 'listGenres']);
        $app->get('/api/genres/{id}', [GenreController::class, 'getGenre']);
        $app->post('/api/genres', [GenreController::class, 'createGenre'])->add(new AuthMiddleware());
        $app->put('/api/genres/{id}', [GenreController::class, 'updateGenre'])->add(new AuthMiddleware());
        $app->delete('/api/genres/{id}', [GenreController::class, 'deleteGenre'])->add(new AuthMiddleware());

        // Movie routes
        $app->get('/api/movies', [MovieController::class, 'listMovies']);
        $app->get('/api/movies/{id}', [MovieController::class, 'getMovie']);
        $app->get('/api/series/{id}', [SeriesController::class, 'getSeries']);
        $app->get('/api/season/{id}', [SeriesController::class, 'getSeason']);
        $app->post('/api/movies', [MovieController::class, 'createMovie'])->add(new AuthMiddleware());
        $app->put('/api/movies/{id}', [MovieController::class, 'updateMovie'])->add(new AuthMiddleware());
        $app->delete('/api/movies/{id}', [MovieController::class, 'deleteMovie'])->add(new AuthMiddleware());

        // Person routes
        $app->get('/api/persons', [PersonController::class, 'listPersons']);
        $app->get('/api/persons/{id}', [PersonController::class, 'getPerson']);
        $app->post('/api/persons', [PersonController::class, 'createPerson'])->add(new AuthMiddleware());
        $app->put('/api/persons/{id}', [PersonController::class, 'updatePerson'])->add(new AuthMiddleware());
        $app->delete('/api/persons/{id}', [PersonController::class, 'deletePerson'])->add(new AuthMiddleware());

        // Awards endpoints
        $app->get('/api/awards', [AwardsController::class, 'listAwards']);
        $app->get('/api/awards/{id}', [AwardsController::class, 'getAward']);
        $app->post('/api/awards', [AwardsController::class, 'createAward'])->add(AuthMiddleware::class);
        $app->put('/api/awards/{id}', [AwardsController::class, 'updateAward'])->add(AuthMiddleware::class);
        $app->delete('/api/awards/{id}', [AwardsController::class, 'deleteAward'])->add(AuthMiddleware::class);

        // Award nominations
        $app->post('/api/awards/nominations/movies', [AwardsController::class, 'addMovieNomination'])->add(AuthMiddleware::class);
        $app->post('/api/awards/nominations/persons', [AwardsController::class, 'addPersonNomination'])->add(AuthMiddleware::class);
        $app->put('/api/awards/{award_id}/movies/{movie_id}', [AwardsController::class, 'updateMovieNomination'])->add(AuthMiddleware::class);
        $app->put('/api/awards/{award_id}/persons/{person_id}', [AwardsController::class, 'updatePersonNomination'])->add(AuthMiddleware::class);
        $app->delete('/api/awards/{award_id}/movies/{movie_id}', [AwardsController::class, 'deleteMovieNomination'])->add(AuthMiddleware::class);
        $app->delete('/api/awards/{award_id}/persons/{person_id}', [AwardsController::class, 'deletePersonNomination'])->add(AuthMiddleware::class);

        // Review routes (public read)
        // (Reviews shown with movie details)

        // Review routes (protected - authenticated users)
        $app->post('/api/reviews', [ReviewController::class, 'addReview'])->add(new AuthMiddleware());
        $app->put('/api/reviews/{id}', [ReviewController::class, 'updateReview'])->add(new AuthMiddleware());
        $app->delete('/api/reviews/{id}', [ReviewController::class, 'deleteReview'])->add(new AuthMiddleware());

        // Media routes (public read)
        $app->get('/api/media', [MediaController::class, 'listMedia']);
        $app->get('/api/media/{id}', [MediaController::class, 'getMedia']);

        // Media routes (protected - admin only)
        $app->post('/api/media', [MediaController::class, 'createMedia'])->add(new AuthMiddleware());
        $app->put('/api/media/{id}', [MediaController::class, 'updateMedia'])->add(new AuthMiddleware());
        $app->delete('/api/media/{id}', [MediaController::class, 'deleteMedia'])->add(new AuthMiddleware());
    }
}
