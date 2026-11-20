<?php
// Zeal College of Engineering and Research (ZCOER), Narhe, Pune-41
// Department of Electronics and Computer Engineering
// Faculty Control Panel & Live Digital Lab Monitoring Console

session_start();

// Session Guard: Must be logged in as faculty
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'faculty') {
    header("Location: index.php");
    exit();
}

require_once 'db.php';

$faculty_name = htmlspecialchars($_SESSION['faculty_name'] ?? 'Faculty Member', ENT_QUOTES, 'UTF-8');
$subject      = htmlspecialchars($_SESSION['subject'] ?? 'Electronics & Computer Engg.', ENT_QUOTES, 'UTF-8');
$lab_room     = htmlspecialchars($_SESSION['lab_room'] ?? 'Lab Station', ENT_QUOTES, 'UTF-8');

// Query live summary statistics from student_users
$total_students = 0;
$active_sessions = 0;
$flagged_alerts = 0;

$res_total = $conn->query("SELECT COUNT(*) AS total FROM student_users");
if ($res_total) {
    $row = $res_total->fetch_assoc();
    $total_students = (int)$row['total'];
}

$res_active = $conn->query("SELECT COUNT(*) AS active_count FROM student_users WHERE status = 'Active'");
if ($res_active) {
    $row = $res_active->fetch_assoc();
    $active_sessions = (int)$row['active_count'];
}

$res_alerts = $conn->query("SELECT COUNT(*) AS alert_count FROM student_users WHERE status LIKE '%Alert%' OR status LIKE '%Unauthorized%'");
if ($res_alerts) {
    $row = $res_alerts->fetch_assoc();
    $flagged_alerts = (int)$row['alert_count'];
}

// Fetch all student records for initial render
$students = [];
$res_students = $conn->query("SELECT id, student_name, roll_no, pc_number, status, current_window, last_updated FROM student_users ORDER BY id DESC");
if ($res_students) {
    while ($r = $res_students->fetch_assoc()) {
        $students[] = $r;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Control Panel | ZCOER Smart Lab</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Inter & Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #040d1f;
        }
        h1, h2, h3, h4, .font-heading {
            font-family: 'Poppins', sans-serif;
        }
        .hero-banner-bg {
            background-image: 
                linear-gradient(90deg, rgba(10, 35, 81, 0.95) 0%, rgba(10, 35, 81, 0.82) 55%, rgba(18, 148, 104, 0.40) 100%),
                url('zeal-campus-hero.png');
            background-size: cover;
            background-position: center;
        }
        .stat-card-glass {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            transition: all 0.25s ease;
        }
        .stat-card-glass:hover {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(6, 182, 212, 0.4);
            transform: translateY(-2px);
        }
        @keyframes pulseDot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(0.85); }
        }
        .dot-pulse {
            animation: pulseDot 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
</head>
<body class="min-h-screen text-slate-100 flex flex-col antialiased">

    <!-- Top Navbar: Deep Navy (#0a2351) -->
    <header class="bg-[#0a2351] border-b border-cyan-500/20 sticky top-0 z-40 shadow-lg px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto h-20 flex items-center justify-between gap-4">
            
            <!-- Left Branding -->
            <div class="flex items-center gap-3.5">
                <div class="bg-white p-1 rounded-2xl shadow-md flex items-center justify-center shrink-0">
                    <img src="zeal-logo.png" alt="ZCOER Logo" class="h-11 w-auto object-contain">
                </div>
                <div>
                    <h1 class="text-base sm:text-lg font-black tracking-tight text-white font-heading leading-tight flex items-center gap-2">
                        <span>ZCOER Faculty Control</span>
                        <span class="hidden sm:inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-cyan-500/20 text-cyan-300 border border-cyan-400/30">
                            LIVE
                        </span>
                    </h1>
                    <p class="text-xs text-cyan-300 font-medium">
                        Dept. of Electronics &amp; Computer Engineering
                    </p>
                </div>
            </div>

            <!-- Right Controls -->
            <div class="flex items-center gap-3 sm:gap-4">
                
                <!-- Faculty Badge -->
                <div class="hidden sm:flex items-center gap-2.5 bg-white/10 border border-white/15 px-3.5 py-1.5 rounded-full text-xs text-white">
                    <span class="w-7 h-7 rounded-full bg-cyan-500 text-[#0a2351] font-black flex items-center justify-center text-xs">
                        <i class="fa-solid fa-user-tie"></i>
                    </span>
                    <div>
                        <span class="font-bold block leading-tight"><?php echo $faculty_name; ?></span>
                        <span class="text-[10px] text-cyan-300 font-semibold"><?php echo $lab_room; ?></span>
                    </div>
                </div>

                <!-- Red Logout Button -->
                <a href="logout.php" class="inline-flex items-center gap-2 bg-rose-600 hover:bg-rose-700 text-white text-xs sm:text-sm font-bold px-4 py-2.5 rounded-xl transition duration-200 shadow-md shadow-rose-950/40 hover:scale-[1.02] active:scale-95">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    <span>Logout</span>
                </a>

            </div>

        </div>
    </header>

    <!-- Main Container -->
    <main class="flex-grow max-w-7xl mx-auto w-full p-4 sm:p-6 lg:p-8 space-y-6">

        <!-- Hero Banner: Compact rounded banner with zeal-campus-hero.png & navy gradient overlay -->
        <section class="hero-banner-bg rounded-3xl p-6 sm:p-8 shadow-2xl border border-white/10 relative overflow-hidden">
            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
                
                <!-- Welcome Text -->
                <div class="lg:col-span-5">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-cyan-400/20 text-cyan-300 text-xs font-bold uppercase tracking-wider mb-2 border border-cyan-400/30">
                        <i class="fa-solid fa-satellite-dish text-xs"></i> Supervision Console
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-black text-white font-heading tracking-tight leading-snug">
                        Welcome, <?php echo $faculty_name; ?>
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1">
                        Active Monitoring Session • <strong class="text-cyan-300"><?php echo $subject; ?></strong> (Room: <span class="text-emerald-300 font-bold"><?php echo $lab_room; ?></span>)
                    </p>
                    <div class="mt-4 flex items-center gap-2 text-xs text-slate-300">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 dot-pulse"></span>
                        <span>Auto-refreshing live activity every <strong class="text-white">10 seconds</strong></span>
                    </div>
                </div>

                <!-- 3 Live Summary Stat Cards -->
                <div class="lg:col-span-7 grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                    
                    <!-- Stat 1: Total Students -->
                    <div class="stat-card-glass rounded-2xl p-4.5 flex flex-col justify-between">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold text-slate-300">Total Students</span>
                            <span class="w-8 h-8 rounded-lg bg-blue-500/20 text-cyan-300 flex items-center justify-center text-sm border border-cyan-400/30">
                                <i class="fa-solid fa-users"></i>
                            </span>
                        </div>
                        <div class="mt-3">
                            <span id="stat-total" class="text-3xl font-black text-white font-heading"><?php echo $total_students; ?></span>
                            <span class="block text-[11px] text-slate-400 mt-0.5">Enrolled in lab</span>
                        </div>
                    </div>

                    <!-- Stat 2: Active Sessions -->
                    <div class="stat-card-glass rounded-2xl p-4.5 flex flex-col justify-between border-emerald-500/30">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold text-emerald-200">Active Sessions</span>
                            <span class="w-8 h-8 rounded-lg bg-emerald-500/20 text-emerald-300 flex items-center justify-center text-sm border border-emerald-400/40">
                                <i class="fa-solid fa-desktop"></i>
                            </span>
                        </div>
                        <div class="mt-3">
                            <span id="stat-active" class="text-3xl font-black text-emerald-400 font-heading"><?php echo $active_sessions; ?></span>
                            <span class="block text-[11px] text-emerald-200/70 mt-0.5">Online right now</span>
                        </div>
                    </div>

                    <!-- Stat 3: Alerts / Flagged -->
                    <div class="stat-card-glass rounded-2xl p-4.5 flex flex-col justify-between border-rose-500/30">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold text-rose-200">Alerts / Flagged</span>
                            <span class="w-8 h-8 rounded-lg bg-rose-500/20 text-rose-400 flex items-center justify-center text-sm border border-rose-400/40">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            </span>
                        </div>
                        <div class="mt-3">
                            <span id="stat-alerts" class="text-3xl font-black text-rose-400 font-heading"><?php echo $flagged_alerts; ?></span>
                            <span class="block text-[11px] text-rose-200/70 mt-0.5">Unauthorized apps</span>
                        </div>
                    </div>

                </div>

            </div>
        </section>

        <!-- Live Monitoring Table Section -->
        <section class="bg-[#081b3d] border border-cyan-500/20 rounded-3xl p-5 sm:p-7 shadow-2xl space-y-5">
            
            <!-- Table Header & Controls -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-white/10">
                <div>
                    <h3 class="text-lg sm:text-xl font-bold text-white font-heading flex items-center gap-2">
                        <i class="fa-solid fa-network-wired text-cyan-400"></i>
                        <span>Workstation Telemetry &amp; Application Status</span>
                    </h3>
                    <p class="text-xs text-slate-400 mt-1">
                        Live process detection matching student roll numbers, active foreground apps, and academic compliance.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <span id="sync-indicator" class="text-xs text-slate-400 hidden sm:inline flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 dot-pulse"></span>
                        <span id="sync-time">Synced just now</span>
                    </span>

                    <button 
                        type="button" 
                        id="refresh-btn" 
                        onclick="fetchLiveStudents(true)"
                        class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white text-xs font-bold px-4 py-2.5 rounded-xl border border-white/15 transition active:scale-95"
                    >
                        <i id="refresh-icon" class="fa-solid fa-rotate"></i>
                        <span>Refresh Now</span>
                    </button>
                </div>
            </div>

            <!-- Modern Tailwind Table -->
            <div class="overflow-x-auto rounded-2xl border border-white/10 shadow-sm">
                <table class="w-full text-left text-xs sm:text-sm border-collapse">
                    <thead>
                        <tr class="bg-[#05132d] text-cyan-300 uppercase tracking-wider text-[11px] font-semibold border-b border-white/10">
                            <th class="py-3.5 px-4">Student</th>
                            <th class="py-3.5 px-4">Roll No</th>
                            <th class="py-3.5 px-4">PC Station</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4">Current Active Window</th>
                            <th class="py-3.5 px-4 text-right">Last Heartbeat</th>
                        </tr>
                    </thead>
                    <tbody id="student-table-body" class="divide-y divide-white/5 bg-[#081a3b]">
                        <?php if (empty($students)): ?>
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 text-sm">
                                <i class="fa-solid fa-user-slash text-2xl text-slate-500 mb-2 block"></i>
                                No student workstations registered yet. Students can log in at <a href="student_login.php" class="text-cyan-400 underline font-semibold">student_login.php</a>.
                            </td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($students as $s): 
                                $isAlert = (stripos($s['status'], 'Alert') !== false || stripos($s['status'], 'Unauthorized') !== false);
                                $isActive = (stripos($s['status'], 'Active') !== false && !$isAlert);
                            ?>
                            <tr class="hover:bg-white/5 transition-colors" data-roll="<?php echo htmlspecialchars($s['roll_no']); ?>">
                                
                                <!-- Student Name -->
                                <td class="py-3.5 px-4 font-bold text-white flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-cyan-500/20 text-cyan-300 flex items-center justify-center text-xs shrink-0 border border-cyan-400/30">
                                        <i class="fa-solid fa-user-graduate"></i>
                                    </div>
                                    <span class="truncate max-w-[150px] sm:max-w-none"><?php echo htmlspecialchars($s['student_name']); ?></span>
                                </td>

                                <!-- Roll No -->
                                <td class="py-3.5 px-4 font-semibold text-slate-300">
                                    <span class="bg-black/20 px-2 py-0.5 rounded border border-white/10 text-xs">
                                        <?php echo htmlspecialchars($s['roll_no']); ?>
                                    </span>
                                </td>

                                <!-- PC Number -->
                                <td class="py-3.5 px-4 font-bold text-cyan-300">
                                    <span class="inline-flex items-center gap-1.5">
                                        <i class="fa-solid fa-desktop text-xs opacity-75"></i>
                                        <?php echo htmlspecialchars($s['pc_number']); ?>
                                    </span>
                                </td>

                                <!-- Status Badge -->
                                <td class="py-3.5 px-4">
                                    <?php if ($isAlert): ?>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-500/20 text-rose-300 border border-rose-500/40">
                                            <span class="w-2 h-2 rounded-full bg-rose-500 dot-pulse"></span>
                                            <?php echo htmlspecialchars($s['status']); ?>
                                        </span>
                                    <?php elseif ($isActive): ?>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/40">
                                            <span class="w-2 h-2 rounded-full bg-emerald-400 dot-pulse"></span>
                                            Active
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-500/20 text-slate-300 border border-slate-500/40">
                                            <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                                            <?php echo htmlspecialchars($s['status']); ?>
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <!-- Current Window -->
                                <td class="py-3.5 px-4 text-slate-200">
                                    <div class="flex items-center gap-2 max-w-xs sm:max-w-sm truncate" title="<?php echo htmlspecialchars($s['current_window']); ?>">
                                        <?php if ($isAlert): ?>
                                            <i class="fa-solid fa-ban text-rose-400 shrink-0"></i>
                                            <span class="text-rose-200 font-medium truncate"><?php echo htmlspecialchars($s['current_window']); ?></span>
                                        <?php else: ?>
                                            <i class="fa-solid fa-window-maximize text-cyan-400 shrink-0"></i>
                                            <span class="truncate"><?php echo htmlspecialchars($s['current_window']); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <!-- Last Updated -->
                                <td class="py-3.5 px-4 text-right text-xs text-slate-400 font-mono">
                                    <?php echo htmlspecialchars($s['last_updated']); ?>
                                </td>

                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Whitelist reference bar -->
            <div class="mt-4 pt-4 border-t border-white/10 flex flex-wrap items-center justify-between text-xs text-slate-400 gap-3">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="font-bold text-slate-300"><i class="fa-solid fa-shield-halved text-cyan-400 mr-1"></i>Approved Academic Tools:</span>
                    <span class="bg-white/5 px-2 py-0.5 rounded text-cyan-300 border border-white/10">VS Code</span>
                    <span class="bg-white/5 px-2 py-0.5 rounded text-cyan-300 border border-white/10">Python</span>
                    <span class="bg-white/5 px-2 py-0.5 rounded text-cyan-300 border border-white/10">XAMPP</span>
                    <span class="bg-white/5 px-2 py-0.5 rounded text-cyan-300 border border-white/10">Chrome (Localhost)</span>
                    <span class="bg-white/5 px-2 py-0.5 rounded text-cyan-300 border border-white/10">phpMyAdmin</span>
                    <span class="bg-white/5 px-2 py-0.5 rounded text-cyan-300 border border-white/10">Sublime</span>
                    <span class="bg-white/5 px-2 py-0.5 rounded text-cyan-300 border border-white/10">Dev-C++</span>
                </div>
                <div>
                    <span class="text-slate-500">Auto-polls API: <code class="text-cyan-400">api_update.php</code></span>
                </div>
            </div>

        </section>

    </main>

    <!-- Footer -->
    <footer class="bg-[#05132d] text-slate-400 text-xs py-4 px-4 text-center border-t border-white/10 mt-auto">
        &copy; <?php echo date('Y'); ?> Zeal College of Engineering and Research, Pune-41 • Department of Electronics &amp; Computer Engineering
    </footer>

    <!-- Live Auto-Refresh Script (10s timer & manual trigger) -->
    <script>
        function escapeHtml(str) {
            if (!str) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        async function fetchLiveStudents(manual = false) {
            const refreshIcon = document.getElementById('refresh-icon');
            const syncTime = document.getElementById('sync-time');

            if (refreshIcon) {
                refreshIcon.classList.add('fa-spin');
            }

            try {
                const response = await fetch('api_update.php?t=' + new Date().getTime());
                if (!response.ok) throw new Error('Network response not ok: ' + response.status);

                const data = await response.json();
                if (data.success && Array.isArray(data.students)) {
                    renderTable(data.students);
                    updateCounters(data.students);

                    const now = new Date();
                    const timeStr = now.toLocaleTimeString();
                    if (syncTime) syncTime.textContent = 'Synced at ' + timeStr;
                }
            } catch (err) {
                console.warn('Auto-refresh polling issue:', err);
                if (syncTime) syncTime.textContent = 'Reconnecting...';
            } finally {
                if (refreshIcon) {
                    setTimeout(() => refreshIcon.classList.remove('fa-spin'), 400);
                }
            }
        }

        function updateCounters(students) {
            let total = students.length;
            let active = 0;
            let alerts = 0;

            students.forEach(s => {
                const st = (s.status || '').toLowerCase();
                if (st.includes('alert') || st.includes('unauthorized')) {
                    alerts++;
                } else if (st.includes('active')) {
                    active++;
                }
            });

            const statTotal = document.getElementById('stat-total');
            const statActive = document.getElementById('stat-active');
            const statAlerts = document.getElementById('stat-alerts');

            if (statTotal) statTotal.textContent = total;
            if (statActive) statActive.textContent = active;
            if (statAlerts) statAlerts.textContent = alerts;
        }

        function renderTable(students) {
            const tbody = document.getElementById('student-table-body');
            if (!tbody) return;

            if (students.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-400 text-sm">
                            <i class="fa-solid fa-user-slash text-2xl text-slate-500 mb-2 block"></i>
                            No student workstations registered yet. Students can log in at <a href="student_login.php" class="text-cyan-400 underline font-semibold">student_login.php</a>.
                        </td>
                    </tr>
                `;
                return;
            }

            let html = '';
            students.forEach(s => {
                const status = s.status || 'Active';
                const currentWindow = s.current_window || 'None';
                const isAlert = status.toLowerCase().includes('alert') || status.toLowerCase().includes('unauthorized');
                const isActive = status.toLowerCase().includes('active') && !isAlert;

                let badgeHtml = '';
                if (isAlert) {
                    badgeHtml = `
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-500/20 text-rose-300 border border-rose-500/40">
                            <span class="w-2 h-2 rounded-full bg-rose-500 dot-pulse"></span>
                            ${escapeHtml(status)}
                        </span>
                    `;
                } else if (isActive) {
                    badgeHtml = `
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/40">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 dot-pulse"></span>
                            Active
                        </span>
                    `;
                } else {
                    badgeHtml = `
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-500/20 text-slate-300 border border-slate-500/40">
                            <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                            ${escapeHtml(status)}
                        </span>
                    `;
                }

                let windowHtml = '';
                if (isAlert) {
                    windowHtml = `
                        <div class="flex items-center gap-2 max-w-xs sm:max-w-sm truncate" title="${escapeHtml(currentWindow)}">
                            <i class="fa-solid fa-ban text-rose-400 shrink-0"></i>
                            <span class="text-rose-200 font-medium truncate">${escapeHtml(currentWindow)}</span>
                        </div>
                    `;
                } else {
                    windowHtml = `
                        <div class="flex items-center gap-2 max-w-xs sm:max-w-sm truncate" title="${escapeHtml(currentWindow)}">
                            <i class="fa-solid fa-window-maximize text-cyan-400 shrink-0"></i>
                            <span class="truncate">${escapeHtml(currentWindow)}</span>
                        </div>
                    `;
                }

                html += `
                    <tr class="hover:bg-white/5 transition-colors" data-roll="${escapeHtml(s.roll_no)}">
                        <td class="py-3.5 px-4 font-bold text-white flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-cyan-500/20 text-cyan-300 flex items-center justify-center text-xs shrink-0 border border-cyan-400/30">
                                <i class="fa-solid fa-user-graduate"></i>
                            </div>
                            <span class="truncate max-w-[150px] sm:max-w-none">${escapeHtml(s.student_name)}</span>
                        </td>
                        <td class="py-3.5 px-4 font-semibold text-slate-300">
                            <span class="bg-black/20 px-2 py-0.5 rounded border border-white/10 text-xs">
                                ${escapeHtml(s.roll_no)}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 font-bold text-cyan-300">
                            <span class="inline-flex items-center gap-1.5">
                                <i class="fa-solid fa-desktop text-xs opacity-75"></i>
                                ${escapeHtml(s.pc_number)}
                            </span>
                        </td>
                        <td class="py-3.5 px-4">
                            ${badgeHtml}
                        </td>
                        <td class="py-3.5 px-4 text-slate-200">
                            ${windowHtml}
                        </td>
                        <td class="py-3.5 px-4 text-right text-xs text-slate-400 font-mono">
                            ${escapeHtml(s.last_updated)}
                        </td>
                    </tr>
                `;
            });

            tbody.innerHTML = html;
        }

        // Set interval to auto-refresh every 10 seconds (10000 ms) as specified
        setInterval(fetchLiveStudents, 10000);
    </script>

</body>
</html>
