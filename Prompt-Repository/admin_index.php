<?php
session_start();
require 'db.php';
require 'process_prompts.php'; 

// SÉCURITÉ : Vérification du rôle admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
   header('Location: index.php');
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
    $id = $_GET['delete_category'];
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id=?");
    $stmt->execute([$id]);
    header("Location: admin_index.php");
    exit();
}

// --- RÉCUPÉRATION DES FILTRES ---
$f_cat = $_GET['f_cat'] ?? null;
$f_user = $_GET['f_user'] ?? null;



// 1. STATISTIQUES : Top contributeurs
$sql_stats = "SELECT u.username, u.email, COUNT(p.id) as total_prompts 
              FROM users u 
              LEFT JOIN prompts p ON u.id = p.user_id 
              WHERE  u.role !='admin'
              GROUP BY u.id 
              ORDER BY total_prompts DESC";
$stats_users = $pdo->query($sql_stats)->fetchAll();

// 2. RÉCUPÉRATION DES CATÉGORIES ET USERS (pour les filtres)
$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();
$all_users_list = $pdo->query("SELECT id, username FROM users  WHERE  role !='admin' ORDER BY username ASC")->fetchAll();

// 3. UTILISATION DU FILTRE POUR LA LISTE DES PROMPTS
$all_prompts = getFilteredPrompts($pdo, $f_cat, $f_user);

// 4. COMPTAGE TOTAL (Widgets)
$total_prompts = count($all_prompts);
$total_users = count($stats_users);

include 'header.php';
?>

<div class="container mt-4 pb-5">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h2 class="fw-bold text-dark"><i class="bi bi-person-fill-gear"></i> Espace Administration</h2>
        <span class="badge bg-primary p-2 d-flex align-items-center">Admin : <?= htmlspecialchars($_SESSION['user']) ?></span>
    </div>

    <div class="row mb-5">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-dark text-white p-3">
                <div class="d-flex align-items-center">
                    <i class="bi bi-chat-left-quote display-6 me-3"></i>
                    <div>
                        <h3 class="mb-0 fw-bold"><?= $total_prompts ?></h3>
                        <small>Prompts affichés</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-dark text-white p-3">
                <div class="d-flex align-items-center">
                    <i class="bi bi-people display-6 me-3"></i>
                    <div>
                        <h3 class="mb-0 fw-bold"><?= $total_users ?></h3>
                        <small>Développeurs inscrits</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-dark text-white p-3">
                <div class="d-flex align-items-center">
                    <i class="bi bi-tags display-6 me-3"></i>
                    <div>
                        <h3 class="mb-0 fw-bold"><?= count($categories) ?></h3>
                        <small>Catégories actives</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-lg-8 mb-4">
            <div class="card border-0 border-top border-primary border-3 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-graph-up"></i> Classement des Contributeurs</h5>
                </div>
                <div class="table-responsive ">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Utilisateur</th>
                                <th>Contact</th>
                                <th class="text-center">Actifs</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($stats_users as $user): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center ">
                                        <div class="bg-secondary text-white rounded-circle d-flex justify-content-center align-items-center me-2" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                            <?= strtoupper(substr($user['username'], 0, 1)) ?>
                                        </div>
                                        <strong><?= htmlspecialchars($user['username']) ?></strong>
                                    </div>
                                </td>
                                <td class="text-muted small"><?= htmlspecialchars($user['email']) ?></td>
                                <td class="text-center">
                                    <span class="badge rounded-pill bg-<?= $user['total_prompts'] > 0 ? 'success' : 'light text-dark' ?>">
                                        <?= $user['total_prompts'] ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card p-3 shadow-sm border-0 border-top border-primary border-3  h-100">
                <h6 class="fw-bold mb-3"><i class="bi bi-folder-plus"></i> Gestion des Catégories</h6>
                <form method="POST" class="d-flex gap-2 mb-3">
                    <input type="text" name="name" class="form-control form-control-sm" placeholder="Nouvelle catégorie..." required>
                    <button name="add_category" class="btn btn-sm btn-primary"><i class="bi bi-plus"></i></button>
                </form>
                <div class="d-flex flex-column gap-2 overflow-auto" style="max-height: 300px;">
                    <?php foreach($categories as $c): ?>
                        <div class="d-flex justify-content-between align-items-center px-3 py-2 rounded bg-light border-start border-dark border-3">
                            <span class="small fw-bold"><?= htmlspecialchars($c['name']) ?></span>
                            <a href="?delete_category=<?= $c['id'] ?>" class="text-danger" onclick="return confirm('Supprimer cette catégorie ?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

<div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
    <div class="card-header bg-primary text-white py-3 border-0">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold d-flex align-items-center">
                <i class="bi bi-list-stars me-2"></i>Liste Globale des Prompts
            </h5>

            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-primary-light btn-sm border-white border-opacity-25 text-white shadow-sm" data-bs-toggle="modal" data-bs-target="#adminFilterModal" style="background: rgba(255,255,255,0.1);">
                    <i class="bi bi-filter me-2"></i>Filtrer les prompts
                </button>
                
                <a href="admin_index.php" class="btn text-white p-0 ms-2" title="Réinitialiser">
                    <i class="bi bi-x-lg" style="font-size: 1.2rem;"></i>
                </a>
            </div>
        </div>
    </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Titre</th>
                                <th>Catégorie</th>
                                <th>Auteur</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($all_prompts as $p): ?>
                            <tr>
                                <td><span class="fw-bold"><?= htmlspecialchars($p['title']) ?></span></td>
                                <td><span class="badge bg-dark text-white"><?= htmlspecialchars($p['cat_name']) ?></span></td>
                                <td><small><?= htmlspecialchars($p['username']) ?></small></td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#readModal<?= $p['id'] ?>">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            <div class="modal fade" id="readModal<?= $p['id'] ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold"><?= htmlspecialchars($p['title']) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="d-flex justify-content-between mb-3">
                    <span class="badge bg-primary"><?= htmlspecialchars($p['cat_name']) ?></span>
                    <small class="text-muted">Auteur : <strong><?= htmlspecialchars($p['username']) ?></strong></small>
                </div>
                <div class="bg-secondary-subtle  p-3 rounded" style="font-family: monospace; white-space: pre-wrap;"><?= htmlspecialchars($p['content']) ?></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="adminFilterModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title small fw-bold">Options de filtrage</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="GET">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="small fw-bold mb-1">Catégorie</label>
                        <select name="f_cat" class="form-select form-select-sm">
                            <option value="">Toutes les catégories</option>
                            <?php foreach($categories as $c): ?>
                                <option value="<?= $c['id'] ?>" <?= $f_cat == $c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="small fw-bold mb-1">Auteur</label>
                        <select name="f_user" class="form-select form-select-sm">
                            <option value="">Tous les auteurs</option>
                            <?php foreach($all_users_list as $u): ?>
                                <option value="<?= $u['id'] ?>" <?= $f_user == $u['id'] ? 'selected' : '' ?>><?= htmlspecialchars($u['username']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="submit" class="btn btn-primary btn-sm w-100 shadow-sm">Appliquer</button>                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>