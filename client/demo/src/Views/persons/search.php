<div class="row mb-4">
    <div class="col">
        <h1><?php echo htmlspecialchars($data['title']); ?></h1>
    </div>
</div>

<form method="GET" action="" class="mb-4">
    <input type="hidden" name="page" value="search">
    <div class="input-group">
        <input type="text" name="q" class="form-control" placeholder="Search persons..." value="<?php echo htmlspecialchars($data['search']); ?>">
        <button class="btn btn-primary" type="submit">Search</button>
    </div>
</form>

<?php if ($data['error']): ?>
    <div class="alert alert-danger" role="alert">
        Error: <?php echo htmlspecialchars($data['error']); ?>
    </div>
<?php elseif ($data['results'] === null): ?>
    <div class="alert alert-info">Enter a search term to find persons</div>
<?php elseif (empty($data['results'])): ?>
    <div class="alert alert-warning">No persons found matching "<?php echo htmlspecialchars($data['search']); ?>"</div>
<?php else: ?>
    <div class="row g-4">
        <?php foreach ($data['results'] as $person): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100">
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