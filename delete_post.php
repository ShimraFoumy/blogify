<?php
include 'db_connection.php';
include 'header.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    echo "<p>Post not found.</p>";
    exit;
}

$post_id = (int)$_GET['id'];

// Fetch the post
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$post_id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    echo "<p>Post not found.</p>";
    exit;
}

// Only the author can delete
if ($_SESSION['user_id'] != $post['user_id']) {
    echo "<p>Access denied. You are not the author of this post.</p>";
    exit;
}

// Delete image file if exists
if (!empty($post['image']) && file_exists("uploads/" . $post['image'])) {
    unlink("uploads/" . $post['image']);
}

// Delete post from database
$pdo->prepare("DELETE FROM posts WHERE id = ?")->execute([$post_id]);

// Redirect to homepage after deletion
header("Location: index.php");
exit;
?>
