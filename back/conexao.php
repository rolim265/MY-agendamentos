<?php
// Ajuste as credenciais conforme o seu ambiente XAMPP
$host = 'localhost';
$db   = 'chacara';   // nome do DB criado no SQL acima
$user = 'root';
$pass = '';

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, $options);
} catch (PDOException $e) {
    // Em desenvolvimento é útil exibir o erro
    http_response_code(500);
    echo json_encode(['success'=>false, 'message'=>'Erro conexão DB: '.$e->getMessage()]);
    exit;
}
// Em produção, é melhor registrar o erro em um log e não exibir detalhes
// error_log($e->getMessage());