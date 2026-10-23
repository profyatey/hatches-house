<?php
require_once __DIR__ . '/../config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect(site_url('events.php'));
verify_csrf($_POST['csrf'] ?? null);

$eventId = (int)($_POST['event_id'] ?? 0);
$ticketTypeId = (int)($_POST['ticket_type_id'] ?? 0);
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$quantity = max(1, min(10, (int)($_POST['quantity'] ?? 1)));

if (!$eventId || !$ticketTypeId || !$name || !filter_var($email, FILTER_VALIDATE_EMAIL) || !$phone) {
    exit('Please provide valid registration details.');
}

$pdo = db();
$pdo->beginTransaction();

try {
    $stmt = $pdo->prepare("SELECT e.title, e.slug, tt.name ticket_name, tt.price, tt.quantity, tt.sold
                           FROM events e JOIN ticket_types tt ON tt.event_id=e.id
                           WHERE e.id=? AND tt.id=? AND e.status='published' AND tt.is_active=1
                           FOR UPDATE");
    $stmt->execute([$eventId, $ticketTypeId]);
    $row = $stmt->fetch();

    if (!$row) throw new RuntimeException('Ticket type not found.');
    if (((int)$row['quantity'] - (int)$row['sold']) < $quantity) throw new RuntimeException('Not enough tickets available.');

    $total = (float)$row['price'] * $quantity;
    $reference = 'HH-' . strtoupper(bin2hex(random_bytes(6)));

    $stmt = $pdo->prepare("INSERT INTO registrations (event_id,ticket_type_id,name,email,phone,quantity,total_amount,payment_reference,status)
                           VALUES (?,?,?,?,?,?,?,?, 'pending')");
    $stmt->execute([$eventId,$ticketTypeId,$name,$email,$phone,$quantity,$total,$reference]);
    $registrationId = (int)$pdo->lastInsertId();

    $pdo->commit();

    $_SESSION['checkout'] = [
        'registration_id' => $registrationId,
        'reference' => $reference,
        'email' => $email,
        'name' => $name,
        'amount' => $total
    ];

    redirect(site_url('payment/pay.php?reference=' . urlencode($reference)));
} catch (Throwable $e) {
    $pdo->rollBack();
    http_response_code(400);
    exit(e($e->getMessage()));
}
