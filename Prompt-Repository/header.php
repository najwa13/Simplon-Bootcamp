<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DevGenius Solutions</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
       
        .nav-link:hover {
            opacity: 0.7; 
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm py-3">
  <div class="container">
    
    <a class="navbar-brand fw-bold" href="user_index.php">
    <span>DevGenius <span class="fw-light opacity-75">Solutions</span></span>
    </a>

    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center">
        
        <li class="nav-item">
          <a class="nav-link text-white px-3" href="user_index.php">
            <i class="bi bi-grid-1x2-fill me-1 small"></i>Mon Espace
          </a>
        </li>

        <li class="nav-item ms-lg-3">
          <a class="btn btn-danger btn-sm px-4 fw-bold rounded-pill" href="logout.php">
            <i class="bi bi-box-arrow-right me-1"></i>Logout
          </a>
        </li>
        
      </ul>
    </div>
  </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>