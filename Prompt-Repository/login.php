<?php 
session_start();
require 'db.php';

if (isset($_SESSION['user'])) {
    header("Location: index.php"); 
    exit();
}

$error = '';
$login = ''; 
$password = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login']);
    $password = trim($_POST['password']);

    if ($login === '' || $password === '') {
        $error = "Veuillez remplir tous les champs !";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
        $stmt->execute([$login, $login]);
        $user = $stmt->fetch();

        if ($user && password_verify($password,$user['password'])) {
            $_SESSION['user'] = $user['username'];
            $_SESSION['id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            
            if($_SESSION['role']==='admin'){
            header('Location: admin_index.php');
            exit();
            }else{
            header('Location: user_index.php');
            exit();
            }
        } else {
            if(!$user){
                $error = "Email ou nom d'utilisateur incorrect !";
            }elseif(!password_verify($password, $user['password'])){
                 $error = "mot de passe incorrect !";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-primary bg-gradient">

<div class="container d-flex flex-column vh-100 justify-content-center align-items-center">

    
    <div class="card shadow-lg border-0" style="max-width: 420px; width:100%; border-radius: 18px; overflow:hidden;">

        
       <div class="text-center p-4 border-bottom border-2 border-light-subtle">
            <h4 class="text-primary fw-bold mb-1">DevGenius Solutions</h4>
            <small class="text-muted">Accédez à votre espace</small>
        </div>

        
        <div class="card-body p-4">

            <?php if($error): ?>
                <div class="alert alert-danger text-center py-2">
                    <?= htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST">

                <div class="form-floating mb-3">
                    <input type="text" name="login" class="form-control" 
                    placeholder="Email ou Username"
                    value="<?= htmlspecialchars($login) ?>">
                    <label>Email ou Nom d'utilisateur</label>
                </div>

                <div class="form-floating mb-4">
                    <input type="password" name="password" class="form-control" placeholder="Password">
                    <label>Mot de passe</label>
                </div>

                <button class="btn btn-primary w-100 py-2 fw-bold shadow-sm">
                    Se connecter
                </button>

            </form>

            <hr>

            <p class="text-center mb-0">
                Pas encore inscrit ?
                <a href="register.php" class="text-primary fw-semibold text-decoration-none">
                    Créer un compte
                </a>
            </p>

        </div>

    </div>

    
   

</div>

</body>
</html>