<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="alert alert-danger" role="alert">
            <h4 class="alert-heading">Error</h4>
            <p><?php echo htmlspecialchars($data['error'] ?? 'An unknown error occurred'); ?></p>
            <hr>
            <p class="mb-0">
                <a href="?page=home" class="btn btn-primary">Go to Home</a>
                <a href="?page=movies" class="btn btn-secondary">Browse Movies</a>
            </p>
        </div>
    </div>
</div>