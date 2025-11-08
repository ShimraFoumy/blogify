<?php
include 'db_connection.php';
include 'header.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if post ID is provided
if (!isset($_GET['id'])) {
    echo "<p>Post not found.</p>";
    include 'footer.php';
    exit;
}

$id = (int)$_GET['id'];

// Fetch the post
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    echo "<p>Post not found.</p>";
    include 'footer.php';
    exit;
}

// Increment view count
$pdo->prepare("UPDATE posts SET views = views + 1 WHERE id = ?")->execute([$id]);

// Determine image (all images are inside 'images/' folder)
$img = '';
if (!empty($post['image']) && file_exists(__DIR__ . '/images/' . $post['image'])) {
    $img = 'images/' . $post['image'];
}

// Determine if current user is the author
$showButtons = isset($_SESSION['user_id']) && $_SESSION['user_id'] == $post['user_id'];

// Check if current user liked the post
$liked = false;
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM likes WHERE user_id = ? AND post_id = ?");
    $stmt->execute([$_SESSION['user_id'], $post['id']]);
    $liked = $stmt->rowCount() > 0;
}

// Count total likes
$stmt = $pdo->prepare("SELECT COUNT(*) FROM likes WHERE post_id = ?");
$stmt->execute([$post['id']]);
$totalLikes = $stmt->fetchColumn();
?>

<div class="single-hero">
    <?php if (!empty($img)): ?>
        <img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
    <?php endif; ?>
</div>

<div class="single-body">

    <!-- Like Button -->
    <form method="post" action="like_post.php" style="display:inline;">
        <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
        <button type="submit" class="post-like-btn <?php echo $liked ? 'liked' : ''; ?>" title="Like/Unlike">
            <?php echo $liked ? '♥' : '♡'; ?>
        </button>
    </form>

    <!-- Post Content -->
    <h1 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h1>
    <p class="post-date"><?php echo date("F j, Y", strtotime($post['created_at'])); ?></p>
    <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>

    <!-- Like Count -->
    <p class="post-like-count"><?php echo $totalLikes; ?> <?php echo $totalLikes == 1 ? 'like' : 'likes'; ?></p>

    <!-- Edit/Delete Buttons for Author -->
    <?php if ($showButtons): ?>
        <div class="post-actions" style="margin-top: 20px;">
            <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="btn-edit">Edit</a>
            <a href="delete_post.php?id=<?php echo $post['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
        </div>
    <?php endif; ?>

</div>
<?php
// Fetch comments for this post
$stmt = $pdo->prepare("SELECT c.*, u.email FROM comments c JOIN users u ON c.user_id = u.id WHERE c.post_id = ? ORDER BY c.created_at ASC");
$stmt->execute([$post['id']]);
$comments = $stmt->fetchAll();
?>

<div class="comments-section">
    <h2>Comments (<?php echo count($comments); ?>)</h2>

    <?php if ($comments): ?>
        <ul class="comments-list">
            <?php foreach ($comments as $c): ?>
                <li>
                    <strong><?php echo htmlspecialchars($c['email']); ?></strong> 
                    <span class="comment-date"><?php echo date("F j, Y H:i", strtotime($c['created_at'])); ?></span>
                    <p><?php echo nl2br(htmlspecialchars($c['comment'])); ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No comments yet. Be the first to comment!</p>
    <?php endif; ?>

    <?php if (isset($_SESSION['user_id'])): ?>
        <form method="POST" action="add_comment.php" class="comment-form">
            <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
            <textarea name="comment" placeholder="Write your comment..." required></textarea>
            <button type="submit" class="btn-primary">Add Comment</button>
        </form>
    <?php else: ?>
        <p><a href="login.php">Log in</a> to add a comment.</p>
    <?php endif; ?>
</div>


<?php include 'footer.php'; ?>
