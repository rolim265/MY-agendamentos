<?php
header('Content-Type: application/json');
require __DIR__ . '/vendor/autoload.php';
require 'conexao.php';

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (is_null($data)) {
    http_response_code(400);
    echo json_encode(['success'=>false,'message'=>'JSON inválido ou vazio']);
    exit;
}

// função de validação CPF
function validarCPF($cpf) {
    $cpf = preg_replace('/[^0-9]/','',$cpf);
    if (strlen($cpf) != 11) return false;
    if (preg_match('/(\d)\1{10}/',$cpf)) return false;

    for ($t = 9; $t < 11; $t++) {
        $sum = 0;
        for ($c = 0; $c < $t; $c++) {
            $sum += $cpf[$c] * (($t + 1) - $c);
        }
        $digit = ((10 * $sum) % 11) % 10;
        if ($cpf[$c] != $digit) return false;
    }
    return true;
}

// checagens básicas
$campos = ['data','nome','cpf','email','qtd_pessoas','valor'];
foreach ($campos as $c) {
    if (!isset($data[$c]) || $data[$c] === '') {
        echo json_encode(['success'=>false,'message'=>"Campo obrigatório faltando: $c"]);
        exit;
    }
}

// valida cpf
if (!validarCPF($data['cpf'])) {
    echo json_encode(['success'=>false,'message'=>'CPF inválido']);
    exit;
}

// verifica se data já existe
try {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM agendamentos WHERE data = ?");
    $stmt->execute([$data['data']]);
    if ($stmt->fetchColumn() > 0) {
        echo json_encode(['success'=>false,'message'=>'Data já alugada']);
        exit;
    }

    // inserir
    $stmt = $pdo->prepare("INSERT INTO agendamentos (data, nome, cpf, email, telefone, qtd_pessoas, observacao, valor) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $ok = $stmt->execute([
        $data['data'],
        $data['nome'],
        $data['cpf'],
        $data['email'],
        $data['telefone'] ?? null,
        (int)$data['qtd_pessoas'],
        $data['observacao'] ?? null,
        (float)$data['valor']
    ]);

    if ($ok) {
        echo json_encode(['success'=>true,'message'=>'Aluguel salvo com sucesso']);
        exit;
    } else {
        echo json_encode(['success'=>false,'message'=>'Erro ao salvar agendamento']);
        exit;
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success'=>false,'message'=>'Erro no servidor: '.$e->getMessage()]);
    exit;
}
// Em produção, é melhor registrar o erro em um log e não exibir detalhes
// error_log($e->getMessage());
