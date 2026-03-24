<?php 
require 'db.php';
include 'header.php';
session_start();
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

$search = isset($_GET['search']) ? $_GET['search'] : '';


if($search){
    $stmt = $pdo->prepare("
        SELECT a.*, c.name AS category 
        FROM assets a 
        INNER JOIN categories c ON a.category_id = c.id 
        WHERE device_name LIKE :search OR serial_number LIKE :search
    ");
    
    $stmt->execute([
        'search' => "%$search%"
    ]);

}else{
    $stmt = $pdo->prepare("
        SELECT a.*, c.name AS category 
        FROM assets a 
        JOIN categories c ON a.category_id = c.id
    ");
    $stmt->execute();  
}
$data=$stmt->fetchAll();
?>

<div class="container py-4">

    <div class="card shadow-sm">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Assets Management</h4>
                <a href="add_assets.php" class="btn btn-dark btn-sm">
                    <i class="bi bi-plus-square me-1"></i> Add Asset
                </a>
            </div>

            <form id="searchForm" method="get">
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input 
                        type="text" 
                        name="search" 
                        id="searchInput"
                        value="<?= htmlspecialchars($search) ?>" 
                        class="form-control" 
                        placeholder="Search by device name or serial number"
                    >
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Serial number</th>
                            <th>Device name</th>
                            <th>Status</th>
                            <th>Price</th>
                            <th>Category</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php if(count($data) > 0): ?>
                        <?php foreach($data as $d): ?>
                        <tr>
                            <td><?= htmlspecialchars($d['serial_number']) ?></td>
                            <td><?= htmlspecialchars($d['device_name']) ?></td>

                            <td>
                                <span class="badge <?= $d['status']=='Deployed'?'bg-success':'bg-danger' ?>">
                                    <?= htmlspecialchars($d['status']) ?>
                                </span>
                            </td>

                            <td><?= htmlspecialchars($d['price']) ?> DH</td>

                            <td>
                                <span class="badge bg-secondary">
                                    <?= htmlspecialchars($d['category']) ?>
                                </span>
                            </td>

                            <td class="text-center">
                               
                                <a href="edit_asset.php?serial=<?= urlencode($d['serial_number']) ?>" class="btn btn-sm btn-outline-dark">
                                    <i class="bi bi-pencil-square me-1"></i>Edit
                                </a>

                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $d['serial_number'] ?>">
                                <i class="bi bi-trash3 me-1"></i>Delete
                                </button>
                            </td>
                        </tr>
                        </div>
                        <div class="modal fade" id="deleteModal<?= $d['serial_number'] ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title"><i class="bi bi-exclamation-triangle"></i> Confirm Delete</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete the asset: <strong><?= htmlspecialchars($d['device_name']); ?></strong>?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <a href="delete_asset.php?serial=<?= urlencode($d['serial_number']) ?>" class="btn btn-danger">Delete</a>
                        </div>
                        </div>
                        </div>
                        </div>

                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">No assets found</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>