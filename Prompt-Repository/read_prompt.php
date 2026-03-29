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