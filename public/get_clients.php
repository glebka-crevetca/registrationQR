<?php
require 'config.php';

try {
    $sql = "SELECT id, full_name, phone, email, arrived, registration_date FROM clients ORDER BY registration_date DESC";
    $stmt = $pdo->query($sql);
    $clients = $stmt->fetchAll();
    
    header('Content-Type: application/json');
    echo json_encode($clients);
    
} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode([
        'error' => 'Ошибка при получении данных: ' . $e->getMessage()
    ]);
}
?>