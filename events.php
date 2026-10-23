<?php
require_once __DIR__ . '/config/config.php';

$stmt = db()->query("
    SELECT e.*, 
           (SELECT MIN(price) FROM ticket_types tt WHERE tt.event_id=e.id AND tt.is_active=1) AS starting_price
    FROM events e
    WHERE e.status='published'
    ORDER BY e.event_date ASC, e.start_time ASC
");
$events = $stmt->fetchAll();

$meta = base_meta('Upcoming Events | HatchesHouse', 'Discover upcoming HatchesHouse events and secure your tickets online.', site_url('events.php'), site_url('assets/images/logo.jpg'));
require __DIR__ . '/includes/header.php';
?>
<section class="bg-black text-white">
    <div class="mx-auto max-w-7xl px-5 py-20 lg:px-8">
        <p class="text-xs font-black uppercase tracking-[.25em] text-zinc-400">HatchesHouse calendar</p>
        <h1 class="mt-3 text-5xl font-black tracking-tight sm:text-7xl">Upcoming events.</h1>
        <p class="mt-5 max-w-2xl text-zinc-400">Find your next experience and reserve your place with secure online ticketing.</p>
    </div>
</section>
<section class="mx-auto max-w-7xl px-5 py-16 lg:px-8">
    <?php if (!$events): ?>
        <div class="rounded-3xl border border-dashed border-zinc-300 p-12 text-center">
            <h2 class="text-2xl font-black">No published events yet.</h2>
            <p class="mt-2 text-zinc-500">The HatchesHouse team will announce new experiences here.</p>
        </div>
    <?php else: ?>
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($events as $event): ?>
                <article class="overflow-hidden rounded-3xl border border-zinc-200">
                    <div class="aspect-[4/3] bg-black">
                        <?php if ($event['cover_image']): ?>
                            <img src="<?= site_url('assets/images/events/' . e($event['cover_image'])) ?>" alt="" class="h-full w-full object-cover grayscale">
                        <?php else: ?>
                            <div class="flex h-full items-center justify-center text-6xl font-black text-white">HH</div>
                        <?php endif; ?>
                    </div>
                    <div class="p-6">
                        <div class="text-xs font-black uppercase tracking-widest text-zinc-500"><?= date('l, d F Y', strtotime($event['event_date'])) ?></div>
                        <h2 class="mt-3 text-2xl font-black"><?= e($event['title']) ?></h2>
                        <p class="mt-2 text-sm leading-6 text-zinc-500"><?= e($event['short_description']) ?></p>
                        <div class="mt-5 text-sm font-semibold text-zinc-600"><?= e($event['venue']) ?><?= $event['city'] ? ', ' . e($event['city']) : '' ?></div>
                        <div class="mt-6 flex items-center justify-between gap-3">
                            <span class="font-black"><?= $event['starting_price'] !== null ? money((float)$event['starting_price']) . '+' : 'Free' ?></span>
                            <a href="<?= site_url('event.php?slug=' . urlencode($event['slug'])) ?>" class="rounded-full bg-black px-5 py-3 text-sm font-bold text-white">View & Register</a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
