<?php
require_once __DIR__ . '/../config/config.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf($_POST['csrf'] ?? null);
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $title = trim($_POST['title'] ?? '');
        $slug = slugify($title);
        $description = trim($_POST['description'] ?? '');
        $short = trim($_POST['short_description'] ?? '');
        $date = $_POST['event_date'] ?? '';
        $start = $_POST['start_time'] ?? '';
        $end = $_POST['end_time'] ?? '';
        $venue = trim($_POST['venue'] ?? '');
        $city = trim($_POST['city'] ?? '');
        $status = $_POST['status'] === 'published' ? 'published' : 'draft';

        $stmt = db()->prepare("INSERT INTO events(title,slug,short_description,description,event_date,start_time,end_time,venue,city,status) VALUES(?,?,?,?,?,?,?,?,?,?)");
        $stmt->execute([$title,$slug,$short,$description,$date,$start,$end,$venue,$city,$status]);
        $eventId = (int)db()->lastInsertId();

        $names = $_POST['ticket_name'] ?? [];
        $prices = $_POST['ticket_price'] ?? [];
        $qtys = $_POST['ticket_quantity'] ?? [];
        $ticketStmt = db()->prepare("INSERT INTO ticket_types(event_id,name,price,quantity) VALUES(?,?,?,?)");
        foreach ($names as $i => $name) {
            if (trim($name) === '') continue;
            $ticketStmt->execute([$eventId,trim($name),(float)($prices[$i] ?? 0),(int)($qtys[$i] ?? 0)]);
        }

        flash('success','Event created successfully.');
        redirect(site_url('admin/events.php'));
    }

    if ($action === 'delete') {
        $stmt = db()->prepare("DELETE FROM events WHERE id=?");
        $stmt->execute([(int)$_POST['id']]);
        flash('success','Event deleted.');
        redirect(site_url('admin/events.php'));
    }
}

$events = db()->query("SELECT e.*, (SELECT COUNT(*) FROM registrations r WHERE r.event_id=e.id) registrations FROM events e ORDER BY event_date DESC")->fetchAll();
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Events | HatchesHouse</title><script src="https://cdn.tailwindcss.com"></script></head>
<body class="bg-zinc-50">
<header class="border-b bg-white"><div class="mx-auto flex max-w-7xl justify-between px-5 py-4"><a href="<?= site_url('admin/dashboard.php') ?>" class="font-black">← Dashboard</a><a href="<?= site_url('admin/logout.php') ?>" class="font-bold">Logout</a></div></header>
<main class="mx-auto max-w-7xl px-5 py-8">
<?php show_flash(); ?>
<div class="grid gap-8 lg:grid-cols-[.8fr_1.2fr]">
<section class="rounded-3xl border bg-white p-6">
<h1 class="text-2xl font-black">Create Event</h1>
<form method="post" class="mt-6 space-y-3">
<input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="create">
<input name="title" required placeholder="Event title" class="w-full rounded-xl border px-3 py-3">
<input name="short_description" placeholder="Short description" class="w-full rounded-xl border px-3 py-3">
<textarea name="description" required rows="5" placeholder="Full description" class="w-full rounded-xl border px-3 py-3"></textarea>
<div class="grid grid-cols-2 gap-3"><input name="event_date" type="date" required class="rounded-xl border px-3 py-3"><input name="start_time" type="time" required class="rounded-xl border px-3 py-3"></div>
<div class="grid grid-cols-2 gap-3"><input name="end_time" type="time" class="rounded-xl border px-3 py-3"><input name="venue" required placeholder="Venue" class="rounded-xl border px-3 py-3"></div>
<input name="city" value="Accra" placeholder="City" class="w-full rounded-xl border px-3 py-3">
<select name="status" class="w-full rounded-xl border px-3 py-3"><option value="draft">Draft</option><option value="published">Published</option></select>
<div class="border-t pt-4"><div class="mb-3 font-bold">Ticket types</div>
<?php for($i=0;$i<3;$i++): ?><div class="mb-2 grid grid-cols-3 gap-2">
<input name="ticket_name[]" placeholder="Regular/VIP" class="rounded-xl border px-3 py-2">
<input name="ticket_price[]" type="number" step="0.01" placeholder="Price" class="rounded-xl border px-3 py-2">
<input name="ticket_quantity[]" type="number" placeholder="Qty" class="rounded-xl border px-3 py-2">
</div><?php endfor; ?></div>
<button class="w-full rounded-full bg-black px-5 py-3 font-bold text-white">Create Event</button>
</form></section>
<section>
<h2 class="text-2xl font-black">Events</h2>
<div class="mt-4 space-y-3">
<?php foreach($events as $event): ?><div class="flex flex-col gap-4 rounded-2xl border bg-white p-5 sm:flex-row sm:items-center sm:justify-between">
<div><div class="font-black"><?= e($event['title']) ?></div><div class="text-sm text-zinc-500"><?= date('d M Y',strtotime($event['event_date'])) ?> · <?= e($event['status']) ?> · <?= (int)$event['registrations'] ?> registrations</div></div>
<form method="post" onsubmit="return confirm('Delete this event?')"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$event['id'] ?>"><button class="rounded-full border border-red-200 px-4 py-2 text-xs font-bold text-red-600">Delete</button></form>
</div><?php endforeach; ?>
</div></section></div></main></body></html>
