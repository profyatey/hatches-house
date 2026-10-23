<?php
require_once __DIR__ . '/../config/config.php';

$payload = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_PAYSTACK_SIGNATURE'] ?? '';

if (!$payload || !$signature || !hash_equals(hash_hmac('sha512', $payload, PAYSTACK_SECRET_KEY), $signature)) {
    http_response_code(401); exit('Invalid signature.');
}

$data = json_decode($payload, true);
if (($data['event'] ?? '') !== 'charge.success') { http_response_code(200); exit('Ignored.'); }

$reference = trim($data['data']['reference'] ?? '');
$amount = (int)($data['data']['amount'] ?? 0);
if (!$reference || ($data['data']['currency'] ?? '') !== 'GHS') { http_response_code(200); exit('Invalid.'); }

$pdo = db();

try {
    $pdo->beginTransaction();
    $q = $pdo->prepare("SELECT * FROM registrations WHERE payment_reference=? FOR UPDATE");
    $q->execute([$reference]);
    $reg = $q->fetch();

    if (!$reg) { $pdo->rollBack(); http_response_code(200); exit('Not found.'); }

    $expected = (int)round((float)$reg['total_amount'] * 100);
    if ($amount < $expected) { $pdo->rollBack(); http_response_code(400); exit('Amount mismatch.'); }

    if ($reg['status'] !== 'paid') {
        $pdo->prepare("UPDATE registrations SET status='paid', paid_at=NOW() WHERE id=?")->execute([$reg['id']]);
        $pdo->prepare("UPDATE ticket_types SET sold=sold+? WHERE id=?")->execute([$reg['quantity'],$reg['ticket_type_id']]);

        for ($i=0; $i<(int)$reg['quantity']; $i++) {
            $code='HH-'.date('Y').'-'.strtoupper(bin2hex(random_bytes(5)));
            $pdo->prepare("INSERT INTO tickets(registration_id,ticket_code,status) VALUES (?,?,'valid')")->execute([$reg['id'],$code]);
        }
    }

    $pdo->commit(); http_response_code(200); echo 'OK';
} catch (Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    http_response_code(500); echo 'Webhook error.';
}
