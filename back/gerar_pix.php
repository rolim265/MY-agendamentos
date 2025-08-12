<?php
require __DIR__ . '/vendor/autoload.php';

MercadoPago\SDK::setAccessToken("TEST-8711531440856482-081217-3bd8173b31acb8ecbdb69cbdd60caa48-490839095");

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['valor']) || !isset($input['descricao'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Parâmetros inválidos']);
    exit;
}

$payment = new MercadoPago\Payment();
$payment->transaction_amount = (float)$input['valor'];
$payment->description = $input['descricao'];
$payment->payment_method_id = "pix";
$payment->payer = [
    "email" => "test_user_123456@test.com"
];

if ($payment->save()) {
    echo json_encode([
        'id' => $payment->id,
        'point_of_interaction' => $payment->point_of_interaction
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'error' => 'Erro ao gerar PIX',
        'details' => $payment->error
    ]);
}
