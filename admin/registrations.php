<?php
require_once __DIR__ . '/../config/config.php';
require_admin();
$status = $_GET['status'] ?? '';
$sql = "SELECT r.*, e.title, tt.name ticket_name FROM registrations r JOIN events e ON e.id=r.event_id JOIN ticket_types tt ON tt.id=r.ticket_type_id";
$params = [];
if (in_array($status, ['pending','paid'], true)) { $sql .= " WHERE r.status=?"; $params[]=$status; }
$sql .= " ORDER BY r.created_at DESC";
$stmt=db()->prepare($sql); $stmt->execute($params); $rows=$stmt->fetchAll();
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Registrations</title><script src="https://cdn.tailwindcss.com"></script></head>
<body class="bg-zinc-50"><main class="mx-auto max-w-7xl px-5 py-8">
<a href="<?= site_url('admin/dashboard.php') ?>" class="font-bold">← Dashboard</a>
<h1 class="mt-6 text-3xl font-black">Registrations</h1>
<div class="mt-6 overflow-x-auto rounded-3xl border bg-white"><table class="w-full text-left text-sm"><thead class="border-b bg-zinc-50"><tr><th class="p-4">Name</th><th class="p-4">Event</th><th class="p-4">Ticket</th><th class="p-4">Amount</th><th class="p-4">Status</th><th class="p-4">Date</th></tr></thead><tbody>
<?php foreach($rows as $r): ?><tr class="border-b last:border-0"><td class="p-4 font-bold"><?= e($r['name']) ?><div class="text-xs text-zinc-400"><?= e($r['phone']) ?></div></td><td class="p-4"><?= e($r['title']) ?></td><td class="p-4"><?= e($r['ticket_name']) ?> × <?= (int)$r['quantity'] ?></td><td class="p-4 font-bold"><?= money((float)$r['total_amount']) ?></td><td class="p-4"><?= e($r['status']) ?></td><td class="p-4"><?= e($r['created_at']) ?></td></tr><?php endforeach; ?>
</tbody></table></div></main></body></html>
