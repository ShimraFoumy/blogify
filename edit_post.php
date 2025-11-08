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

// Only the author can edit
if ($_SESSION['user_id'] != $post['user_id']) {
    echo "<p>Access denied. You are not the author of this post.</p>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];

    // optional image upload
    $imageName = $post['image']; // keep old image if no new upload
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir);
        $imageName = time() . '_' . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $targetDir . $imageName);

        // optionally delete old image
        if (!empty($post['image']) && file_exists($targetDir . $post['image'])) {
            unlink($targetDir . $post['image']);
        }
    }

    $stmt = $pdo->prepare("UPDATE posts SET title = ?, content = ?, image = ? WHERE id = ?");
    $stmt->execute([$title, $content, $imageName, $post_id]);

    header("Location: single_post.php?id=$post_id");
    exit;
}
?>

<main class="form-page">
    <h2>Edit Post</h2>
    <form method="POST" enctype="multipart/form-data" class="post-form">
        <input type="text" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
        <textarea name="content" required><?php echo htmlspecialchars($post['content']); ?></textarea>
        <?php if (!empty($post['image'])): ?>
            <img src="uploads/<?php echo htmlspecialchars($post['image']); ?>" style="max-width:200px;margin-bottom:10px;">
        <?php endif; ?>
        <input type="file" name="image" accept="image/*">
        <button type="submit" class="btn-primary">Update</button>
    </form>
</main>

<?php include 'footer.php'; ?>
