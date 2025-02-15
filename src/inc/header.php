<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= $href?>">
    <script src="https://kit.fontawesome.com/192f77a36b.js" crossorigin="anonymous"></script>
    <title><?= $title ?? 'Home' ?></title>
</head>
<body>
<div class="header">
    <div class="head-cnt">
        <div class="logo">
            <img src="../src/assets/logo.jpg" class="logoimg"/>
            <p class="logo-text">Image Search App</p>
        </div>

        <?php if (is_user_logged_in()): ?>
            <div class="profile">
                <p><i class='fas fa-hand-sparkles' style='font-size:20px'></i>Welcome <?= current_user() ?></p>
                <img src="http://api.dicebear.com/5.x/initials/svg?seed=<?= current_user() ?>" class="profile-avatar"/>
                <a href="logout.php"><img src="../src/assets/logout.png" class="logout"/></a>
            </div>
        <?php else: ?>
            <div class="btn-cnt">
            <a href="login.php" style="text-decoration: none;"><button class="head-btn">Login</button></a>
            <a href="register.php" style="text-decoration: none;"><button class="head-btn">Register</button></a>
            </div>
        <?php endif; ?>
    </div>
</div>
<main class="main-cnt">
<?php flash() ?>
