<?php
require_once __DIR__ . '/../config/config.php';

$reference = trim($_GET['reference'] ?? '');
$stmt = db()->prepare("SELECT r.*, e.title, tt.name ticket_name FROM registrations r
                       JOIN events e ON e.id=r.event_id
                       JOIN ticket_types tt ON tt.id=r.ticket_type_id
                       WHERE r.payment_reference=? LIMIT 1");
$stmt->execute([$reference]);
$registration = $stmt->fetch();

if (!$registration) exit('Registration not found.');

$meta = base_meta('Payment | ' . $registration['title'], 'Complete your HatchesHouse ticket payment.', site_url('payment/pay.php?reference=' . urlencode($reference)), site_url('assets/images/logo.jpg'));
require __DIR__ . '/../includes/header.php';
?>
<section class="mx-auto max-w-xl px-5 py-16 lg:py-24">
    <div class="rounded-3xl border border-zinc-200 p-7 sm:p-10">
        <div class="text-xs font-black uppercase tracking-[.25em] text-zinc-500">Secure checkout</div>
        <h1 class="mt-3 text-3xl font-black">Complete payment</h1>
        <div class="mt-7 space-y-3 rounded-2xl bg-zinc-50 p-5 text-sm">
            <div class="flex justify-between"><span class="text-zinc-500">Event</span><b><?= e($registration['title']) ?></b></div>
            <div class="flex justify-between"><span class="text-zinc-500">Ticket</span><b><?= e($registration['ticket_name']) ?> × <?= (int)$registration['quantity'] ?></b></div>
            <div class="flex justify-between border-t border-zinc-200 pt-3"><span class="font-bold">Total</span><b><?= money((float)$registration['total_amount']) ?></b></div>
        </div>

        <button id="payBtn" class="mt-7 w-full rounded-full bg-black px-6 py-4 text-sm font-black text-white hover:bg-zinc-800">
            Pay with MoMo / Paystack
        </button>
        <p id="status" class="mt-4 text-center text-sm text-zinc-500"></p>
    </div>
</section>

<script src="https://js.paystack.co/v2/inline.js"></script>
<script>
const btn = document.getElementById('payBtn');
const statusEl = document.getElementById('status');

btn.addEventListener('click', async () => {
    btn.disabled = true;
    statusEl.textContent = 'Initializing secure payment...';

    try {
        const response = await fetch('<?= site_url('payment/initialize.php') ?>', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({reference: <?= json_encode($reference) ?>})
        });
        const data = await response.json();

        if (!data.status) throw new Error(data.message || 'Could not initialize payment.');

        const popup = new PaystackPop();
        popup.resumeTransaction(data.data.access_code);
    } catch (err) {
        statusEl.textContent = err.message;
        btn.disabled = false;
    }
});
</script>
<?php require __DIR__ . '/../includes/footer.php'; ?>
