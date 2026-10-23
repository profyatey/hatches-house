<?php
require_once __DIR__ . '/../config/config.php';

$reference = trim($_GET['reference'] ?? '');
$stmt = db()->prepare("SELECT r.*, e.title, e.event_date, e.venue, tt.name ticket_name
                       FROM registrations r JOIN events e ON e.id=r.event_id
                       JOIN ticket_types tt ON tt.id=r.ticket_type_id
                       WHERE r.payment_reference=? AND r.status='paid'");
$stmt->execute([$reference]);
$reg = $stmt->fetch();

if (!$reg) exit('Paid registration not found.');

$ticketStmt = db()->prepare("SELECT * FROM tickets WHERE registration_id=? ORDER BY id ASC");
$ticketStmt->execute([$reg['id']]);
$tickets = $ticketStmt->fetchAll();

$meta = base_meta('Ticket Confirmation | HatchesHouse', 'Your HatchesHouse event ticket confirmation.', site_url('ticket/success.php?reference=' . urlencode($reference)), site_url('assets/images/logo.jpg'));
require __DIR__ . '/../includes/header.php';
?>
<section class="mx-auto max-w-3xl px-5 py-16">
    <div class="rounded-[2rem] border-2 border-black bg-white p-7 shadow-xl sm:p-10">
        <div class="flex flex-col justify-between gap-5 sm:flex-row sm:items-start">
            <div>
                <div class="text-xs font-black uppercase tracking-[.25em]">Payment confirmed</div>
                <h1 class="mt-3 text-4xl font-black">You're in.</h1>
                <p class="mt-2 text-zinc-500">Keep your ticket code ready for event check-in.</p>
            </div>
            <div class="rounded-full bg-black px-4 py-2 text-xs font-black text-white">PAID</div>
        </div>

        <div class="mt-10 grid gap-6 border-y border-zinc-200 py-7 sm:grid-cols-2">
            <div><div class="text-xs uppercase tracking-widest text-zinc-500">Event</div><div class="mt-1 font-black"><?= e($reg['title']) ?></div></div>
            <div><div class="text-xs uppercase tracking-widest text-zinc-500">Attendee</div><div class="mt-1 font-black"><?= e($reg['name']) ?></div></div>
            <div><div class="text-xs uppercase tracking-widest text-zinc-500">Date</div><div class="mt-1 font-black"><?= date('d F Y', strtotime($reg['event_date'])) ?></div></div>
            <div><div class="text-xs uppercase tracking-widest text-zinc-500">Ticket</div><div class="mt-1 font-black"><?= e($reg['ticket_name']) ?> × <?= (int)$reg['quantity'] ?></div></div>
        </div>

        <div class="mt-7 space-y-4">
            <?php foreach ($tickets as $ticket): ?>
                <div class="flex flex-col items-center justify-between gap-4 rounded-2xl bg-zinc-50 p-5 sm:flex-row">
                    <div>
                        <div class="text-xs uppercase tracking-widest text-zinc-500">Ticket code</div>
                        <div class="mt-1 font-mono text-lg font-black"><?= e($ticket['ticket_code']) ?></div>
                        <div class="mt-1 text-xs text-zinc-500">Valid · show this code/QR at check-in</div>
                    </div>
                    <div class="qr rounded-xl bg-white p-2" data-code="<?= e($ticket['ticket_code']) ?>"></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
document.querySelectorAll('.qr').forEach(el => {
    new QRCode(el, {text: el.dataset.code, width: 110, height: 110, correctLevel: QRCode.CorrectLevel.M});
});
</script>
<?php require __DIR__ . '/../includes/footer.php'; ?>
