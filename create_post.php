<?php
include 'db_connection.php';
include 'header.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];

    // optional image upload
    $imageName = null;
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
        $imageName = time() . '_' . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $targetDir . $imageName);
    }

    $stmt = $pdo->prepare("INSERT INTO posts (user_id, title, content, image, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->execute([$user_id, $title, $content, $imageName]);
    header('Location: index.php');
    exit;
}
?>

<main class="form-page">
    <h2>Create New Post</h2>
    <form method="POST" enctype="multipart/form-data" class="post-form">
        <input type="text" name="title" placeholder="Enter post title" required>
        <textarea name="content" placeholder="Write your blog content..." required></textarea>
        <input type="file" name="image" accept="image/*">
        <button type="submit" class="btn-primary">Publish</button>
    </form>
</main>

<?php include 'footer.php'; ?>
