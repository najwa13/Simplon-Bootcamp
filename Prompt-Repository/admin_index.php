<?php
session_start();
require 'db.php';
include 'header.php';

if(!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Ajouter une catégorie
if(isset($_POST['add_category'])){
    $name = trim($_POST['name']);
    if(!empty($name)){
        $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->execute([$name]);
        header("Location: admin_index.php");
        exit();
    }
}

// Supprimer une catégorie
if(isset($_GET['delete_category'])){
    $id = intval($_GET['delete_category']);
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id=?");
    $stmt->execute([$id]);
    header("Location: admin_index.php");
    exit();
}

// Récupérer toutes les catégories et utilisateurs
$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
$users = $pdo->query("SELECT id, username FROM users ORDER BY username ASC")->fetchAll(PDO::FETCH_ASSOC);

// Requête pour afficher tous les prompts avec les noms
$stmt = $pdo->query("
    SELECT p.*, c.name AS category_name, u.username 
    FROM prompts p
    INNER JOIN categories c ON c.id = p.category_id
    INNER JOIN users u ON u.id = p.user_id
    ORDER BY p.id DESC
");
$prompts = $stmt->fetchAll(PDO::FETCH_ASSOC);
$total_prompts = count($prompts);

// Top contributeurs
$users_stats = $pdo->query("
    SELECT u.username, COUNT(*) AS total_prompts 
    FROM users u 
    INNER JOIN prompts p ON u.id = p.user_id
    GROUP BY u.id
    ORDER BY total_prompts DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container py-4">
    <h2 class="fw-bold text-primary mb-3">Dashboard Admin</h2>

    <div class="row g-3">

        <!-- Sidebar -->
        <div class="col-md-4 d-flex flex-column gap-3">

            <!-- Gestion Catégories -->
            <div class="card p-3 shadow-sm">
                <h6>Gestion des Catégories</h6>
                <form method="POST" class="d-flex gap-2 mb-3">
                    <input type="text" name="name" class="form-control" placeholder="Ajouter..." required>
                    <button name="add_category" class="btn btn-primary">+</button>
                </form>
                <div class="d-flex flex-column gap-2 overflow-auto" style="max-height: 250px;">
                    <?php foreach($categories as $c): ?>
                        <div class="d-flex justify-content-between align-items-center px-2 py-1 rounded bg-light">
                            <span><?= htmlspecialchars($c['name']) ?></span>
                            <a href="?delete_category=<?= $c['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cette catégorie ?')">
                                <i class="bi bi-trash-fill"></i>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Top Contributeurs -->
            <div class="card p-3 shadow-sm">
                <h6>Top Contributeurs</h6>
                <div class="d-flex flex-column gap-2">
                    <?php foreach(array_slice($users_stats,0,5) as $u): ?>
                        <div class="d-flex justify-content-between">
                            <span><?= htmlspecialchars($u['username']) ?></span>
                            <span class="badge bg-primary"><?= $u['total_prompts'] ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Total Prompts -->
            <div class="card p-3 shadow-sm text-center">
                <h6>Total Prompts</h6>
                <h3><?= $total_prompts ?></h3>
            </div>

        </div>

        <!-- Prompts -->
        <div class="col-md-8">
            <div class="card p-3 shadow-sm">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Titre</th>
                            <th>Catégorie</th>
                            <th>Auteur</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($prompts as $p): ?>
                            <tr>
                                <td><?= htmlspecialchars($p['title']) ?></td>
                                <td><?= htmlspecialchars($p['category_name']) ?></td>
                                <td><?= htmlspecialchars($p['username']) ?></td>
                                <td>
                                    <!-- Bouton Read -->
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#readModal<?= $p['id'] ?>"><i class="bi bi-eye-fill"></i></button>
                                </td>
                            </tr>

                            <!-- Modal Read -->
                            <div class="modal fade" id="readModal<?= $p['id'] ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title"><?= htmlspecialchars($p['title']) ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p><?= htmlspecialchars($p['content']) ?></p>
                                            <hr>
                                            <p><strong>Catégorie:</strong> <?= htmlspecialchars($p['category_name']) ?></p>
                                            <p><strong>Auteur:</strong> <?= htmlspecialchars($p['username']) ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <?php if(empty($prompts)): ?>
                            <tr><td colspan="4" class="text-center">Aucun prompt trouvé.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>