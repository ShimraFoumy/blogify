<?php
include 'db_connection.php';
include 'functions.php';
include 'header.php';
?>

<!-- Hero / Get Started Section -->
<section class="hero-section-full">
    <div class="hero-overlay">
        <h1>Welcome to Our Blog</h1>
        <p>Discover the latest posts, tutorials, and insights. Start exploring now!</p>
        <a href="register.php" class="btn-primary">Get Started</a>
        <a href="#posts" class="btn-blogs" style="margin-left: 15px;">Blogs</a>
    </div>
</section>

<h2 id="posts" class="section-title">Latest Posts</h2>

<div class="blog-grid">
<?php
try {
    $stmt = $pdo->query("
        SELECT p.*, 
               (SELECT COUNT(*) FROM comments c WHERE c.post_id = p.id) AS comments_count 
        FROM posts p 
        ORDER BY created_at DESC
    ");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo '<p class="no-posts">Error fetching posts: ' . htmlspecialchars($e->getMessage()) . '</p>';
    $rows = [];
}

$fallbacks = glob('images/*.{jpg,JPG,jpeg,JPEG,png,PNG,gif,GIF}', GLOB_BRACE) ?: [];

if ($rows) {
    foreach ($rows as $r) {
        // Determine image
        $img = 'images/default.jpeg'; // default fallback
        if (!empty($r['image'])) {
            if (filter_var($r['image'], FILTER_VALIDATE_URL)) {
                $img = $r['image'];
            } elseif (file_exists(__DIR__ . '/' . $r['image'])) {
                $img = $r['image'];
            } elseif (file_exists(__DIR__ . '/images/' . $r['image'])) {
                $img = 'images/' . $r['image'];
            } elseif (file_exists(__DIR__ . '/uploads/' . $r['image'])) {
                $img = 'uploads/' . $r['image'];
            } elseif (!empty($fallbacks)) {
                $img = $fallbacks[array_rand($fallbacks)];
            }
        }

        $excerpt = strip_tags($r['content']);
        if (mb_strlen($excerpt) > 200) $excerpt = mb_substr($excerpt, 0, 200) . '...';

        echo '<div class="blog-card">';
        echo '  <div class="blog-image"><a href="single_post.php?id='.$r['id'].'"><img src="'.htmlspecialchars($img).'" alt="'.htmlspecialchars($r['title']).'"></a></div>';
        echo '  <div class="blog-content">';
        echo '    <h3 class="blog-title">'.htmlspecialchars($r['title']).'</h3>';
        echo '    <div class="badges">';
        echo '      <span class="badge">'.date("M d, Y", strtotime($r['created_at'])).'</span>';
        echo '      <span class="badge">'.(int)$r['views'].' Views</span>';
        echo '      <span class="badge">'.(int)$r['comments_count'].' Comments</span>';
        echo '    </div>';
        echo '    <p class="blog-excerpt">'.htmlspecialchars($excerpt).'</p>';
        echo '    <div class="card-actions">';
        echo '      <a class="read-more" href="single_post.php?id='.$r['id'].'">View More →</a>';
        echo '    </div>';
        echo '  </div>';
        echo '</div>';
    }
} else {
    echo '<p class="no-posts">No posts found.</p>';
}
?>
</div>

<?php include 'footer.php'; ?>
