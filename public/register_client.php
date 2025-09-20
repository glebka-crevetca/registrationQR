<?php
require 'config.php';

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['full_name']) || !isset($input['phone']) || !isset($input['email'])) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Все поля обязательны для заполнения'
    ]);
    exit;
}

$full_name = trim($input['full_name']);
$phone = trim($input['phone']);
$email = trim($input['email']);

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Некорректный формат email'
    ]);
    exit;
}

try {
    $sql = "INSERT INTO clients (full_name, phone, email) VALUES (:full_name, :phone, :email)";
    $stmt = $pdo->prepare($sql);
    
    $stmt->execute([
        ':full_name' => $full_name,
        ':phone' => $phone,
        ':email' => $email
    ]);
    
    $client_id = $pdo->lastInsertId();
    
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'id' => $client_id,
        'message' => 'Клиент успешно зарегистрирован'
    ]);
    
} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Ошибка при регистрации: ' . $e->getMessage()
    ]);
}
?>