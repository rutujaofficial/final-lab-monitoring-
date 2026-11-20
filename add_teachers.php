<?php
// Zeal College of Engineering and Research (ZCOER), Narhe, Pune-41
// Department of Electronics and Computer Engineering
// Pre-Load Timetable Faculty Seeder Script

require_once 'db.php';

$teachers = [
    [
        'name'     => 'Dr. Sumit R. Patil',
        'subject'  => 'DEM (Digital Electronics & Microcontroller)',
        'lab_room' => 'C5-08'
    ],
    [
        'name'     => 'Ms. Supriya Patil',
        'subject'  => 'DSA (Data Structures & Algorithms)',
        'lab_room' => 'C5-11'
    ],
    [
        'name'     => 'Mr. Mukesh Patil',
        'subject'  => 'COA (Computer Organization & Architecture)',
        'lab_room' => 'C5-10'
    ],
    [
        'name'     => 'Ms. Tejaswini Pawar',
        'subject'  => 'EM3 (Engineering Mathematics III)',
        'lab_room' => 'C5-10'
    ],
    [
        'name'     => 'Ms. Sampada Gaikwad',
        'subject'  => 'OE-1 (Open Elective 1)',
        'lab_room' => 'C5-09'
    ],
    [
        'name'     => 'Mr. Pravin Sangle',
        'subject'  => 'EW (Electronic Workshop)',
        'lab_room' => 'C3-09'
    ],
    [
        'name'     => 'Ms. Basamma Math',
        'subject'  => 'PST1 (Problem Solving Techniques 1)',
        'lab_room' => 'C3-03'
    ],
    [
        'name'     => 'Ms. Sana Metkari',
        'subject'  => 'PBL (Project Based Learning)',
        'lab_room' => 'C3-04'
    ],
];

$universal_password = 'Zcoer@123';
$hashed_password = password_hash($universal_password, PASSWORD_DEFAULT);

$added_count = 0;
$skipped_count = 0;
$details = [];

// Prepare statements
$check_stmt = $conn->prepare("SELECT id FROM faculty_users WHERE faculty_name = ?");
$insert_stmt = $conn->prepare("INSERT INTO faculty_users (faculty_name, subject, lab_room, password) VALUES (?, ?, ?, ?)");

foreach ($teachers as $t) {
    $check_stmt->bind_param("s", $t['name']);
    $check_stmt->execute();
    $res = $check_stmt->get_result();

    if ($res->num_rows > 0) {
        // Sync credentials so universal password Zcoer@123 is guaranteed for all 8 teachers
        $update_pwd = $conn->prepare("UPDATE faculty_users SET password = ?, subject = ?, lab_room = ? WHERE faculty_name = ?");
        $update_pwd->bind_param("ssss", $hashed_password, $t['subject'], $t['lab_room'], $t['name']);
        $update_pwd->execute();
        $update_pwd->close();

        $skipped_count++;
        $details[] = [
            'name'     => $t['name'],
            'subject'  => $t['subject'],
            'lab_room' => $t['lab_room'],
            'status'   => 'Verified (Password: Zcoer@123)'
        ];
    } else {
        $insert_stmt->bind_param("ssss", $t['name'], $t['subject'], $t['lab_room'], $hashed_password);
        if ($insert_stmt->execute()) {
            $added_count++;
            $details[] = [
                'name'     => $t['name'],
                'subject'  => $t['subject'],
                'lab_room' => $t['lab_room'],
                'status'   => 'Successfully Added'
            ];
        } else {
            $skipped_count++;
            $details[] = [
                'name'     => $t['name'],
                'subject'  => $t['subject'],
                'lab_room' => $t['lab_room'],
                'status'   => 'Insert Failed: ' . $conn->error
            ];
        }
    }
}

$check_stmt->close();
$insert_stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Seeder | ZCOER Smart Lab</title>
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
            background-color: #0a2351;
        }
        h1, h2, h3, .font-heading {
            font-family: 'Poppins', sans-serif;
        }
        .bg-campus-overlay {
            background-image: 
                linear-gradient(135deg, rgba(10, 35, 81, 0.94) 0%, rgba(10, 35, 81, 0.88) 50%, rgba(18, 148, 104, 0.80) 100%),
                url('zeal-campus-hero.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
    </style>
</head>
<body class="min-h-screen bg-campus-overlay text-slate-100 flex flex-col justify-between p-4 sm:p-6 md:p-8">

    <!-- Header Banner -->
    <header class="max-w-4xl mx-auto w-full mb-6">
        <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl p-4 sm:p-5 shadow-2xl flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <div class="bg-white p-1.5 rounded-xl shadow-md shrink-0 flex items-center justify-center w-12 h-12">
                    <img src="zeal-logo.png" alt="ZCOER Logo" class="h-10 w-auto object-contain">
                </div>
                <div>
                    <h1 class="text-base sm:text-lg font-bold text-white tracking-wide font-heading">Zeal College of Engineering &amp; Research</h1>
                    <p class="text-xs text-cyan-300 font-medium">Department of Electronics &amp; Computer Engineering (Narhe, Pune-41)</p>
                </div>
            </div>
            <a href="faculty_login.php" class="inline-flex items-center gap-2 bg-[#129468] hover:bg-[#0e7a55] text-white text-xs font-semibold px-4 py-2.5 rounded-xl transition duration-200 shadow-lg shadow-emerald-900/30">
                <i class="fa-solid fa-arrow-right-to-bracket"></i>
                Faculty Login
            </a>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="max-w-4xl mx-auto w-full my-auto">
        <div class="bg-white/95 backdrop-blur-2xl rounded-3xl p-6 sm:p-8 shadow-2xl border border-white/40 text-slate-800">
            
            <!-- Title & Status Badge -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-200">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-100 text-cyan-800 text-xs font-bold uppercase tracking-wider mb-2">
                        <i class="fa-solid fa-database text-cyan-600"></i> Faculty Database Seeder
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-[#0a2351] font-heading">SY B.Tech Div A Faculty Setup</h2>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">Universal login password for preloaded faculty accounts: <code class="bg-slate-100 px-2 py-0.5 rounded text-rose-600 font-bold border border-slate-200">Zcoer@123</code></p>
                </div>
                
                <!-- Counter Badges -->
                <div class="flex items-center gap-3">
                    <div class="bg-emerald-50 border border-emerald-200 rounded-2xl px-4 py-2.5 text-center">
                        <span class="block text-xl font-black text-[#129468]"><?php echo $added_count; ?></span>
                        <span class="text-[10px] font-bold uppercase text-emerald-700 tracking-wider">Added</span>
                    </div>
                    <div class="bg-slate-100 border border-slate-200 rounded-2xl px-4 py-2.5 text-center">
                        <span class="block text-xl font-black text-slate-700"><?php echo $skipped_count; ?></span>
                        <span class="text-[10px] font-bold uppercase text-slate-500 tracking-wider">Skipped</span>
                    </div>
                </div>
            </div>

            <!-- Table of Teachers -->
            <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 shadow-sm">
                <table class="w-full text-left text-xs sm:text-sm border-collapse">
                    <thead>
                        <tr class="bg-[#0a2351] text-white">
                            <th class="py-3 px-4 font-semibold">Faculty Name</th>
                            <th class="py-3 px-4 font-semibold">Subject / Code</th>
                            <th class="py-3 px-4 font-semibold">Lab Room</th>
                            <th class="py-3 px-4 font-semibold text-right">Result</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        <?php foreach ($details as $row): ?>
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3 px-4 font-bold text-slate-900 flex items-center gap-2">
                                <span class="w-7 h-7 rounded-full bg-blue-100 text-[#0a2351] inline-flex items-center justify-center text-xs">
                                    <i class="fa-solid fa-chalkboard-user"></i>
                                </span>
                                <?php echo htmlspecialchars($row['name']); ?>
                            </td>
                            <td class="py-3 px-4 text-slate-600 font-medium"><?php echo htmlspecialchars($row['subject']); ?></td>
                            <td class="py-3 px-4">
                                <span class="inline-block bg-slate-100 text-slate-700 font-bold px-2.5 py-0.5 rounded text-xs border border-slate-200">
                                    <?php echo htmlspecialchars($row['lab_room']); ?>
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <?php if (strpos($row['status'], 'Added') !== false): ?>
                                    <span class="inline-flex items-center gap-1.5 bg-emerald-100 text-emerald-800 text-[11px] font-bold px-2.5 py-1 rounded-full">
                                        <i class="fa-solid fa-circle-check text-emerald-600"></i> Added
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-600 text-[11px] font-bold px-2.5 py-1 rounded-full">
                                        <i class="fa-solid fa-circle-info text-slate-400"></i> Exists
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4 pt-4 border-t border-slate-200">
                <a href="index.php" class="text-xs font-semibold text-slate-500 hover:text-[#0a2351] transition flex items-center gap-2">
                    <i class="fa-solid fa-arrow-left"></i> Return to Homepage
                </a>
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <a href="faculty_login.php" class="w-full sm:w-auto text-center bg-[#0a2351] hover:bg-[#07193b] text-white text-xs sm:text-sm font-bold px-6 py-3 rounded-xl transition duration-200 shadow-lg shadow-blue-950/30 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-user-shield text-cyan-400"></i> Proceed to Faculty Login
                    </a>
                </div>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="max-w-4xl mx-auto w-full mt-6 text-center text-xs text-blue-200/80">
        <p>&copy; <?php echo date('Y'); ?> Zeal College of Engineering and Research, Pune-41. Department of Electronics and Computer Engineering.</p>
    </footer>

</body>
</html>
