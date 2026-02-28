<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($data['title']); ?> - Movie Database</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="?page=home">🎬 Movie Database</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="?page=home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?page=movies">Movies</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?page=persons">Persons</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="search-bar bg-light py-3">
        <div class="container">
            <form method="GET" action="" class="d-flex gap-2">
                <input type="hidden" name="page" value="search">
                <input type="text" name="q" class="form-control" placeholder="Search movies..." value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
    </div>

    <main class="container py-4">
        <?php include "../src/Views/" . $data['view'] . ".php"; ?>
    </main>

    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p>&copy; 2026 Movie Database. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/js/app.js"></script>
</body>
</html>