<?php
require_once __DIR__ . '/../config/config.php';

$reference = trim($_GET['reference'] ?? '');
if (!$reference) exit('Missing payment reference.');

$ch = curl_init('https://api.paystack.co/transaction/verify/' . rawurlencode($reference));
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . PAYSTACK_SECRET_KEY],
    CURLOPT_TIMEOUT => 20
]);
$result = curl_exec($ch);
curl_close($ch);

$data = json_decode($result ?: '', true);

if (!($data['status'] ?? false) || ($data['data']['status'] ?? '') !== 'success') {
    exit('Payment was not successful or could not be verified.');
}

$pdo = db();
$pdo->beginTransaction();

try {
    $stmt = $pdo->prepare("SELECT * FROM registrations WHERE payment_reference=? FOR UPDATE");
    $stmt->execute([$reference]);
    $reg = $stmt->fetch();

    if (!$reg) throw new RuntimeException('Registration not found.');

    if ($reg['status'] !== 'paid') {
        $pdo->prepare("UPDATE registrations SET status='paid', paid_at=NOW() WHERE id=?")->execute([$reg['id']]);
        $pdo->prepare("UPDATE ticket_types SET sold=sold+? WHERE id=?")->execute([$reg['quantity'], $reg['ticket_type_id']]);

        for ($i=0; $i<(int)$reg['quantity']; $i++) {
            $ticketCode = 'HH-' . date('Y') . '-' . strtoupper(bin2hex(random_bytes(5)));
            $pdo->prepare("INSERT INTO tickets (registration_id,ticket_code,status) VALUES (?,?,'valid')")
                ->execute([$reg['id'], $ticketCode]);
        }
    }

    $pdo->commit();
    redirect(site_url('ticket/success.php?reference=' . urlencode($reference)));
} catch (Throwable $e) {
    $pdo->rollBack();
    exit(e($e->getMessage()));
}
