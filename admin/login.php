<?php
require_once __DIR__ . '/../config/config.php';

if (is_admin()) redirect(site_url('admin/dashboard.php'));

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf($_POST['csrf'] ?? null);
    $stmt = db()->prepare("SELECT * FROM admins WHERE email=? LIMIT 1");
    $stmt->execute([trim($_POST['email'] ?? '')]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($_POST['password'] ?? '', $admin['password_hash'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['name'];
        redirect(site_url('admin/dashboard.php'));
    }
    $error = 'Invalid email or password.';
}
?>
<!doctype html>
<html lang="en"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin Login | HatchesHouse</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex min-h-screen items-center justify-center bg-black px-5">
<div class="w-full max-w-md rounded-3xl bg-white p-8">
    <img src="<?= site_url('assets/images/logo.jpg') ?>" class="mx-auto mb-6 h-20 w-20 rounded-2xl object-cover">
    <h1 class="text-center text-3xl font-black">Admin Login</h1>
    <?php if ($error): ?><div class="mt-5 rounded-2xl bg-zinc-100 p-3 text-sm"><?= e($error) ?></div><?php endif; ?>
    <form method="post" class="mt-7 space-y-4">
        <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
        <input name="email" type="email" required placeholder="Admin email" class="w-full rounded-2xl border px-4 py-3">
        <input name="password" type="password" required placeholder="Password" class="w-full rounded-2xl border px-4 py-3">
        <button class="w-full rounded-full bg-black px-5 py-4 font-bold text-white">Sign In</button>
    </form>
    <p class="mt-5 text-center text-xs text-zinc-400">Change the seeded password immediately after installation.</p>
</div>
</body></html>
