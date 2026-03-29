<?php
require 'db.php';

if(!isset($_SESSION['user'])) exit();

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_prompt'])){
    $title = trim($_POST['title']);
    $category_id = intval($_POST['category_id']);
    $content = trim($_POST['content']);
    $user_id = $_SESSION['id'];

    if($title && $category_id && $content){
        $stmt = $pdo->prepare("INSERT INTO prompts (user_id, category_id, title, content) VALUES (?,?,?,?)");
        $stmt->execute([$user_id, $category_id, $title, $content]);
        header("Location: user_index.php " );
        exit();
    }
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Add Prompt Modal -->
<div class="modal fade" id="addPromptModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST">
        <div class="modal-header">
          <h5 class="modal-title">Ajouter un prompt</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="text" name="title" class="form-control mb-2" placeholder="Titre" required>
          <select name="category_id" class="form-select mb-2" required>
            <option value="">Choisir une catégorie</option>
            <?php foreach($categories as $c): ?>
              <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
            <?php endforeach; ?>
          </select>
          <textarea name="content" class="form-control" placeholder="Contenu du prompt" required></textarea>
        </div>
        <div class="modal-footer">
          <button type="submit" name="add_prompt" class="btn btn-primary">Ajouter</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        </div>
      </form>
    </div>
  </div>
</div>