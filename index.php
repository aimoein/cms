<?php
/**
 * index.php
 * روتر اصلی سایت که تمام درخواست‌های فرانت‌اند را مدیریت می‌کند.
 */

// راه‌اندازی کل سیستم با فراخوانی فایل init.php
require_once __DIR__ . '/init.php';
require_once __DIR__ . '/includes/functions.php';

// روتر ساده
$request_uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

// اگر در ساب‌فولدر نصب شده، نام آن را حذف کنید. مثال:
// if (strpos($request_uri, 'your-folder-name/') === 0) {
//     $request_uri = substr($request_uri, strlen('your-folder-name/'));
// }

$slug = $request_uri;

$home_page_id = get_setting('home_page_id');
if (empty($slug)) {
    if (!empty($home_page_id)) {
        $post = get_post_by_id($home_page_id);
        if ($post) {
            require 'templates/page.php';
            exit;
        }
    }
    require 'templates/home.php';
} else {
    $post = get_post_by_slug($slug);
    if ($post) {
        if ($post['post_type'] === 'post') {
            require 'templates/single-post.php';
        } else { // 'page'
            require 'templates/page.php';
        }
    } else {
        header("HTTP/1.0 404 Not Found");
        echo "404 - Page Not Found";
    }
}
