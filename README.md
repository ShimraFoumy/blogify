# Blogify

**Blogify** is a simple yet powerful PHP blog application with user authentication, post creation, comments, likes, and responsive design. It allows users to register, create posts with optional images, like posts, comment, and manage their content.

---

## 🛠 Features

### User Features
- User registration and login system with secure password hashing.
- View all blog posts on the homepage.
- Create new posts with optional images.
- Edit or delete posts (only for the author).
- Like posts and see total likes.
- Comment on posts (optional, can be extended).

### Admin / Author Features
- Manage own posts (edit/delete).
- View all comments on own posts.
- Secure access to post management pages.

### Frontend Features
- Responsive and modern UI using HTML, CSS, and Google Fonts.
- Hero section with call-to-action buttons.
- Blog grid with post previews, including title, excerpt, date, views, and comments.
- "View More" button to open full post details.

---

## 💻 Tech Stack

- **Backend:** PHP (PDO for database operations)
- **Database:** MySQL
- **Frontend:** HTML, CSS, JavaScript (optional)
- **Tools:** VS Code, Git, GitHub
- **Hosting:** InfinityFree or any PHP-compatible hosting

---

## 📂 Project Structure

/blogify
│
├── index.php # Homepage showing latest posts
├── single_post.php # Single post view with likes and comments
├── login.php # User login page
├── register.php # User registration page
├── create_post.php # Form to create new posts
├── edit_post.php # Edit existing posts
├── delete_post.php # Delete a post
├── db_connection.php # Database connection using PDO
├── functions.php # Helper functions (e.g., esc(), require_login())
├── add_comment.php # Handles comment submission
├── uploads/ # Folder for uploaded post images
├── images/ # Default and static images
├── css/ # Stylesheet folder (style.css)
└── .env # Environment variables (DB credentials)

---

## 🗄 Database Schema

Use the following SQL to create the necessary tables:

```sql
```
-- ----------------------------
-- Table structure for users
-- ----------------------------
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ----------------------------
-- Table structure for posts
-- ----------------------------
CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    views INT DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ----------------------------
-- Table structure for comments
-- ----------------------------
CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    comment TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ----------------------------
-- Table structure for likes
-- ----------------------------
CREATE TABLE likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_like (post_id, user_id),
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

```
```
---
## ⚡ Setup Instructions

1. **Clone the repository**
```bash
git clone https://github.com/ShimraFoumy/blogify.git
cd blogify
```
