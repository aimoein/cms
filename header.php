<?php
/**
 * header.php
 * هدر اصلی سایت. تمام فایل‌های هسته قبلاً توسط init.php بارگذاری شده‌اند.
 */
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($page_title ?? get_setting('site_title')) ?></title>
    <meta name="description" content="<?= htmlspecialchars($meta_description ?? get_setting('site_description')) ?>">

    <meta name="theme-color" content="#ffffff">
    <link rel="manifest" href="<?= SITE_URL ?>/manifest.json">
    <link rel="apple-touch-icon" href="/assets/images/icon-192.png">
    <link rel="stylesheet" href="<?= SITE_URL ?>/plugins/mega-menu-builder/assets/mega-menu.css">
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css">
    
    <?php 
    // این قلاب به افزونه‌ها اجازه می‌دهد CSS خود را به هدر اضافه کنند
    do_action('site_head'); 
    ?>
    
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- Logo Manager CSS -->
    <link rel="stylesheet" href="<?= SITE_URL ?>/plugins/logo-manager/assets/logo-manager.css">
    <!-- Footer Manager CSS -->
    <link rel="stylesheet" href="<?= SITE_URL ?>/plugins/footer-manager/assets/footer-manager.css">
</head>
<body>
    <header class="site-header">
        <div class="container header-container">
            <div class="site-logo" style="display:flex;align-items:center;justify-content:flex-end;gap:12px;">
                <?php
                $logo_exts = ['png', 'svg', 'jpg', 'jpeg'];
                $logo_found = false;
                foreach ($logo_exts as $ext) {
                    $logo_path = __DIR__ . "/plugins/logo-manager/assets/logo.$ext";
                    $logo_url = SITE_URL . "/plugins/logo-manager/assets/logo.$ext";
                    if (file_exists($logo_path)) {
                        echo '<img src="' . $logo_url . '" alt="لوگو سایت" style="width:80px;max-width:100%;height:auto;">';
                        $logo_found = true;
                        break;
                    }
                }
                if (!$logo_found) {
                    echo '<span style="color:#fff;font-size:1.5em;font-weight:bold;">لوگو</span>';
                }
                ?>
                <a href="<?= SITE_URL ?>" style="font-weight:bold;font-size:1.2em;">
                    <?= htmlspecialchars(get_setting('site_title')) ?>
                </a>
            </div>
            
            <div class="site-navigation">
                <?php
                // این تابع اکنون باید به درستی کار کند
                if (function_exists('mmb_render_menu')) {
                    mmb_render_menu();
                } else {
                    // این بخش فقط برای عیب‌یابی است
                    echo '<div style="color: red; border: 1px solid red; padding: 5px;">افزونه منو فعال نیست یا تابع mmb_render_menu پیدا نشد.</div>';
                }
                ?>
            </div>
        </div>
    </header>
    
    <main> <!-- تگ main در اینجا باز می‌شود و در footer.php بسته خواهد شد -->
