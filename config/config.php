<?php
declare(strict_types=1);

session_start();

define('APP_NAME', 'HatchesHouse Event & Media');
define('APP_URL', 'http://localhost/hatcheshouse'); // Change on hosting
define('CURRENCY', 'GHS');

define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'hatcheshouse');
define('DB_USER', 'root');
define('DB_PASS', '');

define('PAYSTACK_SECRET_KEY', 'sk_test_keys');
define('PAYSTACK_PUBLIC_KEY', 'pk_test_keys');

date_default_timezone_set('Africa/Accra');

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/../includes/helpers.php';
