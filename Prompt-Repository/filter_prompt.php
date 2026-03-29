<?php



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