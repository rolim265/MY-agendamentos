<?php
header('Content-Type: application/json');
require 'conexao.php';

try {
    $stmt = $pdo->query("SELECT id, DATE_FORMAT(data,'%Y-%m-%d') AS data, nome FROM agendamentos");
    $rows = $stmt->fetchAll();
    echo json_encode($rows);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success'=>false, 'message'=>'Erro ao listar: '.$e->getMessage()]);
}
// Em produção, é melhor registrar o erro em um log e não exibir detalhes
// error_log($e->getMessage());
