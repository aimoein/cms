<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'cp62215_cms');
define('DB_PASS', 'zh884dzh884d');
define('DB_NAME', 'cp62215_cms');

// Site URL
define('SITE_URL', 'https://nicedev.ir');

// Start the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// این خط را به انتهای فایل اضافه کنید
// این کار باعث می‌شود توابع پلاگین مانند do_action در همه جا در دسترس باشند
require_once __DIR__ . '/includes/plugin_manager.php';
?>