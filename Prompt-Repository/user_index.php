<?php
ob_start();
session_start();
require 'db.php';
require 'filter_prompt.php';

if (!isset($_SESSION['id'])) {
    header('Location: index.php');
    exit();
}

include 'add_prompt.php';    
include 'edit_prompt.php';   
include 'delete_prompt.php'; 

$user_id = $_SESSION['id'];

$my_cat = $_GET['my_cat'] ?? null;
$others_cat = $_GET['others_cat'] ?? null;
$others_user = $_GET['others_user'] ?? null;

$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();
// Récupérer tous les utilisateurs sauf celui connecté
$all_users = $pdo->query("SELECT id, username FROM users
                          WHERE id != $user_id 
                          ORDER BY username ASC"
                        )->fetchAll();

$my_prompts = getFilteredPrompts($pdo, $my_cat, $_SESSION['id']);
$other_prompts = getFilteredPrompts($pdo, $others_cat, $others_user, $_SESSION['id']);

include 'header.php';
?>

<div class="container mt-5 pb-5">
    <div class="row align-items-center mb-5">
        <div class="col-md-8">
            <h2 class="fw-bold text-dark mb-1"><i class="bi bi-person-fill"></i> Espace User</h2>
            <p class="text-muted mb-0">Bienvenue, <span class="text-primary fw-bold"><?= htmlspecialchars($_SESSION['user']) ?></span>. Gérez vos actifs en toute simplicité.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <button class="btn btn-dark  shadow rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addPromptModal">
                <i class="bi bi-plus-lg me-2"></i>Nouveau Prompt
            </button>
        </div>
    </div>

    <div class="card border-0 border-top border-primary border-3 shadow-sm overflow-hidden mb-5" style="border-radius: 15px;">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center  border-bottom">
            <h5 class="mb-0 fw-bold"><i class="bi bi-collection-play text-primary me-2"></i>Mes Contributions</h5>
            <form method="GET" class="d-flex align-items-center">
                <select name="my_cat" class="form-select form-select-sm border-0 bg-light fw-bold text-primary px-3" style="border-radius: 10px; cursor: pointer;" onchange="this.form.submit()">
                    <option value="">Toutes mes catégories</option>
                    <?php foreach($categories as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= $my_cat == $c['id'] ? 'selected' : '' ?>><?= $c['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-uppercase small fw-bold text-muted">
                    <tr>
                        <th class="ps-4">Titre du Prompt</th>
                        <th>Catégorie</th>
                        <th class="text-center">Gestion</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($my_prompts)): ?>
                    <?php foreach($my_prompts as $p): ?>
                    <tr>
                        <td class="ps-4">
                            <span class="fw-bold text-dark"><?= htmlspecialchars($p['title']) ?></span>
                        </td>
                        <td>
                            <span class="badge rounded-pill bg-dark py-2 px-3"><?= $p['cat_name'] ?></span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group shadow-sm" >
                                <button id="btncrud" class="btn btn-sm btn-outline-primary " title="Voir" data-bs-toggle="modal" data-bs-target="#readModal<?= $p['id'] ?>"><i class="bi bi-eye-fill text-primary "></i></button>
                                <button id="btncrud" class="btn btn-sm btn-outline-warning" title="Modifier" data-bs-toggle="modal" data-bs-target="#editModal<?= $p['id'] ?>"><i class="bi bi-pencil-fill text-warning "></i></button>
                                <button id="btncrud" class="btn btn-sm btn-outline-danger" title="Supprimer" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $p['id'] ?>"><i class="bi bi-trash-fill text-danger"></i></button>
                            </div>
                        </td>
                    </tr>
                    <?php include 'read_prompt.php'; include 'edit_prompt.php'; include 'delete_prompt.php'; ?>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center py-5 text-muted">
                            <i class="bi bi-search d-block mb-2" style="font-size: 2rem;"></i>
                            Vous n'avez pas encore contribué.
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <hr class="my-5 opacity-25">

    <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0 text-dark"><i class="bi bi-globe me-2 text-primary"></i>Communauté DevGenius</h4>
        <small class="text-muted">Découvrez et inspirez-vous des créations partagées</small>
    </div>

    <div class="d-flex align-items-center gap-2">
        <button class="btn btn-outline-dark btn-sm rounded-pill px-4 shadow-sm fw-bold d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#filterCommunityModal">
            <i class="bi bi-sliders2-vertical me-2"></i> Filtrer
        </button>

        <a href="user_index.php" class="btn btn-light btn-sm rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 35px; height: 35px; border: 1px solid #dee2e6;" title="Fermer">
            <i class="bi bi-x-lg text-dark"></i>
        </a>
    </div>
</div>

    <div class="row">
        <?php foreach($other_prompts as $p): ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm card-custom-hover">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-soft-primary text-primary px-3 py-2 rounded-pill small fw-bold"><?= $p['cat_name'] ?></span>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person-circle text-muted me-1"></i>
                            <small class="text-muted fw-bold"><?= htmlspecialchars($p['username']) ?></small>
                        </div>
                    </div>
                    <h5 class="card-title fw-bold text-dark lh-base mb-0"><?= htmlspecialchars($p['title']) ?></h5>
                </div>
                <div class="card-footer bg-transparent border-0 p-4 pt-0">
                    <button class="btn btn-primary w-100 rounded-pill fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#readModal<?= $p['id'] ?>">
                        Consulter <i class="bi bi-arrow-right ms-2"></i>
                    </button>
                </div>
            </div>
        </div>
        <?php include 'read_prompt.php'; ?>
        <?php endforeach; ?>
    </div>
</div>

<div class="modal fade" id="filterCommunityModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header bg-dark text-white border-0 py-3" style="border-radius: 20px 20px 0 0;">
                <h6 class="modal-title fw-bold"><i class="bi bi-funnel me-2"></i>Affiner la recherche</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="GET">
                <div class="modal-body p-4">
                    <input type="hidden" name="my_cat" value="<?= $my_cat ?>">
                    <div class="mb-4">
                        <label class="small text-uppercase fw-bold text-muted mb-2">Par Catégorie</label>
                        <select name="others_cat" class="form-select border-0 bg-light">
                            <option value="">Toutes les catégories</option>
                            <?php foreach($categories as $c): ?>
                                <option value="<?= $c['id'] ?>" <?= $others_cat == $c['id'] ? 'selected' : '' ?>><?= $c['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="small text-uppercase fw-bold text-muted mb-2">Par Auteur</label>
                        <select name="others_user" class="form-select border-0 bg-light">
                            <option value="">Tous les auteurs</option>
                            <?php foreach($all_users as $u): ?>
                                <option value="<?= $u['id'] ?>" <?= $others_user == $u['id'] ? 'selected' : '' ?>><?= $u['username'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0 flex-column">
                    <button type="submit" class="btn btn-primary w-100 rounded-pill mb-2 shadow-sm">Appliquer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    body { background-color: #f8f9fa; }
    .bg-soft-primary { background-color: #eef4ff; }
    .card-custom-hover {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 15px;
    }
    .card-custom-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .btn-white:hover { background-color: #f8f9fa; }
    .table thead th { border-top: none; }
    #btncrud:hover{background-color: #f8f9fa}
</style>