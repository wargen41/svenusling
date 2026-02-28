<?php $series = $data['series']; ?>

<div class="row">
    <div class="col-md-3">
        <?php if ($series['poster_image']): ?>
            <img src="<?php echo htmlspecialchars($series['poster_image']['file_directory']); ?>/<?php echo htmlspecialchars($series['poster_image']['file_name']); ?>" 
                 class="img-fluid rounded" alt="<?php echo htmlspecialchars($series['title']); ?>">
        <?php else: ?>
            <div class="bg-light rounded p-5 text-center text-muted">
                No Image Available
            </div>
        <?php endif; ?>
    </div>
    
    <div class="col-md-9">
        <h1><?php echo htmlspecialchars($series['title']); ?></h1>
        
        <div class="mb-3">
            <span class="badge bg-primary">★ <?php echo htmlspecialchars($series['rating']); ?>/10</span>
            <span class="badge bg-secondary"><?php echo htmlspecialchars(ucfirst($series['type'])); ?></span>
            <span class="badge bg-info"><?php echo htmlspecialchars($series['year']); ?></span>
        </div>
        
        <div class="mb-4">
            <p><?php echo htmlspecialchars($series['description']); ?></p>
        </div>
        
        <?php if (!empty($series['genres'])): ?>
            <div class="mb-3">
                <strong>Genres:</strong>
                <?php foreach ($series['genres'] as $genre): ?>
                    <span class="badge bg-light text-dark"><?php echo htmlspecialchars($genre['en']); ?></span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($series['persons']['director'])): ?>
            <div class="mb-3">
                <strong>Creators/Directors:</strong>
                <ul>
                    <?php foreach ($series['persons']['director'] as $director): ?>
                        <li>
                            <a href="?page=person&id=<?php echo $director['id']; ?>">
                                <?php echo htmlspecialchars($director['name']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php if (!empty($series['seasons'])): ?>
    <div class="row mt-5">
        <div class="col">
            <h2>Seasons (<?php echo count($series['seasons']); ?>)</h2>
            
            <div class="accordion" id="seasonsAccordion">
                <?php foreach ($series['seasons'] as $index => $season): ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button <?php echo $index === 0 ? '' : 'collapsed'; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#season<?php echo $season['id']; ?>">
                                Season <?php echo htmlspecialchars($season['sequence_number']); ?>: <?php echo htmlspecialchars($season['title']); ?>
                                <span class="badge bg-secondary ms-2"><?php echo count($season['episodes']); ?> episodes</span>
                            </button>
                        </h2>
                        <div id="season<?php echo $season['id']; ?>" class="accordion-collapse collapse <?php echo $index === 0 ? 'show' : ''; ?>" data-bs-parent="#seasonsAccordion">
                            <div class="accordion-body">
                                <?php if (!empty($season['episodes'])): ?>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Ep</th>
                                                <th>Title</th>
                                                <th>Year</th>
                                                <th>Rating</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($season['episodes'] as $episode): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($episode['sequence_number']); ?></td>
                                                    <td><?php echo htmlspecialchars($episode['title']); ?></td>
                                                    <td><?php echo htmlspecialchars($episode['year'] ?? '-'); ?></td>
                                                    <td>★ <?php echo htmlspecialchars($episode['rating']); ?></td>
                                                    <td>
                                                        <a href="?page=movie&id=<?php echo $episode['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                            View
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php else: ?>
                                    <p class="text-muted">No episodes available</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>