<?php
include 'db_connection.php';
include 'functions.php';
if (session_status() === PHP_SESSION_NONE) session_start();
require_login();

$post_id = intval($_POST['post_id'] ?? 0);
$comment = trim($_POST['comment'] ?? '');
$user_id = current_user_id();

if ($post_id && $comment !== '') {
    $stmt = $pdo->prepare("INSERT INTO comments (post_id,user_id,comment,created_at) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$post_id, $user_id, $comment]);
}
header('Location: single_post.php?id=' . $post_id);
exit;
