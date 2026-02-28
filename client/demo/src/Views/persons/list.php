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
            <input type="hidden" name="page" value="persons">
            
            <div class="mb-3">
                <label class="form-label">Category</label>
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    <option value="actor" <?php echo ($_GET['category'] ?? '') === 'actor' ? 'selected' : ''; ?>>Actor</option>
                    <option value="director" <?php echo ($_GET['category'] ?? '') === 'director' ? 'selected' : ''; ?>>Director</option>
                    <option value="voice" <?php echo ($_GET['category'] ?? '') === 'voice' ? 'selected' : ''; ?>>Voice</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </form>
    </div>
    
    <div class="col-md-9">
        <?php if (empty($data['persons'])): ?>
            <div class="alert alert-info">No persons found</div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($data['persons'] as $person): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100">
                            <?php if ($person['poster_image_id']): ?>
                                <div class="card-img-top" style="height: 200px; background: #eee; display: flex; align-items: center; justify-content: center;">
                                    <span>No Image</span>
                                </div>
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="?page=person&id=<?php echo $person['id']; ?>" class="text-decoration-none">
                                        <?php echo htmlspecialchars($person['name']); ?>
                                    </a>
                                </h5>
                                <p class="card-text text-muted">
                                    <?php echo htmlspecialchars(ucfirst($person['category'])); ?>
                                </p>
                                <a href="?page=person&id=<?php echo $person['id']; ?>" class="btn btn-sm btn-outline-primary">
                                    View Profile
                                </a>
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
                            <a class="page-link" href="?page=persons&skip=<?php echo $skip; ?>&limit=<?php echo $_GET['limit'] ?? 10; ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>
