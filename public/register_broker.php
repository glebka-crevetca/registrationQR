<?php
require 'config.php';

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['broker_name']) || !isset($input['broker_phone']) || !isset($input['client_name'])) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Все поля обязательны для заполнения'
    ]);
    exit;
}

$broker_name = trim($input['broker_name']);
$broker_phone = trim($input['broker_phone']);
$client_name = trim($input['client_name']);

try {
    $sql = "INSERT INTO broker_clients (broker_name, broker_phone, client_name) VALUES (:broker_name, :broker_phone, :client_name)";
    $stmt = $pdo->prepare($sql);
    
    $stmt->execute([
        ':broker_name' => $broker_name,
        ':broker_phone' => $broker_phone,
        ':client_name' => $client_name
    ]);
    
    $client_id = $pdo->lastInsertId();
    
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'id' => $client_id,
        'message' => 'Клиент от брокера успешно зарегистрирован'
    ]);
    
} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Ошибка при регистрации: ' . $e->getMessage()
    ]);
}
?>