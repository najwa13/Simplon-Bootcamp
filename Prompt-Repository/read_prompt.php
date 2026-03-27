<?php

require 'db.php';
if(!isset($_SESSION['user'])) exit();

if(isset($_GET['id'])) $id = intval($_GET['id']);
else exit();

$stmt = $pdo->prepare("
SELECT p.*, c.name AS category_name, u.username
FROM prompts p
INNER JOIN categories c ON p.category_id = c.id
INNER JOIN users u ON p.user_id = u.id
WHERE p.id=?
");
$stmt->execute([$id]);
$prompt = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$prompt) exit();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
<!-- Read Prompt Modal -->
<div class="modal fade" id="readPromptModal<?= $id ?>" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><?= htmlspecialchars($prompt['title']) ?></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <p><strong>Auteur :</strong> <?= htmlspecialchars($prompt['username']) ?></p>
            <p><strong>Catégorie :</strong> <?= htmlspecialchars($prompt['category_name']) ?></p>
            <hr>
            <p><?= nl2br(htmlspecialchars($prompt['content'])) ?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        </div>
    </div>
  </div>
</div>
</body>