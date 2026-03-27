<?php 
require 'db.php'; 

$username='';
$email='';
$error='';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']); 
    $password_confirm = trim($_POST['password_confirm']);
   

    if(empty($username)||empty($email)||empty($password)||empty($password_confirm)){

        $error="Veuillez remplir tous les champs!"; 

    }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){

        $error="Email invalide!";

    } elseif($password !== $password_confirm){
        $error = "Les mots de passe ne correspondent pas !";
    }else{
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if($stmt->fetch()){
            $error = "Nom d'utilisateur ou email déjà utilisé !";
        
        }else{
         $password_hash = password_hash($password, PASSWORD_DEFAULT);
         $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
         $stmt = $pdo->prepare($sql);
         $stmt->execute([$username, $email, $password_hash]);

    
    header('Location: login.php');
    exit();
    }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Inscription</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-primary bg-gradient">

<div class="container d-flex flex-column vh-100 justify-content-center align-items-center">

    <div class="card shadow-lg border-0" style="max-width: 420px; width:100%; border-radius: 18px; overflow:hidden;">

        <!-- Header de la card -->
        <div class="text-center p-4 border-bottom border-2 border-light-subtle">
            <h4 class="text-primary fw-bold mb-1">DevGenius Solutions</h4>
            <small class="text-muted">Créez votre compte</small>
        </div>

        <!-- Body de la card -->
        <div class="card-body p-4">

            <?php if($error): ?>
                <div class="alert alert-danger text-center py-2">
                    <?= htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST">

                <div class="form-floating mb-3">
                    <input type="text" name="username" class="form-control" placeholder="Nom d'utilisateur" value="<?= htmlspecialchars($username) ?>" required>
                    <label>Nom d'utilisateur</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email" value="<?= htmlspecialchars($email) ?>" required>
                    <label>Email</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Mot de passe" required>
                    <label>Mot de passe</label>
                </div>

                <div class="form-floating mb-4">
                    <input type="password" name="password_confirm" class="form-control" placeholder="Confirmer mot de passe" required>
                    <label>Confirmer le mot de passe</label>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm">
                    S'inscrire
                </button>

            </form>

            <hr>

            <p class="text-center mb-0">
                Déjà inscrit ?
                <a href="login.php" class="text-primary fw-semibold text-decoration-none">
                    Se connecter
                </a>
            </p>

        </div>

    </div>

   

</div>

</body>
</html>