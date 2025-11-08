<?php
if (session_status() == PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Blog App</title>
<link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>
<header>
  <div class="header-container container">
    <a href="index.php" class="logo">Blogify</a>
    <nav class="main-nav">
        <a href="index.php">Home</a>
        <a href="#posts">Blogs</a>
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="create_post.php">Create Post</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </nav>
  </div>
</header>
<main class="container">
