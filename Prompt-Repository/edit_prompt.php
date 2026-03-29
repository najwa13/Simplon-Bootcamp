<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_prompt_submit'])) {
  if ($_POST['prompt_id'] == $p['id']) {
    $stmt = $pdo->prepare("UPDATE prompts SET title = ?, content = ?, category_id = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$_POST['title'], $_POST['content'], $_POST['category_id'], $_POST['prompt_id'], $_SESSION['id']]);
    header('Location: user_index.php');
    exit();
}
}
?>
<?php if (isset($p)): ?>
<div class="modal fade" id="editModal<?= $p['id'] ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <form action="" method="POST">
                <input type="hidden" name="prompt_id" value="<?= $p['id'] ?>">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title fw-bold">✏️ Modifier le Prompt</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Titre</label>
                        <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($p['title']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Catégorie</label>
                        <select name="category_id" class="form-select">
                            <?php foreach($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= ($cat['id'] == $p['category_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Contenu</label>
                        <textarea name="content" class="form-control" rows="6" required><?= htmlspecialchars($p['content']) ?></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="edit_prompt_submit" class="btn btn-warning fw-bold">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
