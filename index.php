<?php
require_once __DIR__ . '/config/config.php';

$stmt = db()->prepare("
    SELECT e.*, 
           (SELECT MIN(price) FROM ticket_types tt WHERE tt.event_id = e.id AND tt.is_active = 1) AS starting_price
    FROM events e
    WHERE e.status = 'published' AND e.event_date >= CURDATE()
    ORDER BY e.event_date ASC
    LIMIT 6
");
$stmt->execute();
$events = $stmt->fetchAll();

$meta = base_meta(
    'HatchesHouse Event & Media | Creating Moments. Designing Memories.',
    'Ghanaian event house delivering premium events, seamless planning and unforgettable experiences.',
    site_url(),
    site_url('assets/images/logo.jpg')
);
require __DIR__ . '/includes/header.php';
?>

<section class="relative overflow-hidden bg-black text-white">
    <div class="absolute inset-0 opacity-20">
        <div class="absolute -left-20 top-10 h-72 w-72 rounded-full bg-white blur-3xl"></div>
        <div class="absolute -right-20 bottom-0 h-96 w-96 rounded-full bg-zinc-500 blur-3xl"></div>
    </div>
    <div class="relative mx-auto grid min-h-[78vh] max-w-7xl items-center gap-12 px-5 py-20 lg:grid-cols-2 lg:px-8">
        <div>
            <div class="mb-7 inline-flex items-center gap-2 rounded-full border border-white/20 px-4 py-2 text-xs font-bold uppercase tracking-[.2em] text-zinc-300">
                HatchesHouse Event & Media
            </div>
            <h1 class="max-w-4xl text-5xl font-black leading-[.95] tracking-[-.04em] sm:text-6xl lg:text-8xl">
                Creating moments.<br>
                <span class="text-zinc-400">Designing memories.</span>
            </h1>
            <p class="mt-7 max-w-xl text-lg leading-8 text-zinc-300">
                Premium events, seamless planning and unforgettable experiences.
                We make every moment feel effortless before, during and after the event.
            </p>
            <div class="mt-9 flex flex-col gap-3 sm:flex-row">
                <a href="<?= site_url('events.php') ?>" class="rounded-full bg-white px-7 py-4 text-center text-sm font-black text-black hover:bg-zinc-200">
                    Explore Upcoming Events
                </a>
                <a href="<?= site_url('contact.php') ?>" class="rounded-full border border-white/30 px-7 py-4 text-center text-sm font-bold text-white hover:bg-white hover:text-black">
                    Work With Us
                </a>
            </div>
            <div class="mt-10 flex flex-wrap gap-x-7 gap-y-3 text-sm text-zinc-400">
                <span>● Premium experiences</span>
                <span>● Stress-free planning</span>
                <span>● Ghana</span>
            </div>
        </div>

        <div class="relative mx-auto w-full max-w-md">
            <div class="rounded-[2rem] border border-white/10 bg-white p-4 shadow-2xl">
                <img src="<?= site_url('assets/images/logo.jpg') ?>" alt="HatchesHouse Event & Media logo" class="w-full rounded-[1.5rem] object-cover">
                <div class="flex items-center justify-between px-2 pb-1 pt-5">
                    <div>
                        <div class="text-xs font-bold uppercase tracking-[.2em] text-zinc-500">Event House</div>
                        <div class="mt-1 text-xl font-black text-black">Moments that matter.</div>
                    </div>
                    <div class="rounded-full bg-black px-3 py-2 text-xs font-bold text-white">GHANA</div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="mx-auto max-w-7xl px-5 py-20 lg:px-8">
    <div class="flex flex-col justify-between gap-5 md:flex-row md:items-end">
        <div>
            <p class="text-xs font-black uppercase tracking-[.25em] text-zinc-500">Don't miss out</p>
            <h2 class="mt-2 text-4xl font-black tracking-tight sm:text-5xl">Upcoming events</h2>
        </div>
        <a href="<?= site_url('events.php') ?>" class="text-sm font-bold underline underline-offset-4">View all events →</a>
    </div>

    <?php if (!$events): ?>
        <div class="mt-10 rounded-3xl border border-dashed border-zinc-300 p-10 text-center">
            <p class="text-lg font-bold">New experiences are coming.</p>
            <p class="mt-2 text-sm text-zinc-500">Check back soon for HatchesHouse events and ticket releases.</p>
        </div>
    <?php else: ?>
        <div class="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($events as $event): ?>
                <article class="group overflow-hidden rounded-3xl border border-zinc-200 bg-white transition hover:-translate-y-1 hover:shadow-xl">
                    <div class="aspect-[4/3] overflow-hidden bg-zinc-100">
                        <?php if (!empty($event['cover_image'])): ?>
                            <img src="<?= site_url('assets/images/events/' . e($event['cover_image'])) ?>" alt="" class="h-full w-full object-cover grayscale transition duration-500 group-hover:grayscale-0 group-hover:scale-105">
                        <?php else: ?>
                            <div class="flex h-full items-center justify-center bg-black text-6xl font-black text-white">HH</div>
                        <?php endif; ?>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-between gap-4">
                            <span class="rounded-full bg-black px-3 py-1 text-xs font-bold text-white"><?= date('d M Y', strtotime($event['event_date'])) ?></span>
                            <?php if ($event['starting_price'] !== null): ?>
                                <span class="text-sm font-black"><?= money((float)$event['starting_price']) ?>+</span>
                            <?php endif; ?>
                        </div>
                        <h3 class="mt-5 text-2xl font-black"><?= e($event['title']) ?></h3>
                        <p class="mt-2 line-clamp-2 text-sm leading-6 text-zinc-500"><?= e($event['short_description']) ?></p>
                        <a href="<?= site_url('event.php?slug=' . urlencode($event['slug'])) ?>" class="mt-6 inline-flex rounded-full border border-black px-5 py-2.5 text-sm font-bold hover:bg-black hover:text-white">
                            View Event
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<section class="border-y border-zinc-200 bg-zinc-50">
    <div class="mx-auto max-w-7xl px-5 py-20 lg:px-8">
        <div class="max-w-2xl">
            <p class="text-xs font-black uppercase tracking-[.25em] text-zinc-500">What we do</p>
            <h2 class="mt-3 text-4xl font-black tracking-tight sm:text-5xl">We make events feel effortless.</h2>
        </div>
        <div class="mt-12 grid gap-4 md:grid-cols-2 lg:grid-cols-4">
            <?php
            $services = [
                ['01', 'Event Planning', 'From concept to execution, we coordinate the details that make your event work.'],
                ['02', 'Event Management', 'Professional coordination before and during your event for a smooth experience.'],
                ['03', 'Private Experiences', 'Birthdays, celebrations, parties and special moments designed around you.'],
                ['04', 'Corporate Events', 'Polished experiences for launches, meetings, conferences and corporate gatherings.'],
            ];
            foreach ($services as [$num, $name, $desc]):
            ?>
                <div class="rounded-3xl border border-zinc-200 bg-white p-7">
                    <span class="text-xs font-black text-zinc-400"><?= $num ?></span>
                    <h3 class="mt-8 text-xl font-black"><?= $name ?></h3>
                    <p class="mt-3 text-sm leading-6 text-zinc-500"><?= $desc ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="mx-auto max-w-7xl px-5 py-20 lg:px-8">
    <div class="grid gap-12 lg:grid-cols-[1fr_1.3fr] lg:items-center">
        <div>
            <p class="text-xs font-black uppercase tracking-[.25em] text-zinc-500">Our standard</p>
            <h2 class="mt-3 text-4xl font-black tracking-tight sm:text-5xl">Premium service is how we make you feel.</h2>
        </div>
        <div class="space-y-6 text-lg leading-8 text-zinc-600">
            <p>HatchesHouse premium service is defined by how we make clients feel before, during and after every event.</p>
            <p>Our focus is to create a stress-free environment and ensure total client satisfaction through creativity, excellence, beautiful experiences and exceptional service.</p>
            <a href="<?= site_url('about.php') ?>" class="inline-flex rounded-full bg-black px-6 py-3 text-sm font-bold text-white">Discover HatchesHouse</a>
        </div>
    </div>
</section>

<section class="bg-black text-white">
    <div class="mx-auto max-w-7xl px-5 py-20 text-center lg:px-8">
        <p class="text-xs font-black uppercase tracking-[.25em] text-zinc-500">Stay connected</p>
        <h2 class="mt-3 text-4xl font-black sm:text-6xl">Follow the experience.</h2>
        <p class="mx-auto mt-5 max-w-xl text-zinc-400">See what HatchesHouse is creating next.</p>
        <div class="mt-9 flex flex-col justify-center gap-3 sm:flex-row">
            <a href="https://www.instagram.com/hatcheshouse" target="_blank" rel="noopener" class="rounded-full border border-white/20 px-7 py-3 text-sm font-bold hover:bg-white hover:text-black">Instagram · @hatcheshouse</a>
            <a href="https://www.tiktok.com/@hatcheshouse1" target="_blank" rel="noopener" class="rounded-full border border-white/20 px-7 py-3 text-sm font-bold hover:bg-white hover:text-black">TikTok · @hatcheshouse1</a>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
