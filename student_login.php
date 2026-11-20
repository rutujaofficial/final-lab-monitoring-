<?php
// Zeal College of Engineering and Research (ZCOER), Narhe, Pune-41
// Department of Electronics and Computer Engineering
// Student Portal Authentication & Auto-Registration

session_start();
require_once 'db.php';

// Redirect if already logged in as student
if (isset($_SESSION['role']) && $_SESSION['role'] === 'student') {
    header("Location: student_dashboard.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_name = trim($_POST['student_name'] ?? '');
    $roll_no      = trim($_POST['roll_no'] ?? '');
    $pc_number    = trim($_POST['pc_number'] ?? '');
    $password     = $_POST['password'] ?? '';

    if (empty($roll_no) || empty($password)) {
        $error = "Please provide both Roll Number and Password.";
    } else {
        // Query student_users by roll_no
        $stmt = $conn->prepare("SELECT * FROM student_users WHERE roll_no = ?");
        $stmt->bind_param("s", $roll_no);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Existing student -> verify password
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                // Update PC number & status on login
                if (!empty($pc_number)) {
                    $upd = $conn->prepare("UPDATE student_users SET pc_number = ?, status = 'Active', current_window = 'Student Portal', last_updated = CURRENT_TIMESTAMP WHERE roll_no = ?");
                    $upd->bind_param("ss", $pc_number, $roll_no);
                    $upd->execute();
                    $upd->close();
                    $assigned_pc = $pc_number;
                } else {
                    $assigned_pc = $row['pc_number'];
                }

                $_SESSION['role'] = 'student';
                $_SESSION['student_name'] = $row['student_name'];
                $_SESSION['roll_no'] = $row['roll_no'];
                $_SESSION['pc_number'] = $assigned_pc;

                header("Location: student_dashboard.php");
                exit();
            } else {
                $error = "Incorrect password for Roll Number: " . htmlspecialchars($roll_no);
            }
        } else {
            // New student -> auto-register
            if (empty($student_name) || empty($pc_number)) {
                $error = "New student detected. Please enter your Full Name and PC Number to complete auto-registration.";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $insert = $conn->prepare("INSERT INTO student_users (student_name, roll_no, pc_number, password, status, current_window) VALUES (?, ?, ?, ?, 'Active', 'Student Portal')");
                $insert->bind_param("ssss", $student_name, $roll_no, $pc_number, $hashed_password);

                if ($insert->execute()) {
                    $_SESSION['role'] = 'student';
                    $_SESSION['student_name'] = $student_name;
                    $_SESSION['roll_no'] = $roll_no;
                    $_SESSION['pc_number'] = $pc_number;

                    header("Location: student_dashboard.php");
                    exit();
                } else {
                    $error = "Auto-registration error: " . $conn->error;
                }
                $insert->close();
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
    <title>Student Lab Portal | ZCOER Smart Lab</title>
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
        .btn-teal {
            background-color: #129468;
            transition: all 0.2s ease;
        }
        .btn-teal:hover {
            background-color: #0e7854;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(18, 148, 104, 0.4);
        }
    </style>
</head>
<body class="min-h-screen campus-bg relative flex flex-col justify-between">

    <!-- Dark Teal Blur Overlay -->
    <div class="absolute inset-0 bg-[#064e3b]/80 backdrop-blur-sm z-0"></div>

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

    <!-- Centered Crisp White Card Container -->
    <main class="relative z-10 flex items-center justify-center px-4 py-6 my-auto">
        <div class="w-full max-w-md bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl border-t-4 border-[#129468] p-7 sm:p-9 text-slate-800">
            
            <!-- Top of Card: Centered Logo inside Clean Badge -->
            <div class="flex flex-col items-center text-center mb-6">
                <div class="w-20 h-20 rounded-full bg-slate-50 border border-slate-200 p-2.5 shadow-md flex items-center justify-center mb-3">
                    <img src="zeal-logo.png" alt="ZCOER College Logo" class="h-full w-auto object-contain">
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0a2351] font-heading tracking-tight">
                    Student Lab Portal
                </h1>
                <p class="text-xs font-semibold text-[#129468] mt-1">
                    SY B.Tech — Electronics &amp; Computer Engg.
                </p>
                <span class="text-[11px] text-slate-500 mt-0.5">
                    Zeal College of Engineering &amp; Research, Pune-41
                </span>
            </div>

            <!-- Inline Error Alert -->
            <?php if (!empty($error)): ?>
            <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs flex items-start gap-3">
                <i class="fa-solid fa-circle-exclamation text-rose-500 text-sm mt-0.5 shrink-0"></i>
                <div class="leading-relaxed font-medium"><?php echo htmlspecialchars($error); ?></div>
            </div>
            <?php endif; ?>

            <!-- Form -->
            <form action="student_login.php" method="POST" class="space-y-4">
                
                <!-- Student Name -->
                <div>
                    <label for="student_name" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Student Full Name
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-sm">
                            <i class="fa-solid fa-user-graduate"></i>
                        </span>
                        <input 
                            type="text" 
                            id="student_name" 
                            name="student_name" 
                            placeholder="e.g., Rutuja Kadam" 
                            value="<?php echo htmlspecialchars($_POST['student_name'] ?? ''); ?>"
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-[#129468] focus:border-transparent transition"
                        >
                    </div>
                </div>

                <!-- Roll Number -->
                <div>
                    <label for="roll_no" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Roll Number <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-sm">
                            <i class="fa-solid fa-id-badge"></i>
                        </span>
                        <input 
                            type="text" 
                            id="roll_no" 
                            name="roll_no" 
                            required 
                            placeholder="e.g., EC2203" 
                            value="<?php echo htmlspecialchars($_POST['roll_no'] ?? ''); ?>"
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 placeholder-slate-400 text-sm uppercase focus:outline-none focus:ring-2 focus:ring-[#129468] focus:border-transparent transition font-semibold"
                        >
                    </div>
                </div>

                <!-- PC Number -->
                <div>
                    <label for="pc_number" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Lab PC Workstation <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-sm">
                            <i class="fa-solid fa-desktop"></i>
                        </span>
                        <input 
                            type="text" 
                            id="pc_number" 
                            name="pc_number" 
                            required 
                            placeholder="e.g., PC-03 or PC-12" 
                            value="<?php echo htmlspecialchars($_POST['pc_number'] ?? ''); ?>"
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 placeholder-slate-400 text-sm uppercase focus:outline-none focus:ring-2 focus:ring-[#129468] focus:border-transparent transition"
                        >
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Password <span class="text-rose-500">*</span>
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
                            class="w-full pl-10 pr-11 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-[#129468] focus:border-transparent transition"
                        >
                        <button 
                            type="button" 
                            id="togglePassword" 
                            onclick="togglePasswordVisibility('password', 'eyeIcon')"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-emerald-700 transition focus:outline-none cursor-pointer"
                            aria-label="Toggle password visibility"
                            title="Show/Hide Password"
                        >
                            <i id="eyeIcon" class="fa-solid fa-eye text-sm"></i>
                        </button>
                    </div>
                    <p class="text-[11px] text-slate-500 mt-1">
                        First-time students will be automatically registered into the lab system.
                    </p>
                </div>

                <!-- #129468 Enter Lab Session Button -->
                <div class="pt-3">
                    <button 
                        type="submit" 
                        class="w-full btn-teal text-white font-extrabold text-sm py-3 px-6 rounded-xl flex items-center justify-center gap-2 font-heading shadow-md"
                    >
                        <i class="fa-solid fa-right-to-bracket"></i>
                        <span>Enter Lab Session</span>
                    </button>
                </div>

            </form>

            <!-- Bottom Return to Home -->
            <div class="mt-6 pt-5 border-t border-slate-100 text-center">
                <a href="index.php" class="text-xs font-semibold text-slate-500 hover:text-[#0a2351] transition inline-flex items-center gap-2">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                    Back to Home
                </a>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="relative z-10 p-4 text-center text-xs text-emerald-200/80">
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
