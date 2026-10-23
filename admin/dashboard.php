<?php
require_once __DIR__ . '/../config/config.php';
require_admin();

$stats = [
    'events' => (int)db()->query("SELECT COUNT(*) FROM events")->fetchColumn(),
    'registrations' => (int)db()->query("SELECT COUNT(*) FROM registrations")->fetchColumn(),
    'paid' => (int)db()->query("SELECT COUNT(*) FROM registrations WHERE status='paid'")->fetchColumn(),
    'revenue' => (float)db()->query("SELECT COALESCE(SUM(total_amount),0) FROM registrations WHERE status='paid'")->fetchColumn(),
];
?>
<!doctype html><html lang="en"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Dashboard | HatchesHouse</title><script src="https://cdn.tailwindcss.com"></script></head>
<body class="min-h-screen bg-zinc-50 text-black">
<header class="border-b bg-white"><div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4">
<a href="<?= site_url('admin/dashboard.php') ?>" class="font-black">HATCHESHOUSE ADMIN</a>
<a href="<?= site_url('admin/logout.php') ?>" class="rounded-full bg-black px-4 py-2 text-sm font-bold text-white">Logout</a>
</div></header>
<main class="mx-auto max-w-7xl px-5 py-10">
<h1 class="text-4xl font-black">Dashboard</h1>
<p class="mt-2 text-zinc-500">Welcome, <?= e($_SESSION['admin_name'] ?? 'Admin') ?>.</p>
<div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
<?php foreach ([
['Events',$stats['events'],'admin/events.php'],
['Registrations',$stats['registrations'],'admin/registrations.php'],
['Paid Tickets',$stats['paid'],'admin/registrations.php?status=paid'],
['Revenue',money($stats['revenue']),'admin/registrations.php?status=paid']
] as $card): ?>
<a href="<?= site_url($card[2]) ?>" class="rounded-3xl border bg-white p-6 hover:border-black">
<div class="text-xs font-black uppercase tracking-widest text-zinc-400"><?= $card[0] ?></div>
<div class="mt-4 text-4xl font-black"><?= e((string)$card[1]) ?></div>
</a>
<?php endforeach; ?>
</div>
<div class="mt-8 flex flex-wrap gap-3">
<a class="rounded-full bg-black px-5 py-3 text-sm font-bold text-white" href="<?= site_url('admin/events.php') ?>">Manage Events</a>
<a class="rounded-full border border-black px-5 py-3 text-sm font-bold" href="<?= site_url('admin/registrations.php') ?>">View Registrations</a>
<a class="rounded-full border border-black px-5 py-3 text-sm font-bold" href="<?= site_url('admin/checkin.php') ?>">Check-in</a>
</div>
</main></body></html>
