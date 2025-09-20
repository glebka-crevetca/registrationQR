<?php
require 'config.php';

try {
    $sql = "SELECT id, broker_name, broker_phone, client_name, arrived, registration_date FROM broker_clients ORDER BY registration_date DESC";
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