<?php
$host = 'MySQL-8.4';
$dbname = 'roadshow_event';
$user = 'root';
$pass = '';
$port = 3306;

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $user, $pass);
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Ошибка подключения к базе данных: ' . $e->getMessage(),
        'details' => 'Проверьте настройки OpenServer и параметры подключения'
    ]);
    exit;
}
?>