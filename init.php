<?php
/**
 * init.php
 * این فایل، نقطه شروع و راه‌انداز اصلی کل سیستم است.
 * تمام فایل‌های هسته در اینجا بارگذاری می‌شوند.
 */

// نمایش خطاها برای عیب‌یابی آسان‌تر (در حالت نهایی می‌توانید این خط را حذف کنید)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ۱. بارگذاری فایل پیکربندی اصلی
require_once __DIR__ . '/config.php';

// ۲. بارگذاری فایل اتصال به پایگاه داده
require_once __DIR__ . '/includes/db.php';

// ۳. بارگذاری توابع عمومی سایت
require_once __DIR__ . '/includes/functions.php';

// ۴. بارگذاری و راه‌اندازی سیستم پلاگین
require_once __DIR__ . '/includes/plugin_manager.php';
load_active_plugins(); // این تابع پلاگین‌های فعال را بارگذاری می‌کند
