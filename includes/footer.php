</main>
<footer class="border-t border-black bg-black text-white">
    <div class="mx-auto grid max-w-7xl gap-10 px-5 py-14 md:grid-cols-4 lg:px-8">
        <div class="md:col-span-2">
            <div class="mb-4 flex items-center gap-3">
                <img src="<?= site_url('assets/images/logo.jpg') ?>" alt="HatchesHouse" class="h-12 w-12 rounded-xl object-cover grayscale">
                <div>
                    <div class="font-black">HATCHESHOUSE</div>
                    <div class="text-xs uppercase tracking-[.2em] text-zinc-400">Event & Media</div>
                </div>
            </div>
            <p class="max-w-xl text-sm leading-7 text-zinc-400">
                Premium events, seamless planning and unforgettable experiences.
                We turn moments into memories with creativity, excellence and zero stress.
            </p>
        </div>

        <div>
            <h3 class="mb-4 text-sm font-bold uppercase tracking-widest">Explore</h3>
            <div class="space-y-3 text-sm text-zinc-400">
                <a class="block hover:text-white" href="<?= site_url('events.php') ?>">Upcoming Events</a>
                <a class="block hover:text-white" href="<?= site_url('services.php') ?>">What We Do</a>
                <a class="block hover:text-white" href="<?= site_url('about.php') ?>">About Us</a>
                <a class="block hover:text-white" href="<?= site_url('contact.php') ?>">Contact</a>
            </div>
        </div>

        <div>
            <h3 class="mb-4 text-sm font-bold uppercase tracking-widest">Connect</h3>
            <div class="space-y-3 text-sm text-zinc-400">
                <a class="block hover:text-white" href="https://www.instagram.com/hatcheshouse" target="_blank" rel="noopener">Instagram · @hatcheshouse</a>
                <a class="block hover:text-white" href="https://www.tiktok.com/@hatcheshouse1" target="_blank" rel="noopener">TikTok · @hatcheshouse1</a>
                <a class="block hover:text-white" href="mailto:hatcheshouse2@gmail.com">hatcheshouse2@gmail.com</a>
                <a class="block hover:text-white" href="tel:+233531607157">+233 53 160 7157</a>
            </div>
        </div>
    </div>
    <div class="border-t border-white/10">
        <div class="mx-auto flex max-w-7xl flex-col gap-2 px-5 py-6 text-xs text-zinc-500 sm:flex-row sm:items-center sm:justify-between lg:px-8">
            <span>© <?= date('Y') ?> HatchesHouse Event & Media. All rights reserved.</span>
            <span>Ghana · Creating moments. Designing memories.</span>
        </div>
    </div>
</footer>
</body>
</html>
