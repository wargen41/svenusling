<?php $person = $data['person']; ?>

<div class="row">
    <div class="col-md-3">
        <?php if ($person['poster_image']): ?>
            <img src="<?php echo htmlspecialchars($person['poster_image']['file_directory']); ?>/<?php echo htmlspecialchars($person['poster_image']['file_name']); ?>" 
                 class="img-fluid rounded" alt="<?php echo htmlspecialchars($person['name']); ?>">
        <?php else: ?>
            <div class="bg-light rounded p-5 text-center text-muted">
                No Image Available
            </div>
        <?php endif; ?>
    </div>
    
    <div class="col-md-9">
        <h1><?php echo htmlspecialchars($person['name']); ?></h1>
        
        <div class="mb-3">
            <span class="badge bg-secondary"><?php echo htmlspecialchars(ucfirst($person['category'])); ?></span>
        </div>
        
        <div class="row mb-4">
            <?php if ($person['birth_date']): ?>
                <div class="col-md-6">
                    <strong>Birth Date:</strong> <?php echo htmlspecialchars($person['birth_date']); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($person['death_date']): ?>
                <div class="col-md-6">
                    <strong>Death Date:</strong> <?php echo htmlspecialchars($person['death_date']); ?>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if (!empty($person['trivia'])): ?>
            <div class="mb-4">
                <h4>Trivia</h4>
                <ul>
                    <?php foreach ($person['trivia'] as $item): ?>
                        <li>
                            <?php 
                            $text = $item['en'] ?? $item['sv'] ?? '';
                            echo htmlspecialchars($text);
                            ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($person['filmography'])): ?>
            <div class="mb-4">
                <h4>Filmography (<?php echo count($person['filmography']); ?> titles)</h4>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Year</th>
                                <th>Type</th>
                                <th>Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($person['filmography'] as $film): ?>
                                <tr>
                                    <td>
                                        <a href="?page=movie&id=<?php echo $film['id']; ?>">
                                            <?php echo htmlspecialchars($film['title']); ?>
                                        </a>
                                    </td>
                                    <td><?php echo htmlspecialchars($film['year'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars(ucfirst($film['type'] ?? 'Unknown')); ?></td>
                                    <td><?php echo htmlspecialchars($film['role_name'] ?? '-'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($person['relations'])): ?>
            <div class="mb-4">
                <h4>Relations</h4>
                <ul>
                    <?php foreach ($person['relations'] as $relation): ?>
                        <li>
                            <strong><?php echo htmlspecialchars($relation['en'] ?? $relation['sv']); ?>:</strong>
                            <?php echo htmlspecialchars($relation['person_2_name'] ?? 'Unknown'); ?>
                            <?php if ($relation['date_1'] || $relation['date_2']): ?>
                                <small class="text-muted">(<?php echo htmlspecialchars($relation['date_1']); ?> - <?php echo htmlspecialchars($relation['date_2']); ?>)</small>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</div>