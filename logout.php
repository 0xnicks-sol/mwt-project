<?php
session_start();
require 'db.php';

// Delete session from database
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("DELETE FROM sessions WHERE session_id = ?");
    $stmt->execute([session_id()]);
}

// Destroy all session data
$_SESSION = array();
session_destroy();

// Redirect to login page
header("Location: login.php");
exit();
?>