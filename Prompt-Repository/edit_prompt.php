<?php
session_start();
require 'db.php';
if(!isset($_SESSION['user'])) exit();

if(isset($_GET['id'])) $id = intval($_GET['id']);
else exit();

$stmt = $pdo->prepare("SELECT * FROM prompts WHERE id=?");
$stmt->execute([$id]);
$prompt = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$prompt) exit();

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_prompt'])){
    $title = trim($_POST['title']);
    $category_id = intval($_POST['category_id']);
    $content = trim($_POST['content']);

    if($title && $category_id && $content){
        $stmt = $pdo->prepare("UPDATE prompts SET title=?, category_id=?, content=? WHERE id=? AND user_id=?");
        $stmt->execute([$title, $category_id, $content, $id, $_SESSION['user_id']]);
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Edit Prompt Modal -->
<div class="modal fade" id="editPromptModal<?= $id ?>" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST">
        <div class="modal-header">
          <h5 class="modal-title">Modifier le prompt</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="text" name="title" class="form-control mb-2" value="<?= htmlspecialchars($prompt['title']) ?>" required>
          <select name="category_id" class="form-select mb-2" required>
            <?php foreach($categories as $c): ?>
              <option value="<?= $c['id'] ?>" <?= ($c['id']==$prompt['category_id']?'selected':'') ?>><?= htmlspecialchars($c['name']) ?></option>
            <?php endforeach; ?>
          </select>
          <textarea name="content" class="form-control" required><?= htmlspecialchars($prompt['content']) ?></textarea>
        </div>
        <div class="modal-footer">
          <button type="submit" name="edit_prompt" class="btn btn-warning">Modifier</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        </div>
      </form>
    </div>
  </div>
</div>