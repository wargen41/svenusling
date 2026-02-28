<?php $season = $data['season']; ?>

<div class="row">
    <div class="col">
        <?php if ($season['series']): ?>
            <a href="?page=series&id=<?php echo $season['series']['id']; ?>" class="btn btn-outline-secondary mb-3">
                ← Back to <?php echo htmlspecialchars($season['series']['title']); ?>
            </a>
        <?php endif; ?>
        
        <h1>
            Season <?php echo htmlspecialchars($season['sequence_number']); ?>: 
            <?php echo htmlspecialchars($season['title']); ?>
        </h1>
        
        <div class="mb-3">
            <span class="badge bg-primary">★ <?php echo htmlspecialchars($season['rating']); ?>/10</span>
            <span class="badge bg-info"><?php echo htmlspecialchars($season['year']); ?></span>
        </div>
        
        <div class="mb-4">
            <p><?php echo htmlspecialchars($season['description']); ?></p>
        </div>
    </div>
</div>

<?php if (!empty($season['episodes'])): ?>
    <div class="row mt-4">
        <div class="col">
            <h2>Episodes (<?php echo count($season['episodes']); ?>)</h2>
            
            <div class="row g-4">
                <?php foreach ($season['episodes'] as $episode): ?>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">
                                    Episode <?php echo htmlspecialchars($episode['sequence_number']); ?>: 
                                    <a href="?page=movie&id=<?php echo $episode['id']; ?>">
                                        <?php echo htmlspecialchars($episode['title']); ?>
                                    </a>
                                </h5>
                                <p class="card-text text-muted">
                                    <?php echo htmlspecialchars($episode['year'] ?? 'N/A'); ?>
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-primary">★ <?php echo htmlspecialchars($episode['rating']); ?>/10</span>
                                    <a href="?page=movie&id=<?php echo $episode['id']; ?>" class="btn btn-sm btn-outline-primary">
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
<?php else: ?>
    <div class="alert alert-info">No episodes available for this season</div>
<?php endif; ?>