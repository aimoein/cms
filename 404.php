<?php
// صفحه 404 اختصاصی
require_once __DIR__ . '/../header.php';
?>
<main class="container" style="text-align:center;padding:60px 0;">
    <h1 style="font-size:3em;color:#c00;">404</h1>
    <p style="font-size:1.3em;">متاسفانه صفحه مورد نظر شما پیدا نشد.</p>
    <a href="<?= SITE_URL ?>" class="button">بازگشت به صفحه اصلی</a>
</main>
<?php
require_once __DIR__ . '/../footer.php';
?>
