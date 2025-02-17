<?php

function register_user(string $email, string $username, string $password): bool
{   
    $sql = 'INSERT INTO users(username, email, password)
            VALUES(:username, :email, :password)';

    $statement = db()->prepare($sql);

    $statement->bindValue(':username', $username, PDO::PARAM_STR);
    $statement->bindValue(':email', $email, PDO::PARAM_STR);
    $statement->bindValue(':password', password_hash($password, PASSWORD_BCRYPT), PDO::PARAM_STR);

    return $statement->execute();
}

function find_user_by_username(string $username)
{
    $sql = 'SELECT * FROM users WHERE username=:username';

    $statement = db()->prepare($sql);
    $statement->bindValue(':username', $username, PDO::PARAM_STR);
    $statement->execute();

    return $statement->fetch(PDO::FETCH_ASSOC);
}

function login(string $username, string $password): bool
{
    $user = find_user_by_username($username);

    if ($user && password_verify($password, $user['password'])) {

        session_regenerate_id();

        $_SESSION['username'] = $user['username'];
        $_SESSION['user_id']  = $user['id'];
        return true;
    }
    return false;
}

function is_user_logged_in(): bool
{
    return isset($_SESSION['username']);
}

function require_login(): void
{
    if (!is_user_logged_in()) {
        redirect_to('login.php');
    }
}

function logout(): void
{
    if (is_user_logged_in()) {
        unset($_SESSION['username'], $_SESSION['user_id']);
        session_destroy();
        redirect_to('login.php');
    }
}

function current_user()
{
    if (is_user_logged_in()) {
        return $_SESSION['username'];
    }
    return null;
}