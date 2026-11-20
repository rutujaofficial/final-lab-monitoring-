<?php
// Zeal College of Engineering and Research (ZCOER), Narhe, Pune-41
// Department of Electronics and Computer Engineering
// Smart Digital Lab Monitoring System - Database Configuration

// ============================================================================
// INFINITYFREE & LIVE HOSTING CONFIGURATION
// ============================================================================
// When deploying to InfinityFree:
// 1. Log in to your InfinityFree Account -> Manage Account -> Control Panel.
// 2. Click "MySQL Databases" to view your credentials:
//    - MySQL Hostname (e.g., sql100.infinityfree.com or sql205.epizy.com)
//    - MySQL Username (e.g., if0_38000000 or epiz_38000000)
//    - MySQL Password (Your InfinityFree account/vPanel password)
//    - MySQL Database Name (e.g., if0_38000000_smart_lab)
// 3. Paste them into the $infinityfree_... variables below.
// ============================================================================

$infinityfree_host     = "sqlXXX.infinityfree.com"; // <-- Enter your InfinityFree MySQL Hostname
$infinityfree_username = "if0_XXXXXXXX";            // <-- Enter your InfinityFree MySQL Username
$infinityfree_password = "your_vpanel_password";    // <-- Enter your InfinityFree Account Password
$infinityfree_dbname   = "if0_XXXXXXXX_smart_lab";  // <-- Enter your InfinityFree Database Name

// Auto-detect environment: Localhost vs InfinityFree / Live Hosting
$is_localhost = (
    empty($_SERVER['HTTP_HOST']) ||
    strpos($_SERVER['HTTP_HOST'], 'localhost') !== false ||
    strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false
);

if ($is_localhost) {
    // Localhost XAMPP Settings
    $host     = "localhost";
    $username = "root";
    $password = "";
    $dbname   = "smart_lab";
} else {
    // InfinityFree / Live Server Settings
    $host     = $infinityfree_host;
    $username = $infinityfree_username;
    $password = $infinityfree_password;
    $dbname   = $infinityfree_dbname;
}

// Establish MySQLi connection
$conn = @new mysqli($host, $username, $password, $dbname);

// Verify database connection
if ($conn->connect_error) {
    $err = htmlspecialchars($conn->connect_error);
    die("
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <title>Database Configuration Required | ZCOER Smart Lab</title>
        <script src='https://cdn.tailwindcss.com'></script>
    </head>
    <body class='min-h-screen bg-slate-900 text-white flex items-center justify-center p-4 font-sans'>
        <div class='max-w-lg w-full bg-slate-800 border border-rose-500/40 rounded-2xl p-6 shadow-2xl'>
            <div class='flex items-center gap-3 text-rose-400 mb-4'>
                <svg class='w-8 h-8' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'></path></svg>
                <h2 class='text-xl font-bold text-white'>Database Connection Notice</h2>
            </div>
            <p class='text-sm text-slate-300 leading-relaxed mb-4'>
                The application could not connect to MySQL. If you just deployed to <strong>InfinityFree</strong>, please edit <code>db.php</code> with your InfinityFree MySQL credentials:
            </p>
            <div class='bg-slate-950 p-4 rounded-xl text-xs font-mono text-cyan-300 mb-4 space-y-1 border border-slate-700'>
                <div>Hostname: <strong>$host</strong></div>
                <div>Username: <strong>$username</strong></div>
                <div>Database: <strong>$dbname</strong></div>
                <div class='text-rose-400 mt-2'>Error: $err</div>
            </div>
            <p class='text-xs text-slate-400'>
                Open <code>db.php</code> and update lines 18-21 with the credentials from your InfinityFree Control Panel &rarr; MySQL Databases.
            </p>
        </div>
    </body>
    </html>
    ");
}

// Set proper UTF-8 charset
$conn->set_charset("utf8mb4");
?>
