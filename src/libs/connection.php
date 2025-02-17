<?php

function db(): PDO
{
    static $pdo;
    if (!$pdo) {
        try {
            $pdo = new PDO(
                sprintf("mysql:host=%s;dbname=%s;charset=UTF8", DB_HOST, DB_NAME),
                DB_USER,
                DB_PASSWORD,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $e) {
            if ($e->getCode() == 1049) {
                try {
                    $pdo = new PDO(
                        sprintf("mysql:host=%s;charset=UTF8", DB_HOST),
                        DB_USER,
                        DB_PASSWORD,
                        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                    );
                    $pdo->exec("CREATE DATABASE " . DB_NAME);
                    $pdo = new PDO(
                        sprintf("mysql:host=%s;dbname=%s;charset=UTF8", DB_HOST, DB_NAME),
                        DB_USER,
                        DB_PASSWORD,
                        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                    );

                    $pdo->exec("
                        CREATE TABLE users (
                            id INT AUTO_INCREMENT PRIMARY KEY,
                            username VARCHAR(25) NOT NULL UNIQUE,
                            email VARCHAR(320) NOT NULL UNIQUE,
                            password VARCHAR(256) NOT NULL,
                            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                        );
                        
                        CREATE TABLE search_history (
                            id INT AUTO_INCREMENT PRIMARY KEY,
                            user_id INT NOT NULL,
                            hashtag VARCHAR(255) NOT NULL,
                            search_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
                        );
                    ");
                } catch (PDOException $ex) {
                    die("Database creation failed: " . $ex->getMessage());
                }
            } else {
                die("Connection failed: " . $e->getMessage());
            }
        }
    }

    return $pdo;
}
