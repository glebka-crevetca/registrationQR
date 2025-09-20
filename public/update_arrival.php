<?php
require 'config.php';

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['id']) || !isset($input['type']) || !isset($input['arrived'])) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Не все параметры переданы'
    ]);
    exit;
}

$id = intval($input['id']);
$arrived = $input['arrived'] ? 1 : 0;

try {
    if ($type === 'client') {
        $sql = "UPDATE clients SET arrived = :arrived WHERE id = :id";
    } elseif ($type === 'broker') {
        $sql = "UPDATE broker_clients SET arrived = :arrived WHERE id = :id";
    } else {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Неверный тип клиента'
        ]);
        exit;
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':arrived' => $arrived,
        ':id' => $id
    ]);
    
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'message' => 'Статус прибытия обновлен'
    ]);
    
} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Ошибка при обновлении: ' . $e->getMessage()
    ]);
}
?>