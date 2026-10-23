<?php
require_once __DIR__ . '/config/config.php';
$meta = base_meta('About HatchesHouse | Event & Media', 'Learn about the vision and mission behind HatchesHouse.', site_url('about.php'), site_url('assets/images/logo.jpg'));
require __DIR__ . '/includes/header.php';
?>
<section class="bg-black text-white">
    <div class="mx-auto max-w-7xl px-5 py-20 lg:px-8">
        <p class="text-xs font-black uppercase tracking-[.25em] text-zinc-400">About HatchesHouse</p>
        <h1 class="mt-3 max-w-4xl text-5xl font-black tracking-tight sm:text-7xl">Premium service is more than an event. It's how people feel.</h1>
    </div>
</section>
<section class="mx-auto max-w-5xl px-5 py-20 lg:px-8">
    <p class="text-xl leading-9 text-zinc-600 sm:text-2xl">
        HatchesHouse is an event house focused on creating seamless, memorable and premium experiences.
        We go beyond organizing events by focusing on how clients feel before, during and after every experience.
    </p>
    <div class="mt-16 grid gap-6 md:grid-cols-2">
        <div class="rounded-3xl bg-black p-8 text-white">
            <div class="text-xs font-black uppercase tracking-[.25em] text-zinc-400">Vision</div>
            <p class="mt-6 text-2xl font-bold leading-9">To be Ghana’s leading event house by 2030, known for creating stress-free, unforgettable experiences for our clients.</p>
        </div>
        <div class="rounded-3xl border border-zinc-200 p-8">
            <div class="text-xs font-black uppercase tracking-[.25em] text-zinc-500">Mission</div>
            <p class="mt-6 text-2xl font-bold leading-9">To design and deliver premium events with creativity, excellence, and zero stress for our clients. We turn moments into memories through seamless planning, beautiful experiences, and exceptional service.</p>
        </div>
    </div>
    <div class="mt-6 rounded-3xl bg-zinc-50 p-8">
        <div class="text-xs font-black uppercase tracking-[.25em] text-zinc-500">Our premium promise</div>
        <p class="mt-5 max-w-3xl text-xl font-semibold leading-9">HatchesHouse premium service is defined by how we make clients feel before, during, and after every event. Our focus is to create a stress-free environment and ensure total client satisfaction.</p>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
