<?php
// Installer for the Professional CMS
// --- IMPORTANT: Delete this file after installation is complete! ---

// Error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

$step = $_GET['step'] ?? '1';

function display_header($title) {
    echo <<<HTML
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>$title</title>
    <style>
        body { font-family: 'Vazirmatn', sans-serif; direction: rtl; background-color: #f4f7f6; color: #333; margin: 0; padding: 2rem; }
        .container { max-width: 700px; margin: 2rem auto; background: #fff; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        h1, h2 { color: #0056b3; }
        .form-group { margin-bottom: 1.5rem; }
        label { display: block; font-weight: bold; margin-bottom: 0.5rem; }
        input[type="text"], input[type="password"] { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .button { background-color: #007bff; color: white; padding: 12px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .button:hover { background-color: #0056b3; }
        .error { color: #d93025; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 1rem; border-radius: 4px; margin-bottom: 1rem; }
        .success { color: #155724; background-color: #d4edda; border: 1px solid #c3e6cb; padding: 1rem; border-radius: 4px; }
        .check-table { width: 100%; border-collapse: collapse; }
        .check-table td { padding: 8px; border-bottom: 1px solid #eee; }
        .check-table td:last-child { text-align: left; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
HTML;
}

function display_footer() {
    echo <<<HTML
    </div>
</body>
</html>
HTML;
}

switch ($step) {
    // --- STEP 1: WELCOME & SERVER CHECKS ---
    case '1':
        display_header('نصب سیستم مدیریت محتوا - مرحله ۱');
        echo '<h1>خوش آمدید!</h1>';
        echo '<p>این فرآیند شما را برای نصب و پیکربندی CMS راهنمایی می‌کند. ابتدا پیش‌نیازهای سرور را بررسی می‌کنیم:</p>';

        $php_version_ok = version_compare(PHP_VERSION, '7.4.0', '>=');
        $pdo_ok = extension_loaded('pdo_mysql');
        $config_writable = is_writable(__DIR__);

        echo '<table class="check-table">';
        echo '<tr><td>نسخه PHP (حداقل 7.4)</td><td>' . ( $php_version_ok ? '✅' : '❌' ) . ' (' . PHP_VERSION . ')</td></tr>';
        echo '<tr><td>افزونه PDO MySQL</td><td>' . ( $pdo_ok ? '✅' : '❌' ) . '</td></tr>';
        echo '<tr><td>قابلیت نوشتن فایل config.php</td><td>' . ( $config_writable ? '✅' : '❌' ) . '</td></tr>';
        echo '</table><br>';

        if ($php_version_ok && $pdo_ok && $config_writable) {
            echo '<form action="install.php?step=2" method="post"><button type="submit" class="button">شروع نصب</button></form>';
        } else {
            echo '<p class="error">یک یا چند مورد از پیش‌نیازها برآورده نشده است. لطفاً قبل از ادامه، مشکلات را برطرف کنید.</p>';
        }
        display_footer();
        break;

    // --- STEP 2: DATABASE CONFIGURATION ---
    case '2':
        display_header('پیکربندی پایگاه داده - مرحله ۲');
        echo '<h2>پیکربندی پایگاه داده</h2>';
        echo '<p>اطلاعات اتصال به پایگاه داده MySQL خود را وارد کنید. این اطلاعات را از میزبان وب خود دریافت کنید.</p>';
        if(isset($_GET['error'])) echo '<p class="error">'.htmlspecialchars($_GET['error']).'</p>';
        
        echo '<form action="install.php?step=3" method="post">
                <div class="form-group">
                    <label for="db_host">میزبان پایگاه داده</label>
                    <input type="text" id="db_host" name="db_host" value="localhost" required>
                </div>
                <div class="form-group">
                    <label for="db_name">نام پایگاه داده</label>
                    <input type="text" id="db_name" name="db_name" required>
                </div>
                <div class="form-group">
                    <label for="db_user">نام کاربری پایگاه داده</label>
                    <input type="text" id="db_user" name="db_user" required>
                </div>
                <div class="form-group">
                    <label for="db_pass">رمز عبور پایگاه داده</label>
                    <input type="password" id="db_pass" name="db_pass">
                </div>
                <button type="submit" class="button">ادامه</button>
              </form>';
        display_footer();
        break;

    // --- STEP 3: SITE & ADMIN CONFIGURATION ---
    case '3':
        // Test database connection first
        $db_host = $_POST['db_host'] ?? '';
        $db_name = $_POST['db_name'] ?? '';
        $db_user = $_POST['db_user'] ?? '';
        $db_pass = $_POST['db_pass'] ?? '';

        try {
            $pdo = new PDO("mysql:host=$db_host", $db_user, $db_pass);
        } catch (PDOException $e) {
            header('Location: install.php?step=2&error=اتصال به پایگاه داده با خطا مواجه شد: ' . urlencode($e->getMessage()));
            exit;
        }

        display_header('اطلاعات سایت - مرحله ۳');
        echo '<h2>اطلاعات سایت و مدیر</h2>';
        echo '<p>یک عنوان برای سایت خود انتخاب کرده و اطلاعات حساب کاربری مدیر اصلی را ایجاد کنید.</p>';
        echo '<form action="install.php?step=4" method="post">
                <input type="hidden" name="db_host" value="'.htmlspecialchars($db_host).'">
                <input type="hidden" name="db_name" value="'.htmlspecialchars($db_name).'">
                <input type="hidden" name="db_user" value="'.htmlspecialchars($db_user).'">
                <input type="hidden" name="db_pass" value="'.htmlspecialchars($db_pass).'">
                
                <div class="form-group">
                    <label for="site_title">عنوان سایت</label>
                    <input type="text" id="site_title" name="site_title" required>
                </div>
                <hr>
                <div class="form-group">
                    <label for="admin_user">نام کاربری مدیر</label>
                    <input type="text" id="admin_user" name="admin_user" required>
                </div>
                <div class="form-group">
                    <label for="admin_pass">رمز عبور مدیر</label>
                    <input type="password" id="admin_pass" name="admin_pass" required>
                </div>
                <div class="form-group">
                    <label for="admin_email">ایمیل مدیر</label>
                    <input type="text" id="admin_email" name="admin_email" required>
                </div>
                <button type="submit" class="button">نصب نهایی</button>
              </form>';
        display_footer();
        break;

    // --- STEP 4: FINAL INSTALLATION ---
    case '4':
        display_header('در حال نصب...');

        // 1. Get all data from POST
        $db_host = $_POST['db_host'];
        $db_name = $_POST['db_name'];
        $db_user = $_POST['db_user'];
        $db_pass = $_POST['db_pass'];

        $site_title = $_POST['site_title'];
        $admin_user = $_POST['admin_user'];
        $admin_pass = $_POST['admin_pass'];
        $admin_email = $_POST['admin_email'];
        
        // 2. Create config.php file content
        $site_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        $site_url = rtrim(str_replace('install.php', '', $site_url), '/');

        $config_content = "<?php
// Database Configuration
define('DB_HOST', '".addslashes($db_host)."');
define('DB_USER', '".addslashes($db_user)."');
define('DB_PASS', '".addslashes($db_pass)."');
define('DB_NAME', '".addslashes($db_name)."');

// Site URL
define('SITE_URL', '".addslashes($site_url)."');

// Start the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>";

        // 3. Write the config.php file
        if (file_put_contents('config.php', $config_content) === false) {
            echo '<p class="error">خطا در ساخت فایل <code>config.php</code>. لطفاً از دسترسی نوشتن (write permission) در این پوشه اطمینان حاصل کنید.</p>';
            display_footer();
            exit;
        }

        try {
            // 4. Connect to database and create tables
            $pdo = new PDO("mysql:host=$db_host", $db_user, $db_pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
            $pdo->exec("USE `$db_name`;");

            // --- Table Creation SQL ---
            $sql_users = "CREATE TABLE IF NOT EXISTS users ( user_id INT AUTO_INCREMENT PRIMARY KEY, username VARCHAR(50) NOT NULL UNIQUE, password VARCHAR(255) NOT NULL, email VARCHAR(100) NOT NULL UNIQUE, role ENUM('admin', 'editor', 'author') NOT NULL DEFAULT 'author', created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
            $pdo->exec($sql_users);

$sql_plugin = "CREATE TABLE IF NOT EXISTS plugins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    plugin_folder VARCHAR(191) NOT NULL UNIQUE,
    is_active BOOLEAN NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
            $pdo->exec($sql_plugin);

            $sql_posts = "CREATE TABLE IF NOT EXISTS posts ( post_id INT AUTO_INCREMENT PRIMARY KEY, user_id INT NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL UNIQUE, content TEXT NOT NULL, post_type ENUM('post', 'page') NOT NULL DEFAULT 'post', status ENUM('published', 'draft') NOT NULL DEFAULT 'published', created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
            $pdo->exec($sql_posts);

            $sql_settings = "CREATE TABLE IF NOT EXISTS settings ( setting_key VARCHAR(100) PRIMARY KEY, setting_value TEXT ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
            $pdo->exec($sql_settings);

            // 5. Insert initial data
            $hashed_password = password_hash($admin_pass, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, 'admin')");
            $stmt->execute([$admin_user, $hashed_password, $admin_email]);

            $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('site_title', ?), ('site_description', ?)");
            $stmt->execute([$site_title, 'یک سایت جدید با CMS قدرتمند']);
            
            // 6. Success message
            echo '<div class="success"><h2>نصب با موفقیت انجام شد!</h2></div>';
            echo '<p>سیستم مدیریت محتوای شما آماده استفاده است.</p>';
            echo '<p>نام کاربری شما: <strong>'.htmlspecialchars($admin_user).'</strong></p>';
            echo '<p>رمز عبوری که انتخاب کردید را به خاطر بسپارید.</p>';
            echo '<a href="admin/" class="button">ورود به پنل مدیریت</a>';
            echo '<br><br><p class="error"><strong>هشدار امنیتی بسیار مهم:</strong> لطفاً همین حالا فایل <code>install.php</code> را از هاست خود حذف کنید.</p>';

        } catch (PDOException $e) {
            // Cleanup on failure
            unlink('config.php');
            echo '<p class="error">خطا در هنگام نصب در پایگاه داده: '.htmlspecialchars($e->getMessage()).'</p>';
            echo '<p>فایل <code>config.php</code> حذف شد. لطفاً دوباره تلاش کنید.</p>';
        }

        display_footer();
        break;

    default:
        header("Location: install.php?step=1");
        exit;
}

?>