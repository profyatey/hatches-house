<?php
require_once __DIR__ . '/../config/config.php';
$meta = $meta ?? base_meta(
    APP_NAME,
    'Premium events, seamless planning and unforgettable experiences in Ghana.',
    site_url(),
    site_url('assets/images/logo.jpg')
);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($meta['title']) ?></title>
    <meta name="description" content="<?= e($meta['description']) ?>">
    <meta name="theme-color" content="#000000">
    <link rel="canonical" href="<?= e($meta['url']) ?>">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= e($meta['title']) ?>">
    <meta property="og:description" content="<?= e($meta['description']) ?>">
    <meta property="og:url" content="<?= e($meta['url']) ?>">
    <meta property="og:image" content="<?= e($meta['image']) ?>">
    <meta property="og:site_name" content="HatchesHouse Event & Media">

    <!-- Twitter/X -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($meta['title']) ?>">
    <meta name="twitter:description" content="<?= e($meta['description']) ?>">
    <meta name="twitter:image" content="<?= e($meta['image']) ?>">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body class="min-h-screen bg-white text-black antialiased">
<header class="sticky top-0 z-50 border-b border-black/10 bg-white/95 backdrop-blur">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4 lg:px-8">
        <a href="<?= site_url() ?>" class="flex items-center gap-3">
            <img src="<?= site_url('assets/images/logo.jpg') ?>" alt="HatchesHouse" class="h-11 w-11 rounded-xl object-cover">
            <div class="hidden sm:block">
                <div class="text-sm font-black tracking-tight">HATCHESHOUSE</div>
                <div class="text-[10px] font-medium uppercase tracking-[.25em] text-zinc-500">Event & Media</div>
            </div>
        </a>

        <nav class="hidden items-center gap-7 text-sm font-semibold md:flex">
            <a class="hover:underline" href="<?= site_url() ?>">Home</a>
            <a class="hover:underline" href="<?= site_url('events.php') ?>">Events</a>
            <a class="hover:underline" href="<?= site_url('services.php') ?>">What We Do</a>
            <a class="hover:underline" href="<?= site_url('about.php') ?>">About</a>
            <a class="hover:underline" href="<?= site_url('contact.php') ?>">Contact</a>
        </nav>

        <a href="<?= site_url('events.php') ?>" class="rounded-full bg-black px-5 py-2.5 text-sm font-bold text-white transition hover:bg-zinc-800">
            Get Tickets
        </a>
    </div>
</header>
<main>
