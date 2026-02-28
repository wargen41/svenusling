<div class="row mb-4">
    <div class="col">
        <h1><?php echo htmlspecialchars($data['title']); ?></h1>
    </div>
</div>

<form method="GET" action="" class="mb-4">
    <input type="hidden" name="page" value="search">
    <div class="input-group input-group-lg">
        <input type="text" name="q" class="form-control" placeholder="Search movies..." value="<?php echo htmlspecialchars($data['search']); ?>">
        <button class="btn btn-primary" type="submit">Search</button>
    </div>
</form>

<div class="row mb-4">
    <div class="col-md-3">
        <form method="GET" action="">
            <input type="hidden" name="page" value="search">
            <input type="hidden" name="q" value="<?php echo htmlspecialchars($data['search']); ?>">
            
            <div class="mb-3">
                <label class="form-label">Type</label>
                <select name="type" class="form-select">
                    <option value="">All Types</option>
                    <option value="film" <?php echo ($_GET['type'] ?? '') === 'film' ? 'selected' : ''; ?>>Film</option>
                    <option value="series" <?php echo ($_GET['type'] ?? '') === 'series' ? 'selected' : ''; ?>>Series</option>
                    <option value="miniseries" <?php echo ($_GET['type'] ?? '') === 'miniseries' ? 'selected' : ''; ?>>Miniseries</option>
                    <option value="episode" <?php echo ($_GET['type'] ?? '') === 'episode' ? 'selected' : ''; ?>>Episode</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Year</label>
                <input type="number" name="year" class="form-control" placeholder="e.g., 2023" value="<?php echo htmlspecialchars($_GET['year'] ?? ''); ?>">
            </div>
            
            <div class="mb-3">
                <label class="form-label">Minimum Rating</label>
                <input type="number" name="rating" class="form-control" min="0" max="10" step="0.5" value="<?php echo htmlspecialchars($_GET['rating'] ?? ''); ?>">
            </div>
            
            <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
            <a href="?page=search&q=<?php echo urlencode($data['search']); ?>" class="btn btn-outline-secondary w-100 mt-2">Clear Filters</a>
        </form>
    </div>
    
    <div class="col-md-9">
        <?php if ($data['results'] === null): ?>
            <div class="alert alert-info">
                Enter a search term above to find movies
            </div>
        <?php elseif ($data['error']): ?>
            <div class="alert alert-danger" role="alert">
                Error: <?php echo htmlspecialchars($data['error']); ?>
            </div>
        <?php elseif (empty($data['results'])): ?>
            <div class="alert alert-warning">
                No movies found matching "<?php echo htmlspecialchars($data['search']); ?>"
            </div>
        <?php else: ?>
            <div class="mb-3">
                <p class="text-muted">
                    Found <?php echo count($data['results']); ?> movie(s) matching "<?php echo htmlspecialchars($data['search']); ?>"
                </p>
            </div>
            
            <div class="row g-4">
                <?php foreach ($data['results'] as $movie): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm">
                            <?php if ($movie['poster_image_id']): ?>
                                <div class="card-img-top" style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem;">
                                    🎬
                                </div>
                            <?php else: ?>
                                <div class="card-img-top" style="height: 200px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; color: #999;">
                                    No Image
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
        <?php endif; ?>
    </div>
</div>