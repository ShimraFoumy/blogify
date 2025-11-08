<?php
include 'db_connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'], $_POST['comment'])) {
    $post_id = (int)$_POST['post_id'];
    $comment = trim($_POST['comment']);
    $user_id = $_SESSION['user_id'];

    if ($comment !== '') {
        $stmt = $pdo->prepare("INSERT INTO comments (post_id, user_id, comment) VALUES (?, ?, ?)");
        $stmt->execute([$post_id, $user_id, $comment]);
    }

    // Redirect back to the same post page
    header("Location: single_post.php?id=" . $post_id);
    exit;
}
?>
