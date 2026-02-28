<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client;

class MovieDisplay {
    private $api;
    
    public function __construct($baseUrl) {
        $this->api = new Client([
            'base_uri' => $baseUrl,
            'timeout' => 10
        ]);
    }
    
    public function displayMovie($movieId) {
        try {
            $response = $this->api->get("/api/movies/$movieId");
            $movie = json_decode($response->getBody(), true)['data'];
            
            ?>
            <div class="movie">
                <h1><?php echo htmlspecialchars($movie['title']); ?></h1>
                
                <?php if ($movie['poster_image']): ?>
                    <img src="<?php echo htmlspecialchars($movie['poster_image']['file_directory']); ?>/<?php echo htmlspecialchars($movie['poster_image']['file_name']); ?>" 
                         alt="<?php echo htmlspecialchars($movie['title']); ?>">
                <?php endif; ?>
                
                <p><strong>Year:</strong> <?php echo htmlspecialchars($movie['year']); ?></p>
                <p><strong>Rating:</strong> <?php echo htmlspecialchars($movie['rating']); ?>/10</p>
                <p><strong>Description:</strong> <?php echo htmlspecialchars($movie['description']); ?></p>
                
                <?php if (!empty($movie['genres'])): ?>
                    <div class="genres">
                        <strong>Genres:</strong>
                        <?php foreach ($movie['genres'] as $genre): ?>
                            <span class="badge"><?php echo htmlspecialchars($genre['en']); ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($movie['persons']['director'])): ?>
                    <div class="directors">
                        <strong>Directors:</strong>
                        <ul>
                            <?php foreach ($movie['persons']['director'] as $director): ?>
                                <li><?php echo htmlspecialchars($director['name']); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($movie['persons']['actor'])): ?>
                    <div class="cast">
                        <strong>Cast:</strong>
                        <ul>
                            <?php foreach (array_slice($movie['persons']['actor'], 0, 10) as $actor): ?>
                                <li>
                                    <?php echo htmlspecialchars($actor['name']); ?>
                                    <?php if ($actor['role_name']): ?>
                                        as <?php echo htmlspecialchars($actor['role_name']); ?>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($movie['reviews'])): ?>
                    <div class="reviews">
                        <strong>Reviews:</strong>
                        <ul>
                            <?php foreach ($movie['reviews'] as $review): ?>
                                <li>
                                    <strong><?php echo htmlspecialchars($review['username']); ?></strong>
                                    (<?php echo htmlspecialchars($review['rating']); ?>/10):
                                    <?php echo htmlspecialchars($review['comment']); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
            <?php
            
        } catch (Exception $e) {
            echo "Error: " . htmlspecialchars($e->getMessage());
        }
    }
}

// Usage
$display = new MovieDisplay('http://localhost:8000');
$display->displayMovie(1);