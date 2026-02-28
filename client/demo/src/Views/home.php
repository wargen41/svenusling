<div class="row mb-5">
    <div class="col">
        <div class="jumbotron bg-light p-5 rounded">
            <h1 class="display-4">Welcome to Movie Database</h1>
            <p class="lead">Explore movies, TV series, and discover amazing entertainment</p>
            <hr class="my-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <a href="?page=movies" class="btn btn-primary btn-lg w-100">Browse Movies</a>
                </div>
                <div class="col-md-6">
                    <a href="?page=persons" class="btn btn-secondary btn-lg w-100">Browse Persons</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-5">
    <div class="col">
        <h2>Quick Links</h2>
        <div class="row g-3">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">🎬 All Movies</h5>
                        <p class="card-text">Browse our complete collection of films and TV series</p>
                        <a href="?page=movies" class="btn btn-outline-primary">View Movies</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">👥 All Persons</h5>
                        <p class="card-text">Discover actors, directors, and other entertainment professionals</p>
                        <a href="?page=persons" class="btn btn-outline-primary">View Persons</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">🔍 Advanced Search</h5>
                        <p class="card-text">Use filters to find exactly what you're looking for</p>
                        <a href="?page=movies" class="btn btn-outline-primary">Search Movies</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($data['featured'])): ?>
    <div class="row mb-5">
        <div class="col">
            <h2>Featured Movies</h2>
            <div class="row g-4">
                <?php foreach ($data['featured'] as $movie): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm">
                            <?php if ($movie['poster_image_id']): ?>
                                <div class="card-img-top" style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white;">
                                    <span>🎬</span>
                                </div>
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="?page=movie&id=<?php echo $movie['id']; ?>" class="text-decoration-none">
                                        <?php echo htmlspecialchars($movie['title']); ?>
                                    </a>
                                </h5>
                                <p class="card-text text-muted">
                                    <?php echo htmlspecialchars($movie['year'] ?? 'N/A'); ?> • 
                                    <?php echo htmlspecialchars(ucfirst($movie['type'] ?? 'Unknown')); ?>
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-primary">★ <?php echo htmlspecialchars($movie['rating']); ?>/10</span>
                                    <a href="?page=movie&id=<?php echo $movie['id']; ?>" class="btn btn-sm btn-outline-primary">
                                        View
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col">
        <div class="card bg-light">
            <div class="card-body">
                <h5 class="card-title">About This Database</h5>
                <p class="card-text">
                    This is a comprehensive movie and entertainment database featuring detailed information about 
                    films, television series, actors, directors, and more. Explore our collection to discover new 
                    entertainment and learn about your favorite creators and performers.
                </p>
                <p class="card-text text-muted">
                    Use the search bar at the top to find specific movies or persons, or browse by category using our filters.
                </p>
            </div>
        </div>
    </div>
</div>