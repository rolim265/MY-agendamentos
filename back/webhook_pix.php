<?php
require 'bd.php'; // Conexão PDO com seu banco
require __DIR__ . '/vendor/autoload.php';

// Mercado Pago manda dados via POST (JSON)
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['data']['id'])) {
    http_response_code(400);
    exit('Dados inválidos');
}

$payment_id = $input['data']['id'];

// Usar a SDK para pegar o status do pagamento:
require 'vendor/autoload.php';
MercadoPago\SDK::setAccessToken("SEU_ACCESS_TOKEN_AQUI");

$payment = MercadoPago\Payment::find_by_id($payment_id);

if ($payment->status === 'approved' && $payment->payment_method_id === 'pix') {
    // Aqui você pega alguma info que tenha enviado na descrição ou metadata
    // Para simplificar, vou supor que você tenha armazenado o ID do agendamento na descrição
    // Ou melhor, você pode salvar o agendamento só depois da confirmação (explicação abaixo)
    
    // Exemplo: salvar no banco agendamento aprovado (implemente sua lógica)
    // $stmt = $pdo->prepare("UPDATE agendamentos SET pago = 1 WHERE id = ?");
    // $stmt->execute([$payment_id]);

    // Só precisa responder 200 pro Mercado Pago
    http_response_code(200);
    echo "OK";
} else {
    // Pagamento não aprovado ainda
    http_response_code(200);
    echo "Pagamento não aprovado";
}
