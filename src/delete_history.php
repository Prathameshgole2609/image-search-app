<?php
require __DIR__ . '/../src/bootstrap.php';

if (isset($_SESSION['user_id']) && isset($_GET['id'])) {
    $user_id = $_SESSION['user_id'];
    $search_id = intval($_GET['id']);

    $stmt = db()->prepare("DELETE FROM search_history WHERE id = ? AND user_id = ?");
    $stmt->execute([$search_id, $user_id]);

    // header("Location: ../public/index.php");
    redirect_to('../public/index.php');
} else {
    echo "Unauthorized request.";
}
?>
