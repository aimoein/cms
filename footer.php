    </main> <!-- این تگ، تگ پایانی محتوای اصلی صفحه است -->

    
        
   

    <?php 
    // این قلاب به افزونه‌ها اجازه می‌دهد JS خود را در انتهای صفحه اضافه کنند
    do_action('site_footer'); 
    ?>

    <script>
        // ثبت Service Worker برای PWA
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/assets/js/sw.js')
                    .then(registration => console.log('ServiceWorker registered.'))
                    .catch(err => console.log('ServiceWorker registration failed: ', err));
            });
        }
    </script>
    <footer class="site-footer">
    <div class="container">
            <p>&copy; <?= date('Y') ?> | تمام حقوق برای <?= htmlspecialchars(get_setting('site_title')) ?> محفوظ است.</p>
        </div>
     </footer>
</body>
    <?php
    // رندر فوتر افزونه مدیریت فوتر
    if (file_exists(__DIR__ . '/../plugins/footer-manager/render.php')) {
        include __DIR__ . '/../plugins/footer-manager/render.php';
    }
    ?>
</main>
