<?php
require_once __DIR__ . '/config/config.php';

$slug = trim($_GET['slug'] ?? '');
$stmt = db()->prepare("SELECT * FROM events WHERE slug=? AND status='published' LIMIT 1");
$stmt->execute([$slug]);
$event = $stmt->fetch();

if (!$event) {
    http_response_code(404);
    exit('Event not found.');
}

$ticketStmt = db()->prepare("SELECT * FROM ticket_types WHERE event_id=? AND is_active=1 ORDER BY price ASC");
$ticketStmt->execute([$event['id']]);
$tickets = $ticketStmt->fetchAll();

$meta = base_meta(
    $event['title'] . ' | HatchesHouse',
    $event['short_description'] ?: 'Register for this HatchesHouse event.',
    site_url('event.php?slug=' . urlencode($event['slug'])),
    $event['cover_image'] ? site_url('assets/images/events/' . $event['cover_image']) : site_url('assets/images/logo.jpg')
);
require __DIR__ . '/includes/header.php';
?>
<section class="bg-black text-white">
    <div class="mx-auto max-w-7xl px-5 py-14 lg:px-8">
        <a href="<?= site_url('events.php') ?>" class="text-sm font-bold text-zinc-400 hover:text-white">← Back to events</a>
        <div class="mt-10 grid gap-12 lg:grid-cols-[1.2fr_.8fr] lg:items-end">
            <div>
                <div class="text-xs font-black uppercase tracking-[.25em] text-zinc-400"><?= date('l, d F Y', strtotime($event['event_date'])) ?></div>
                <h1 class="mt-4 text-5xl font-black tracking-tight sm:text-7xl"><?= e($event['title']) ?></h1>
                <p class="mt-5 max-w-2xl text-lg leading-8 text-zinc-300"><?= e($event['short_description']) ?></p>
            </div>
            <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
                <div class="text-sm text-zinc-400">Venue</div>
                <div class="mt-1 text-xl font-bold"><?= e($event['venue']) ?></div>
                <div class="mt-4 text-sm text-zinc-400">Time</div>
                <div class="mt-1 font-bold"><?= e(date('g:i A', strtotime($event['start_time']))) ?></div>
            </div>
        </div>
    </div>
</section>

<section class="mx-auto max-w-7xl px-5 py-16 lg:px-8">
    <div class="grid gap-12 lg:grid-cols-[1.2fr_.8fr]">
        <div>
            <?php if ($event['cover_image']): ?>
                <img src="<?= site_url('assets/images/events/' . e($event['cover_image'])) ?>" alt="<?= e($event['title']) ?>" class="w-full rounded-3xl object-cover grayscale">
            <?php endif; ?>
            <div class="prose prose-zinc mt-10 max-w-none">
                <?= nl2br(e($event['description'])) ?>
            </div>
        </div>

        <aside>
            <div class="sticky top-28 rounded-3xl border border-zinc-200 bg-zinc-50 p-6">
                <h2 class="text-2xl font-black">Choose your ticket</h2>
                <p class="mt-2 text-sm text-zinc-500">Secure your place for this HatchesHouse experience.</p>

                <form action="<?= site_url('events/register.php') ?>" method="post" class="mt-7 space-y-4">
                    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
                    <input type="hidden" name="event_id" value="<?= (int)$event['id'] ?>">

                    <?php foreach ($tickets as $ticket): ?>
                        <label class="block cursor-pointer rounded-2xl border border-zinc-200 bg-white p-4 hover:border-black">
                            <div class="flex items-center justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="ticket_type_id" value="<?= (int)$ticket['id'] ?>" required class="h-4 w-4 accent-black">
                                    <div>
                                        <div class="font-bold"><?= e($ticket['name']) ?></div>
                                        <div class="text-xs text-zinc-500"><?= max(0, (int)$ticket['quantity'] - (int)$ticket['sold']) ?> remaining</div>
                                    </div>
                                </div>
                                <div class="font-black"><?= money((float)$ticket['price']) ?></div>
                            </div>
                        </label>
                    <?php endforeach; ?>

                    <input name="name" required placeholder="Full name" class="w-full rounded-2xl border border-zinc-200 bg-white px-4 py-3 outline-none focus:border-black">
                    <input name="email" type="email" required placeholder="Email address" class="w-full rounded-2xl border border-zinc-200 bg-white px-4 py-3 outline-none focus:border-black">
                    <input name="phone" required placeholder="Phone number" class="w-full rounded-2xl border border-zinc-200 bg-white px-4 py-3 outline-none focus:border-black">
                    <input name="quantity" type="number" min="1" max="10" value="1" required class="w-full rounded-2xl border border-zinc-200 bg-white px-4 py-3 outline-none focus:border-black">

                    <button class="w-full rounded-full bg-black px-6 py-4 text-sm font-black text-white hover:bg-zinc-800">Continue to Payment</button>
                    <p class="text-center text-xs text-zinc-500">Payments are processed securely through Paystack/MoMo.</p>
                </form>
            </div>
        </aside>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
