<?php
require_once __DIR__ . '/config/config.php';
$meta = base_meta('What We Do | HatchesHouse', 'Explore HatchesHouse event planning and management services.', site_url('services.php'), site_url('assets/images/logo.jpg'));
require __DIR__ . '/includes/header.php';
?>
<section class="mx-auto max-w-7xl px-5 py-20 lg:px-8">
    <p class="text-xs font-black uppercase tracking-[.25em] text-zinc-500">Our services</p>
    <h1 class="mt-3 max-w-4xl text-5xl font-black tracking-tight sm:text-7xl">Everything your event needs to feel effortless.</h1>
    <div class="mt-14 grid gap-5 md:grid-cols-2">
        <?php foreach ([
            ['Event Planning','Creative concepts, structured planning, timelines and coordination from the first idea to the final moment.'],
            ['Event Management','On-ground coordination and professional execution so you can enjoy the event instead of managing it.'],
            ['Weddings & Celebrations','Personalized experiences for weddings, birthdays, anniversaries and other important milestones.'],
            ['Corporate Events','Professional experiences for launches, conferences, meetings, brand activations and corporate gatherings.'],
        ] as $i => $service): ?>
            <div class="rounded-3xl border border-zinc-200 p-8 hover:bg-black hover:text-white">
                <div class="text-xs font-black uppercase tracking-[.25em] text-zinc-400">0<?= $i+1 ?></div>
                <h2 class="mt-8 text-3xl font-black"><?= e($service[0]) ?></h2>
                <p class="mt-4 max-w-xl leading-7 text-zinc-500"><?= e($service[1]) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
