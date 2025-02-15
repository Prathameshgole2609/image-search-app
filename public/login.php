<?php
require __DIR__ . '/../src/bootstrap.php';
require __DIR__ . '/../src/login.php';
?>

<?php view('header', ['title' => 'Login', 'href' => '../src/inc/style.css']) ?>

<?php if (isset($errors['login'])) : ?>
    <div class="alert alert-error">
        <?= $errors['login'] ?>
    </div>
<?php endif ?>

    <form action="login.php" method="post">
        <h1>Login</h1>
        <div>
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" value="<?= $inputs['username'] ?? '' ?>">
            <small><?= $errors['username'] ?? '' ?></small>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password">
            <small><?= $errors['password'] ?? '' ?></small>
        </div>
        <div>
            <button type="submit" class="form-btn">Login</button>
            <footer>Don't have an account? <a href="register.php">Register Here</a></footer>
        </div>
    </form>

<?php view('footer') ?>