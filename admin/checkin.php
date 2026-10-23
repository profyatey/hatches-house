<?php
require_once __DIR__ . '/../config/config.php';
require_admin();

$message = '';
$ok = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf($_POST['csrf'] ?? null);
    $code = strtoupper(trim($_POST['ticket_code'] ?? ''));

    $q = db()->prepare("SELECT t.*, r.name, tt.name ticket_name, e.title
                        FROM tickets t
                        JOIN registrations r ON r.id=t.registration_id
                        JOIN ticket_types tt ON tt.id=r.ticket_type_id
                        JOIN events e ON e.id=r.event_id
                        WHERE t.ticket_code=? LIMIT 1");
    $q->execute([$code]);
    $ticket = $q->fetch();

    if (!$ticket) $message = 'INVALID — Ticket not found.';
    elseif ($ticket['status'] === 'used') $message = 'ALREADY USED — This ticket was already checked in.';
    elseif ($ticket['status'] === 'cancelled') $message = 'CANCELLED — This ticket is not valid.';
    else {
        db()->prepare("UPDATE tickets SET status='used', checked_in_at=NOW() WHERE id=?")->execute([$ticket['id']]);
        $message = 'VALID — ' . $ticket['name'] . ' · ' . $ticket['ticket_name'];
        $ok = true;
    }
}
?>
<!doctype html><html lang="en"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Check-in | HatchesHouse</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/html5-qrcode"></script>
</head>
<body class="min-h-screen bg-black text-white">
<main class="mx-auto max-w-2xl px-5 py-10">
<a href="<?= site_url('admin/dashboard.php') ?>" class="text-sm font-bold text-zinc-400">← Dashboard</a>
<h1 class="mt-10 text-5xl font-black">Event check-in</h1>
<p class="mt-3 text-zinc-400">Scan a QR ticket or enter the ticket code manually.</p>

<?php if ($message): ?><div class="mt-8 rounded-3xl p-6 text-xl font-black <?= $ok ? 'bg-white text-black' : 'border border-red-400 bg-red-950/30 text-red-200' ?>"><?= e($message) ?></div><?php endif; ?>

<div class="mt-8 overflow-hidden rounded-3xl border border-white/10 bg-white/5">
    <div id="reader" class="min-h-[280px]"></div>
    <div class="p-5">
        <button id="start" class="w-full rounded-full bg-white px-5 py-4 font-black text-black">Start Camera Scanner</button>
        <button id="stop" class="mt-3 hidden w-full rounded-full border border-white/20 px-5 py-4 font-bold">Stop Scanner</button>
        <p id="scanStatus" class="mt-3 text-center text-xs text-zinc-500"></p>
    </div>
</div>

<div class="my-8 flex items-center gap-3 text-xs uppercase tracking-widest text-zinc-600"><span class="h-px flex-1 bg-white/10"></span>Or enter manually<span class="h-px flex-1 bg-white/10"></span></div>

<form method="post" class="space-y-4">
<input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
<input id="ticket_code" name="ticket_code" required placeholder="HH-2026-XXXXXXXXXX" class="w-full rounded-2xl bg-white px-5 py-4 font-mono text-black">
<button class="w-full rounded-full bg-white px-5 py-4 font-black text-black">Verify Ticket</button>
</form>
</main>
<script>
let scanner=null;
const start=document.getElementById('start'), stop=document.getElementById('stop'), status=document.getElementById('scanStatus'), input=document.getElementById('ticket_code');
start.onclick=async()=>{
  scanner=new Html5Qrcode("reader"); start.classList.add('hidden'); stop.classList.remove('hidden');
  try{
    await scanner.start({facingMode:"environment"},{fps:10,qrbox:{width:230,height:230}},async code=>{
      input.value=code.trim(); status.textContent='Ticket detected. Verifying...';
      try{await scanner.stop()}catch(e){} scanner=null; input.form.submit();
    });
    status.textContent='Point the camera at the ticket QR code.';
  }catch(e){
    status.textContent='Camera unavailable. Use manual entry.';
    try{await scanner.stop()}catch(x){} scanner=null; start.classList.remove('hidden'); stop.classList.add('hidden');
  }
};
stop.onclick=async()=>{if(scanner){try{await scanner.stop()}catch(e){}} scanner=null; start.classList.remove('hidden'); stop.classList.add('hidden'); status.textContent='Scanner stopped.'};
</script>
</body></html>