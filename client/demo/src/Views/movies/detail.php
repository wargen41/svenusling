<?php $movie = $data['movie']; ?>

<div class="row">
    <div class="col-md-3">
        <?php if ($movie['poster_image']): ?>
            <img src="<?php echo htmlspecialchars($movie['poster_image']['file_directory']); ?>/<?php echo htmlspecialchars($movie['poster_image']['file_name']); ?>" 
                 class="img-fluid rounded" alt="<?php echo htmlspecialchars($movie['title']); ?>">
        <?php else: ?>
            <div class="bg-light rounded p-5 text-center text-muted">
                No Image Available
            </div>
        <?php endif; ?>
    </div>
    
    <div class="col-md-9">
        <h1><?php echo htmlspecialchars($movie['title']); ?></h1>
        
        <?php if ($movie['original_title'] && $movie['original_title'] !== $movie['title']): ?>
            <p class="text-muted"><?php echo htmlspecialchars($movie['original_title']); ?></p>
        <?php endif; ?>
        
        <div class="mb-3">
            <span class="badge bg-primary">★ <?php echo htmlspecialchars($movie['rating']); ?></span>
            <span class="badge bg-secondary"><?php echo htmlspecialchars($movie['type']); ?></span>
            <span class="badge bg-info"><?php echo htmlspecialchars($movie['year']); ?></span>
        </div>
        
        <div class="mb-4">
            <p><?php echo htmlspecialchars($movie['description']); ?></p>
        </div>
        
        <?php if (!empty($movie['genres'])): ?>
            <div class="mb-3">
                <strong>Genres:</strong>
                <?php foreach ($movie['genres'] as $genre): ?>
                    <span class="badge bg-light text-dark"><?php echo htmlspecialchars($genre['en']); ?></span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($movie['persons']['director'])): ?>
            <div class="mb-3">
                <strong>Directors:</strong>
                <ul>
                    <?php foreach ($movie['persons']['director'] as $director): ?>
                        <li>
                            <a href="?page=person&id=<?php echo $director['id']; ?>">
                                <?php echo htmlspecialchars($director['name']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($movie['persons']['actor'])): ?>
            <div class="mb-3">
                <strong>Cast:</strong>
                <div class="row">
                    <?php foreach (array_slice($movie['persons']['actor'], 0, 10) as $actor): ?>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <a href="?page=person&id=<?php echo $actor['id']; ?>">
                                    <?php echo htmlspecialchars($actor['name']); ?>
                                </a>
                                <?php if ($actor['role_name']): ?>
                                    <br><small class="text-muted">as <?php echo htmlspecialchars($actor['role_name']); ?></small>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($movie['reviews'])): ?>
            <div class="mt-4">
                <h4>Reviews</h4>
                <?php foreach ($movie['reviews'] as $review): ?>
                    <div class="card mb-2">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <strong><?php echo htmlspecialchars($review['username']); ?></strong>
                                <span class="badge bg-primary">★ <?php echo htmlspecialchars($review['rating']); ?></span>
                            </div>
                            <p class="card-text mt-2"><?php echo htmlspecialchars($review['comment']); ?></p>
                            <small class="text-muted"><?php echo htmlspecialchars($review['created_at']); ?></small>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>