<?php
session_start();


if (isset($_SESSION['user'])) {
    if ($_SESSION['role'] === 'admin') {
        header('Location: admin_index.php');
    } else {
        header('Location: user_index.php');
    }
} else {
    
    header('Location: login.php');
}
exit();
?>