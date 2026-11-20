<?php
// Zeal College of Engineering and Research (ZCOER), Narhe, Pune-41
// Department of Electronics and Computer Engineering
// Student Lab Workstation Session Dashboard

session_start();

// Session Guard: Must be logged in as student
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: index.php");
    exit();
}

require_once 'db.php';

$student_name = htmlspecialchars($_SESSION['student_name'] ?? 'Student', ENT_QUOTES, 'UTF-8');
$roll_no      = htmlspecialchars($_SESSION['roll_no'] ?? 'EC0000', ENT_QUOTES, 'UTF-8');
$pc_number    = htmlspecialchars($_SESSION['pc_number'] ?? 'PC-01', ENT_QUOTES, 'UTF-8');

// Fetch latest status and window from student_users
$current_status = 'Active';
$current_window = 'Student Portal';

$stmt = $conn->prepare("SELECT status, current_window, last_updated FROM student_users WHERE roll_no = ?");
$stmt->bind_param("s", $roll_no);
$stmt->execute();
$res = $stmt->get_result();
if ($res && $row = $res->fetch_assoc()) {
    $current_status = $row['status'] ?: 'Active';
    $current_window = $row['current_window'] ?: 'Student Portal';
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Active Lab Session | ZCOER Smart Lab</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Inter & Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        h1, h2, h3, .font-heading {
            font-family: 'Poppins', sans-serif;
        }
        .campus-bg {
            background-image: url('zeal-campus-hero.png');
            background-size: cover;
            background-position: center;
        }
        @keyframes pulseGlow {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            }
            70% {
                transform: scale(1.05);
                box-shadow: 0 0 0 10px rgba(16, 185, 129, 0);
            }
        }
        .pulse-badge {
            animation: pulseGlow 2.5s infinite;
        }
    </style>
</head>
<body class="min-h-screen campus-bg relative flex flex-col justify-between">

    <!-- Dark Slate Blur Overlay -->
    <div class="absolute inset-0 bg-slate-900/75 backdrop-blur-sm z-0"></div>

    <!-- Top Minimal Navigation -->
    <header class="relative z-10 p-4 sm:p-6 flex items-center justify-between max-w-5xl mx-auto w-full">
        <div class="flex items-center gap-3 text-white">
            <div class="bg-white p-1 rounded-xl shadow-md w-9 h-9 flex items-center justify-center">
                <img src="zeal-logo.png" alt="ZCOER Logo" class="h-7 w-auto object-contain">
            </div>
            <div>
                <span class="text-xs sm:text-sm font-bold tracking-wide font-heading block">
                    ZCOER Digital Lab Station
                </span>
                <span class="text-[10px] text-emerald-400 font-semibold block">
                    Dept. of Electronics &amp; Computer Engineering
                </span>
            </div>
        </div>

        <a href="logout.php" class="inline-flex items-center gap-2 bg-rose-600/80 hover:bg-rose-600 text-white text-xs font-bold px-3.5 py-2 rounded-xl transition duration-200 shadow-md">
            <i class="fa-solid fa-power-off"></i>
            <span>Leave PC</span>
        </a>
    </header>

    <!-- Centered Elevated White Card -->
    <main class="relative z-10 flex items-center justify-center px-4 py-6 my-auto">
        <div class="w-full max-w-lg bg-white rounded-3xl shadow-2xl p-7 sm:p-9 text-slate-800 border border-slate-100 flex flex-col items-center">
            
            <!-- Top of Card: Centered College Logo -->
            <div class="w-20 h-20 rounded-2xl bg-slate-50 border border-slate-200 p-2.5 shadow-md flex items-center justify-center mb-4">
                <img src="zeal-logo.png" alt="ZCOER Logo" class="h-full w-auto object-contain">
            </div>

            <!-- Pulsing Green "Session Active" Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-100 border border-emerald-300 text-emerald-800 text-xs font-extrabold uppercase tracking-wider mb-4 pulse-badge">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-600 animate-ping"></span>
                <i class="fa-solid fa-circle-check text-emerald-600"></i>
                <span>Session Active</span>
            </div>

            <!-- Student Name and Roll Number -->
            <h1 class="text-2xl sm:text-3xl font-black text-[#0a2351] font-heading tracking-tight text-center">
                <?php echo $student_name; ?>
            </h1>
            
            <div class="flex items-center gap-3 mt-2 mb-6">
                <span class="bg-blue-50 text-[#0a2351] border border-blue-200 text-xs font-extrabold px-3 py-1 rounded-lg">
                    Roll No: <?php echo $roll_no; ?>
                </span>
                <span class="bg-emerald-50 text-[#129468] border border-emerald-200 text-xs font-extrabold px-3 py-1 rounded-lg flex items-center gap-1.5">
                    <i class="fa-solid fa-desktop"></i>
                    <?php echo $pc_number; ?>
                </span>
            </div>

            <!-- Info Box: Stating their lab session is actively monitored -->
            <div class="w-full bg-gradient-to-br from-slate-50 to-blue-50/50 border border-blue-100 rounded-2xl p-5 mb-6 text-left">
                <div class="flex items-start gap-3.5">
                    <div class="w-9 h-9 rounded-xl bg-cyan-100 text-cyan-800 flex items-center justify-center text-base shrink-0 mt-0.5">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <div class="text-xs text-slate-600 space-y-1.5 leading-relaxed">
                        <p class="font-bold text-[#0a2351] text-sm">
                            Lab Workstation Actively Supervised
                        </p>
                        <p>
                            Your laboratory session is actively monitored by the faculty in charge. Background telemetry checks active foreground processes against the departmental academic whitelist.
                        </p>
                    </div>
                </div>

                <!-- Academic Whitelist Indicators -->
                <div class="mt-4 pt-3.5 border-t border-slate-200/80">
                    <span class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Whitelisted Lab Applications:
                    </span>
                    <div class="flex flex-wrap gap-1.5">
                        <span class="bg-white border border-slate-200 px-2.5 py-1 rounded-md text-[11px] font-semibold text-slate-700 flex items-center gap-1">
                            <i class="fa-solid fa-code text-blue-600 text-[10px]"></i> VS Code
                        </span>
                        <span class="bg-white border border-slate-200 px-2.5 py-1 rounded-md text-[11px] font-semibold text-slate-700 flex items-center gap-1">
                            <i class="fa-brands fa-python text-yellow-600 text-[10px]"></i> Python
                        </span>
                        <span class="bg-white border border-slate-200 px-2.5 py-1 rounded-md text-[11px] font-semibold text-slate-700 flex items-center gap-1">
                            <i class="fa-solid fa-server text-orange-600 text-[10px]"></i> XAMPP
                        </span>
                        <span class="bg-white border border-slate-200 px-2.5 py-1 rounded-md text-[11px] font-semibold text-slate-700 flex items-center gap-1">
                            <i class="fa-solid fa-database text-teal-600 text-[10px]"></i> phpMyAdmin
                        </span>
                        <span class="bg-white border border-slate-200 px-2.5 py-1 rounded-md text-[11px] font-semibold text-slate-700 flex items-center gap-1">
                            <i class="fa-solid fa-terminal text-indigo-600 text-[10px]"></i> Dev-C++
                        </span>
                    </div>
                </div>
            </div>

            <!-- Current Telemetry Live Box -->
            <div class="w-full bg-slate-100 rounded-xl p-3.5 mb-6 flex items-center justify-between text-xs text-slate-700">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Reported Window:</span>
                    <strong id="reported-win" class="text-slate-900 truncate max-w-[170px] sm:max-w-xs"><?php echo htmlspecialchars($current_window); ?></strong>
                </div>
                <span class="text-[10px] text-slate-500 font-mono">Roll: <?php echo $roll_no; ?></span>
            </div>

            <!-- Full-Width Red "End Session & Logout" Button -->
            <a 
                href="logout.php" 
                onclick="notifyLogout('<?php echo $roll_no; ?>')"
                class="w-full bg-rose-600 hover:bg-rose-700 text-white font-extrabold text-sm py-3.5 px-6 rounded-2xl shadow-lg shadow-rose-900/20 hover:shadow-xl transition-all duration-200 flex items-center justify-center gap-2 font-heading"
            >
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>End Session &amp; Logout</span>
            </a>

            <!-- Return home link -->
            <a href="index.php" class="mt-4 text-xs font-semibold text-slate-400 hover:text-slate-600 transition">
                Return to Campus Portal Home
            </a>

        </div>
    </main>

    <!-- Footer -->
    <footer class="relative z-10 p-4 text-center text-xs text-slate-400">
        &copy; <?php echo date('Y'); ?> Zeal College of Engineering and Research, Pune-41 • Electronics &amp; Computer Engg.
    </footer>

    <script>
        function notifyLogout(rollNo) {
            try {
                navigator.sendBeacon('api_update.php', JSON.stringify({
                    roll_no: rollNo,
                    status: 'Inactive',
                    current_window: 'Session Ended'
                }));
            } catch (e) {}
        }

        // Heartbeat periodic telemetry sync from browser tab
        setInterval(() => {
            fetch('api_update.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    roll_no: '<?php echo $roll_no; ?>',
                    status: 'Active',
                    current_window: document.title || 'Student Lab Portal'
                })
            }).then(r => r.json()).then(data => {
                if (data && data.current_window) {
                    const el = document.getElementById('reported-win');
                    if (el) el.textContent = data.current_window;
                }
            }).catch(() => {});
        }, 10000);
    </script>

</body>
</html>
