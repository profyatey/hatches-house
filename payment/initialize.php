<?php
require_once __DIR__ . '/../config/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status'=>false,'message'=>'Invalid request.']); exit;
}

$payload = json_decode(file_get_contents('php://input'), true);
$reference = trim($payload['reference'] ?? '');

$stmt = db()->prepare("SELECT * FROM registrations WHERE payment_reference=? LIMIT 1");
$stmt->execute([$reference]);
$reg = $stmt->fetch();

if (!$reg) {
    echo json_encode(['status'=>false,'message'=>'Registration not found.']); exit;
}

if (PAYSTACK_SECRET_KEY === 'sk_test_replace_me') {
    echo json_encode([
        'status'=>false,
        'message'=>'Add your Paystack secret key in config/config.php before testing payments.'
    ]);
    exit;
}

$body = [
    'email' => $reg['email'],
    'amount' => (int)round((float)$reg['total_amount'] * 100),
    'currency' => 'GHS',
    'reference' => $reference,
    'callback_url' => site_url('payment/callback.php')
];

$ch = curl_init('https://api.paystack.co/transaction/initialize');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($body),
    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer ' . PAYSTACK_SECRET_KEY,
        'Content-Type: application/json'
    ],
    CURLOPT_TIMEOUT => 20
]);
$result = curl_exec($ch);
curl_close($ch);

echo $result ?: json_encode(['status'=>false,'message'=>'Payment provider unavailable.']);
