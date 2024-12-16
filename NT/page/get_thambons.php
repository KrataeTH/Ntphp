<?php
require_once '../config/condb.php';

// Check if amphure_id is provided
if (isset($_GET['amphure_id']) && !empty($_GET['amphure_id'])) {
    $amphure_id = (int) $_GET['amphure_id'];

    // Fetch thambons based on amphure_id
    $stmt = $pdo->prepare("SELECT thambon_id, thambon_name FROM thambon WHERE amphure_id = :amphure_id ORDER BY thambon_name");
    $stmt->execute([':amphure_id' => $amphure_id]);

    // Return thambons as JSON
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}
?>
