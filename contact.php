<?php
require_once __DIR__ . '/config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf($_POST['csrf'] ?? null);
    $stmt = db()->prepare("INSERT INTO contact_messages(name,email,phone,message) VALUES(?,?,?,?)");
    $stmt->execute([
        trim($_POST['name'] ?? ''),
        trim($_POST['email'] ?? ''),
        trim($_POST['phone'] ?? ''),
        trim($_POST['message'] ?? '')
    ]);
    flash('success', 'Thanks. Your message has been received.');
    redirect(site_url('contact.php'));
}

$meta = base_meta('Contact HatchesHouse', 'Contact HatchesHouse Event & Media in Ghana.', site_url('contact.php'), site_url('assets/images/logo.jpg'));
require __DIR__ . '/includes/header.php';
?>
<section class="bg-black text-white">
    <div class="mx-auto max-w-7xl px-5 py-20 lg:px-8">
        <p class="text-xs font-black uppercase tracking-[.25em] text-zinc-400">Get in touch</p>
        <h1 class="mt-3 text-5xl font-black sm:text-7xl">Let's create something memorable.</h1>
    </div>
</section>
<section class="mx-auto grid max-w-7xl gap-12 px-5 py-16 lg:grid-cols-2 lg:px-8">
    <div>
        <h2 class="text-3xl font-black">HatchesHouse Event & Media</h2>
        <div class="mt-8 space-y-5 text-zinc-600">
            <a class="block hover:text-black" href="tel:+233531607157"><b>Phone</b><br>+233 53 160 7157</a>
            <a class="block hover:text-black" href="mailto:hatcheshouse2@gmail.com"><b>Email</b><br>hatcheshouse2@gmail.com</a>
            <div><b>Instagram</b><br>@hatcheshouse</div>
            <div><b>TikTok</b><br>@hatcheshouse1</div>
            <div><b>Location</b><br>Ghana</div>
        </div>
    </div>
    <div>
        <?php show_flash(); ?>
        <form method="post" class="space-y-4">
            <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
            <input name="name" required placeholder="Full name" class="w-full rounded-2xl border border-zinc-200 px-4 py-3 outline-none focus:border-black">
            <input name="email" required type="email" placeholder="Email address" class="w-full rounded-2xl border border-zinc-200 px-4 py-3 outline-none focus:border-black">
            <input name="phone" placeholder="Phone number" class="w-full rounded-2xl border border-zinc-200 px-4 py-3 outline-none focus:border-black">
            <textarea name="message" required rows="6" placeholder="Tell us about your event..." class="w-full rounded-2xl border border-zinc-200 px-4 py-3 outline-none focus:border-black"></textarea>
            <button class="w-full rounded-full bg-black px-6 py-4 text-sm font-black text-white">Send Message</button>
        </form>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
