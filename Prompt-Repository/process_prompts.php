<?php
// FILTRAGE
function getFilteredPrompts($pdo, $cat_id = null, $user_id = null, $exclude_id = null) {
    $sql = "SELECT p.*, c.name AS cat_name, u.username 
            FROM prompts p 
            JOIN categories c ON p.category_id = c.id 
            JOIN users u ON p.user_id = u.id 
            WHERE (:cat IS NULL OR p.category_id = :cat)
              AND (:usr IS NULL OR p.user_id = :usr)
              AND (:excl IS NULL OR p.user_id != :excl)
            ORDER BY p.id DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'cat'  => $cat_id ?: null,
        'usr'  => $user_id ?: null,
        'excl' => $exclude_id ?: null
    ]);
    
    return $stmt->fetchAll();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    //  AJOUT
    if (isset($_POST['add_prompt'])) {
        $stmt = $pdo->prepare("INSERT INTO prompts (user_id, category_id, title, content) VALUES (?,?,?,?)");
        $stmt->execute([$_SESSION['id'], $_POST['category_id'], trim($_POST['title']), trim($_POST['content'])]);
        header("Location: user_index.php");
        exit();
    }

    //  MODIFICATION
    if (isset($_POST['edit_prompt_submit'])) {
        $stmt = $pdo->prepare("UPDATE prompts SET title = ?, content = ?, category_id = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$_POST['title'], $_POST['content'], $_POST['category_id'], $_POST['prompt_id'], $_SESSION['id']]);
        header('Location: user_index.php');
        exit();
    }

    // SUPPRESSION
    if (isset($_POST['delete_prompt_submit'])) {
        $stmt = $pdo->prepare("DELETE FROM prompts WHERE id = ? AND user_id = ?");
        $stmt->execute([$_POST['prompt_id'], $_SESSION['id']]);
        header('Location: user_index.php');
        exit();
    }
}
?>