<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_prompt_submit'])) {
    if ($_POST['prompt_id'] == $p['id']) {
    $stmt = $pdo->prepare("DELETE FROM prompts WHERE id = ? AND user_id = ?");
    $stmt->execute([$_POST['prompt_id'], $_SESSION['id']]);
    header('Location: user_index.php');
    exit();
    }
}
?>

<div class="modal fade" id="deleteModal<?= $p['id'] ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form action="" method="POST">
                <input type="hidden" name="prompt_id" value="<?= $p['id'] ?>">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Confirmation de suppression</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <i class="bi bi-exclamation-triangle text-danger display-4"></i>
                    <p class="mt-3">Voulez-vous vraiment supprimer le prompt : <br><strong>"<?= htmlspecialchars($p['title']) ?>"</strong> ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" name="delete_prompt_submit" class="btn btn-danger">Supprimer définitivement</button>
                </div>
            </form>
        </div>
    </div>
</div>