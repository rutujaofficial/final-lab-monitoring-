<?php
// Zeal College of Engineering and Research (ZCOER), Narhe, Pune-41
// Department of Electronics and Computer Engineering
// Faculty Portal Authentication & Auto-Registration

session_start();
require_once 'db.php';

// Redirect if already logged in as faculty
if (isset($_SESSION['role']) && $_SESSION['role'] === 'faculty') {
    header("Location: dashboard.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $faculty_name = trim($_POST['faculty_name'] ?? '');
    $subject      = trim($_POST['subject'] ?? '');
    $lab_room     = trim($_POST['lab_room'] ?? '');
    $password     = $_POST['password'] ?? '';

    if (empty($faculty_name) || empty($password)) {
        $error = "Please enter both Faculty Name and Password.";
    } else {
        // Query faculty_users by faculty_name using prepared statement
        $stmt = $conn->prepare("SELECT * FROM faculty_users WHERE faculty_name = ?");
        $stmt->bind_param("s", $faculty_name);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Existing faculty -> verify password
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['role'] = 'faculty';
                $_SESSION['faculty_name'] = $row['faculty_name'];
                $_SESSION['subject'] = $row['subject'];
                $_SESSION['lab_room'] = $row['lab_room'];
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Incorrect password for Faculty account: " . htmlspecialchars($faculty_name);
            }
        } else {
            // New Faculty -> Auto-Register with hashed password
            if (empty($subject) || empty($lab_room)) {
                $error = "New faculty account detected. Please specify Subject and Lab Room to complete auto-registration.";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $insert_stmt = $conn->prepare("INSERT INTO faculty_users (faculty_name, subject, lab_room, password) VALUES (?, ?, ?, ?)");
                $insert_stmt->bind_param("ssss", $faculty_name, $subject, $lab_room, $hashed_password);

                if ($insert_stmt->execute()) {
                    $_SESSION['role'] = 'faculty';
                    $_SESSION['faculty_name'] = $faculty_name;
                    $_SESSION['subject'] = $subject;
                    $_SESSION['lab_room'] = $lab_room;
                    header("Location: dashboard.php");
                    exit();
                } else {
                    $error = "Auto-registration error: " . $conn->error;
                }
                $insert_stmt->close();
            }
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Portal | ZCOER Smart Lab</title>
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
        .glow-cyan-btn {
            box-shadow: 0 0 25px rgba(6, 182, 212, 0.45);
            transition: all 0.25s ease;
        }
        .glow-cyan-btn:hover {
            box-shadow: 0 0 35px rgba(6, 182, 212, 0.7);
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="min-h-screen campus-bg relative flex flex-col justify-between">

    <!-- Dark Navy Blur Overlay -->
    <div class="absolute inset-0 bg-[#0a2351]/85 backdrop-blur-sm z-0"></div>

    <!-- Top Minimal Navigation -->
    <header class="relative z-10 p-4 sm:p-6 flex items-center justify-between max-w-6xl mx-auto w-full">
        <a href="index.php" class="flex items-center gap-3 text-white/90 hover:text-white transition">
            <div class="bg-white p-1 rounded-xl shadow-md w-9 h-9 flex items-center justify-center">
                <img src="zeal-logo.png" alt="ZCOER Logo" class="h-7 w-auto object-contain">
            </div>
            <span class="text-xs sm:text-sm font-bold tracking-wide font-heading hidden sm:inline">
                ZCOER Smart Lab System
            </span>
        </a>

        <a href="index.php" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white text-xs font-semibold px-4 py-2 rounded-xl backdrop-blur-md border border-white/20 transition duration-200">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Back to Home</span>
        </a>
    </header>

    <!-- Centered Frosted Glass Card Container -->
    <main class="relative z-10 flex items-center justify-center px-4 py-6 my-auto">
        <div class="w-full max-w-md bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl shadow-2xl p-7 sm:p-9 text-white">
            
            <!-- Top of Card: Centered Logo inside Clean White Circular Badge -->
            <div class="flex flex-col items-center text-center mb-6">
                <div class="w-20 h-20 rounded-full bg-white p-2.5 shadow-2xl flex items-center justify-center mb-4 ring-4 ring-cyan-400/30">
                    <img src="zeal-logo.png" alt="ZCOER College Logo" class="h-full w-auto object-contain">
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white font-heading tracking-tight">
                    Faculty Portal
                </h1>
                <p class="text-xs text-cyan-300 font-medium mt-1">
                    Zeal College of Engineering &amp; Research
                </p>
                <span class="text-[11px] text-slate-300 mt-0.5">
                    Dept. of Electronics &amp; Computer Engineering
                </span>
            </div>

            <!-- Inline Error Alert -->
            <?php if (!empty($error)): ?>
            <div class="mb-5 p-3.5 rounded-xl bg-rose-500/20 border border-rose-500/40 text-rose-200 text-xs flex items-start gap-3 backdrop-blur-md">
                <i class="fa-solid fa-circle-exclamation text-rose-400 text-sm mt-0.5 shrink-0"></i>
                <div class="leading-relaxed"><?php echo htmlspecialchars($error); ?></div>
            </div>
            <?php endif; ?>

            <!-- Form -->
            <form action="faculty_login.php" method="POST" class="space-y-4">
                
                <!-- Faculty Name -->
                <div>
                    <label for="faculty_name" class="block text-xs font-semibold text-slate-200 mb-1.5">
                        Faculty Name
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-sm">
                            <i class="fa-solid fa-user-tie"></i>
                        </span>
                        <input 
                            type="text" 
                            id="faculty_name" 
                            name="faculty_name" 
                            required 
                            placeholder="e.g., Dr. Sumit R. Patil" 
                            value="<?php echo htmlspecialchars($_POST['faculty_name'] ?? ''); ?>"
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-white/10 border border-white/20 text-white placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent transition"
                        >
                    </div>
                </div>

                <!-- Subject -->
                <div>
                    <label for="subject" class="block text-xs font-semibold text-slate-200 mb-1.5">
                        Subject / Course
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-sm">
                            <i class="fa-solid fa-book-open"></i>
                        </span>
                        <input 
                            type="text" 
                            id="subject" 
                            name="subject" 
                            placeholder="e.g., DEM / DSA / PBL" 
                            value="<?php echo htmlspecialchars($_POST['subject'] ?? ''); ?>"
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-white/10 border border-white/20 text-white placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent transition"
                        >
                    </div>
                </div>

                <!-- Lab Room -->
                <div>
                    <label for="lab_room" class="block text-xs font-semibold text-slate-200 mb-1.5">
                        Lab Room Number
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-sm">
                            <i class="fa-solid fa-door-open"></i>
                        </span>
                        <input 
                            type="text" 
                            id="lab_room" 
                            name="lab_room" 
                            placeholder="e.g., C5-08 or C5-10" 
                            value="<?php echo htmlspecialchars($_POST['lab_room'] ?? ''); ?>"
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-white/10 border border-white/20 text-white placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent transition"
                        >
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-200 mb-1.5">
                        Security Password
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-sm">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required 
                            placeholder="••••••••••••" 
                            class="w-full pl-10 pr-11 py-2.5 rounded-xl bg-white/10 border border-white/20 text-white placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent transition"
                        >
                        <button 
                            type="button" 
                            id="togglePassword" 
                            onclick="togglePasswordVisibility('password', 'eyeIcon')"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-cyan-300 transition focus:outline-none cursor-pointer"
                            aria-label="Toggle password visibility"
                            title="Show/Hide Password"
                        >
                            <i id="eyeIcon" class="fa-solid fa-eye text-sm"></i>
                        </button>
                    </div>
                    <p class="text-[11px] text-cyan-200/70 mt-1">
                        New faculty will be securely auto-registered on first login.
                    </p>
                </div>

                <!-- Glowing Cyan Button -->
                <div class="pt-3">
                    <button 
                        type="submit" 
                        class="w-full glow-cyan-btn bg-cyan-500 hover:bg-cyan-400 text-[#0a2351] font-extrabold text-sm py-3 px-6 rounded-xl transition duration-200 flex items-center justify-center gap-2 font-heading"
                    >
                        <i class="fa-solid fa-shield-halved"></i>
                        <span>Secure Login</span>
                    </button>
                </div>

            </form>

            <!-- Bottom Note -->
            <div class="mt-6 pt-5 border-t border-white/10 text-center">
                <a href="index.php" class="text-xs text-slate-300 hover:text-cyan-300 transition inline-flex items-center gap-2">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                    Back to Home
                </a>
            </div>

        </div>
    </main>

    <!-- Footer Identity -->
    <footer class="relative z-10 p-4 text-center text-xs text-slate-400">
        &copy; <?php echo date('Y'); ?> Zeal College of Engineering &amp; Research, Narhe, Pune-41.
    </footer>

    <!-- Password Eye Toggle Script -->
    <script>
        function togglePasswordVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (!input || !icon) return;

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
