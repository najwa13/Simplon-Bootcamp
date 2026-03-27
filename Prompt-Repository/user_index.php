<?php
session_start();
require 'db.php';
include 'header.php';

if(!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Ajouter un prompt
if(isset($_POST['add_prompt'])){
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $category_id = intval($_POST['category_id']);

    if($title && $content){
        $stmt = $pdo->prepare("INSERT INTO prompts (title, content, category_id, user_id) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $content, $category_id, $_SESSION['user_id']]);
        header("Location: user_index.php");
        exit();
    }
}

// Supprimer un prompt (uniquement si c’est le sien)
if(isset($_GET['delete_prompt'])){
    $id = intval($_GET['delete_prompt']);
    $stmt = $pdo->prepare("DELETE FROM prompts WHERE id=? AND user_id=?");
    $stmt->execute([$id, $_SESSION['user_id']]);
    header("Location: user_index.php");
    exit();
}

// Modifier un prompt
if(isset($_POST['edit_prompt'])){
    $id = intval($_POST['prompt_id']);
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $category_id = intval($_POST['category_id']);

    $stmt = $pdo->prepare("UPDATE prompts SET title=?, content=?, category_id=? WHERE id=? AND user_id=?");
    $stmt->execute([$title, $content, $category_id, $id, $_SESSION['user_id']]);
    header("Location: user_index.php");
    exit();
}

// Récupérer toutes les catégories
$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);

// Récupérer tous les utilisateurs pour affichage
$users = $pdo->query("SELECT id, username FROM users ORDER BY username ASC")->fetchAll(PDO::FETCH_ASSOC);

// Récupérer tous les prompts avec noms des auteurs et catégories
$stmt = $pdo->query("
    SELECT p.*, c.name AS category_name, u.username 
    FROM prompts p
    INNER JOIN categories c ON p.category_id = c.id
    INNER JOIN users u ON p.user_id = u.id
    ORDER BY p.id DESC
");
$prompts = $stmt->fetchAll(PDO::FETCH_ASSOC);
$total_prompts = count($prompts);
?>

<div class="container py-4">
    <h2 class="fw-bold text-primary mb-3">Dashboard Prompts</h2>

    <div class="row g-3">

        <!-- Sidebar -->
        <div class="col-md-4 d-flex flex-column gap-3">

            <!-- Ajouter Prompt -->
            <div class="card p-3 shadow-sm">
                <h6>Ajouter un Prompt</h6>
                <form method="POST" class="d-flex flex-column gap-2">
                    <input type="text" name="title" class="form-control" placeholder="Titre" required>
                    <textarea name="content" class="form-control" placeholder="Contenu" rows="3" required></textarea>
                    <select name="category_id" class="form-select" required>
                        <?php foreach($categories as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button name="add_prompt" class="btn btn-primary mt-2">Ajouter</button>
                </form>
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

                                    <?php if($p['user_id'] == $_SESSION['id']): ?>
                                        <!-- Edit -->
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $p['id'] ?>"><i class="bi bi-pencil-fill"></i></button>
                                        <!-- Delete -->
                                        <a href="?delete_prompt=<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ce prompt ?')">
                                            <i class="bi bi-trash-fill"></i>
                                        </a>
                                    <?php endif; ?>
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

                            <!-- Modal Edit (si c’est le sien) -->
                            <?php if($p['user_id'] == $_SESSION['id']): ?>
                                <div class="modal fade" id="editModal<?= $p['id'] ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="POST">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Modifier Prompt</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="prompt_id" value="<?= $p['id'] ?>">
                                                    <input type="text" name="title" class="form-control mb-2" value="<?= htmlspecialchars($p['title']) ?>" required>
                                                    <textarea name="content" class="form-control mb-2" rows="3" required><?= htmlspecialchars($p['content']) ?></textarea>
                                                    <select name="category_id" class="form-select" required>
                                                        <?php foreach($categories as $c): ?>
                                                            <option value="<?= $c['id'] ?>" <?= ($p['category_id']==$c['id']?'selected':'') ?>>
                                                                <?= htmlspecialchars($c['name']) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" name="edit_prompt" class="btn btn-warning">Modifier</button>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
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

