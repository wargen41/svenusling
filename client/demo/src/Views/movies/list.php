<div class="row mb-4">
    <div class="col">
        <h1><?php echo htmlspecialchars($data['title']); ?></h1>
    </div>
</div>

<?php if ($data['error']): ?>
    <div class="alert alert-danger" role="alert">
        Error: <?php echo htmlspecialchars($data['error']); ?>
    </div>
<?php endif; ?>

<div class="row mb-4">
    <div class="col-md-3">
        <form method="GET" action="">
            <input type="hidden" name="page" value="movies">
            
            <div class="mb-3">
                <label class="form-label">Type</label>
                <select name="type" class="form-select">
                    <option value="">All Types</option>
                    <option value="film" <?php echo ($_GET['type'] ?? '') === 'film' ? 'selected' : ''; ?>>Film</option>
                    <option value="series" <?php echo ($_GET['type'] ?? '') === 'series' ? 'selected' : ''; ?>>Series</option>
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
            
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </form>
    </div>
    
    <div class="col-md-9">
        <?php if (empty($data['movies'])): ?>
            <div class="alert alert-info">No movies found</div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($data['movies'] as $movie): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100">
                            <?php if ($movie['poster_image_id']): ?>
                                <div class="card-img-top" style="height: 200px; background: #eee; display: flex; align-items: center; justify-content: center;">
                                    <span>No Image</span>
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
                                    <?php echo htmlspecialchars($movie['type'] ?? 'Unknown'); ?>
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-primary">★ <?php echo htmlspecialchars($movie['rating']); ?></span>
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
        
        <?php if (!empty($data['pagination'])): ?>
            <nav aria-label="Page navigation" class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php 
                    $current_page = (int)(($_GET['skip'] ?? 0) / ($_GET['limit'] ?? 10)) + 1;
                    $total_pages = ceil($data['pagination']['total'] / ($_GET['limit'] ?? 10));
                    
                    for ($i = 1; $i <= $total_pages; $i++):
                        $skip = ($i - 1) * ($_GET['limit'] ?? 10);
                        $class = $i === $current_page ? 'active' : '';
                    ?>
                        <li class="page-item <?php echo $class; ?>">
                            <a class="page-link" href="?page=movies&skip=<?php echo $skip; ?>&limit=<?php echo $_GET['limit'] ?? 10; ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>