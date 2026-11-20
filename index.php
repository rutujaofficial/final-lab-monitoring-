<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZCOER Smart Lab | Zeal College of Engineering and Research</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Inter & Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: {
                            DEFAULT: '#0a2351',
                            dark: '#07193b',
                            light: '#0f3478'
                        },
                        teal: {
                            DEFAULT: '#129468',
                            dark: '#0d7350',
                            light: '#17b27d'
                        },
                        cyanAccent: '#06b6d4',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
        }
        h1, h2, h3, h4, .font-heading {
            font-family: 'Poppins', sans-serif;
        }
        .glass-nav {
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .hero-bg {
            background-image: 
                linear-gradient(90deg, rgba(10, 35, 81, 0.96) 0%, rgba(10, 35, 81, 0.84) 48%, rgba(10, 35, 81, 0.38) 100%),
                url('zeal-campus-hero.png');
            background-size: cover;
            background-position: center;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .glass-card:hover {
            background: rgba(255, 255, 255, 0.18);
            border-color: rgba(6, 182, 212, 0.5);
            transform: translateY(-4px);
            box-shadow: 0 20px 30px -10px rgba(0, 0, 0, 0.3);
        }
        .glow-cyan {
            box-shadow: 0 0 20px rgba(6, 182, 212, 0.35);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col antialiased">

    <!-- Sticky Glass Navbar -->
    <header class="glass-nav sticky top-0 z-50 border-b border-slate-200/80 shadow-sm transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-4">
            
            <!-- Left Branding -->
            <a href="#home" class="flex items-center gap-3.5 group">
                <div class="bg-white p-1 rounded-2xl shadow-md border border-slate-100 flex items-center justify-center transition-transform group-hover:scale-105">
                    <img src="zeal-logo.png" alt="ZCOER Logo" class="h-12 w-auto object-contain">
                </div>
                <div>
                    <span class="block text-lg font-black tracking-tight text-[#0a2351] font-heading leading-tight group-hover:text-cyan-600 transition-colors">
                        ZCOER Smart Lab
                    </span>
                    <span class="block text-xs font-semibold text-slate-500">
                        Dept. of Electronics &amp; Computer Engg.
                    </span>
                </div>
            </a>

            <!-- Right Navigation & Buttons -->
            <div class="flex items-center gap-6">
                <nav class="hidden md:flex items-center gap-7 text-sm font-semibold text-slate-600">
                    <a href="#home" class="hover:text-[#0a2351] transition-colors py-1">Home</a>
                    <a href="#features" class="hover:text-[#0a2351] transition-colors py-1">Features</a>
                    <a href="#smart-labs" class="hover:text-[#0a2351] transition-colors py-1">Smart Labs</a>
                    <a href="#about" class="hover:text-[#0a2351] transition-colors py-1">About</a>
                </nav>

                <div class="flex items-center gap-2.5 sm:gap-3">
                    <!-- Student Login Pill Button -->
                    <a href="student_login.php" class="inline-flex items-center gap-2 bg-[#129468] hover:bg-[#0d7350] text-white text-xs sm:text-sm font-bold px-4 sm:px-5 py-2.5 rounded-full transition-all duration-200 shadow-md shadow-emerald-900/20 hover:shadow-lg hover:scale-[1.02] active:scale-95">
                        <i class="fa-solid fa-graduation-cap text-emerald-200"></i>
                        <span>Student Login</span>
                    </a>

                    <!-- Faculty Login Pill Button -->
                    <a href="faculty_login.php" class="inline-flex items-center gap-2 bg-[#0a2351] hover:bg-[#07193b] text-white text-xs sm:text-sm font-bold px-4 sm:px-5 py-2.5 rounded-full transition-all duration-200 shadow-md shadow-blue-950/20 hover:shadow-lg hover:scale-[1.02] active:scale-95 border border-cyan-400/20">
                        <i class="fa-solid fa-user-shield text-cyan-400"></i>
                        <span>Faculty Login</span>
                    </a>
                </div>
            </div>

        </div>
    </header>

    <!-- Full-Impact Hero Section -->
    <section id="home" class="hero-bg relative min-h-[640px] lg:min-h-[700px] flex items-center overflow-hidden py-16 px-4 sm:px-6 lg:px-8">
        
        <!-- Background Ambient Accent Glow -->
        <div class="absolute inset-0 bg-radial-gradient pointer-events-none opacity-40"></div>

        <div class="max-w-7xl mx-auto w-full relative z-10">
            <div class="max-w-3xl">
                
                <!-- Glowing Badges -->
                <div class="flex flex-wrap items-center gap-2.5 mb-5">
                    <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#0a2351]/80 border border-cyan-400/50 text-cyan-300 text-xs font-bold tracking-wide shadow-sm backdrop-blur-md">
                        <i class="fa-solid fa-award text-yellow-400"></i>
                        ESTD - 1996 • NAAC A+
                    </span>
                    <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#129468]/30 border border-emerald-400/40 text-emerald-300 text-xs font-bold tracking-wide backdrop-blur-md">
                        <i class="fa-solid fa-university"></i>
                        ZEAL EDUCATION SOCIETY
                    </span>
                </div>

                <!-- Bold White-and-Cyan Headline -->
                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black text-white font-heading tracking-tight leading-[1.12] mb-6 drop-shadow-md">
                    Smart Digital Lab <br class="hidden sm:inline">
                    <span class="bg-gradient-to-r from-cyan-300 via-teal-300 to-cyan-400 bg-clip-text text-transparent">
                        Monitoring System
                    </span>
                </h1>

                <!-- Concise Description -->
                <p class="text-base sm:text-lg text-slate-200 font-normal leading-relaxed mb-8 max-w-2xl drop-shadow">
                    Next-generation real-time laboratory supervision platform engineered for ZCOER Pune. Enforces academic whitelisting, detects unauthorized applications, and provides faculties with live, instantaneous monitoring of all student workstations.
                </p>

                <!-- Two Prominent CTA Buttons -->
                <div class="flex flex-wrap items-center gap-4 mb-14">
                    <a href="student_login.php" class="inline-flex items-center gap-3 bg-[#129468] hover:bg-[#0d7350] text-white text-sm sm:text-base font-bold px-7 py-3.5 rounded-xl transition duration-200 shadow-xl shadow-emerald-950/40 hover:scale-[1.02] active:scale-95 border border-emerald-400/30">
                        <i class="fa-solid fa-laptop-code text-cyan-200 text-lg"></i>
                        <span>Enter Student Portal</span>
                        <i class="fa-solid fa-arrow-right text-xs opacity-75"></i>
                    </a>

                    <a href="faculty_login.php" class="inline-flex items-center gap-3 bg-white/10 hover:bg-white/20 text-white text-sm sm:text-base font-bold px-7 py-3.5 rounded-xl transition duration-200 backdrop-blur-xl border border-white/30 shadow-xl shadow-black/20 hover:scale-[1.02] active:scale-95">
                        <i class="fa-solid fa-chalkboard-user text-cyan-400 text-lg"></i>
                        <span>Faculty Control Panel</span>
                    </a>
                </div>

            </div>

            <!-- 4-Card Glassmorphism Feature Grid -->
            <div id="features" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 mt-4 pt-6 border-t border-white/15">
                
                <!-- Card 1 -->
                <div class="glass-card rounded-2xl p-5 text-white">
                    <div class="w-11 h-11 rounded-xl bg-cyan-500/20 border border-cyan-400/40 flex items-center justify-center text-cyan-300 text-lg mb-4">
                        <i class="fa-solid fa-satellite-dish"></i>
                    </div>
                    <h3 class="text-base font-bold text-white font-heading mb-1.5">Real-Time Monitoring</h3>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Sub-second tracking of active windows, student status, and PC allocation across all departmental labs.
                    </p>
                </div>

                <!-- Card 2 -->
                <div class="glass-card rounded-2xl p-5 text-white">
                    <div class="w-11 h-11 rounded-xl bg-emerald-500/20 border border-emerald-400/40 flex items-center justify-center text-emerald-300 text-lg mb-4">
                        <i class="fa-solid fa-user-plus"></i>
                    </div>
                    <h3 class="text-base font-bold text-white font-heading mb-1.5">Secure Auto-Registration</h3>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Instant, zero-friction enrollment with bcrypt-hashed passwords for students and faculty members.
                    </p>
                </div>

                <!-- Card 3 -->
                <div class="glass-card rounded-2xl p-5 text-white">
                    <div class="w-11 h-11 rounded-xl bg-sky-500/20 border border-sky-400/40 flex items-center justify-center text-sky-300 text-lg mb-4">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <h3 class="text-base font-bold text-white font-heading mb-1.5">Academic Whitelisting</h3>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Automated compliance checks ensuring allowed tools: VS Code, Python, XAMPP, Sublime, and Dev-C++.
                    </p>
                </div>

                <!-- Card 4 -->
                <div class="glass-card rounded-2xl p-5 text-white">
                    <div class="w-11 h-11 rounded-xl bg-indigo-500/20 border border-indigo-400/40 flex items-center justify-center text-indigo-300 text-lg mb-4">
                        <i class="fa-solid fa-chart-pie"></i>
                    </div>
                    <h3 class="text-base font-bold text-white font-heading mb-1.5">Session Analytics</h3>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Instant summary KPIs, active student count, and unauthorized application alerts on faculty screens.
                    </p>
                </div>

            </div>

        </div>
    </section>

    <!-- Our Smart Labs Gallery Section -->
    <section id="smart-labs" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="inline-block text-xs font-bold uppercase tracking-wider text-[#129468] bg-emerald-50 px-3.5 py-1.5 rounded-full border border-emerald-200 mb-3">
                    Advanced Engineering Infrastructure
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-[#0a2351] font-heading tracking-tight">
                    Our Smart Laboratories
                </h2>
                <p class="mt-3 text-slate-600 text-sm sm:text-base leading-relaxed">
                    Designed to foster industry-aligned research, practical software engineering, and hands-on hardware development for Electronics &amp; Computer Engineering students.
                </p>
            </div>

            <!-- 3-Column Rounded Image Card Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- Card 1: lab-code-station.jpg -->
                <div class="group bg-white rounded-3xl overflow-hidden border border-slate-200/90 shadow-md hover:shadow-2xl transition-all duration-300 hover:-translate-y-1.5 flex flex-col">
                    <div class="relative h-60 overflow-hidden bg-slate-100">
                        <img src="lab-code-station.jpg" alt="Programming and Code Station Lab" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent"></div>
                        <span class="absolute bottom-3 left-4 bg-[#0a2351]/85 backdrop-blur-md text-cyan-300 text-xs font-bold px-3 py-1 rounded-full border border-cyan-400/30">
                            <i class="fa-solid fa-code mr-1"></i> Lab C5-10
                        </span>
                    </div>
                    <div class="p-6 flex-grow flex flex-col justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-[#0a2351] font-heading mb-2 group-hover:text-[#129468] transition-colors">
                                Programming &amp; Software Workstations
                            </h3>
                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                                Dedicated high-throughput code stations configured with full development toolchains, Python runtimes, C++ compilers, and modern containerized database suites.
                            </p>
                        </div>
                        <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                            <span class="font-semibold text-slate-700">Capacity: 40 PCs</span>
                            <span class="text-[#129468] font-bold">1 Gbps LAN</span>
                        </div>
                    </div>
                </div>

                <!-- Card 2: lab-practical.jpg -->
                <div class="group bg-white rounded-3xl overflow-hidden border border-slate-200/90 shadow-md hover:shadow-2xl transition-all duration-300 hover:-translate-y-1.5 flex flex-col">
                    <div class="relative h-60 overflow-hidden bg-slate-100">
                        <img src="lab-practical.jpg" alt="Electronics and Practical Hardware Lab" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent"></div>
                        <span class="absolute bottom-3 left-4 bg-[#0a2351]/85 backdrop-blur-md text-cyan-300 text-xs font-bold px-3 py-1 rounded-full border border-cyan-400/30">
                            <i class="fa-solid fa-microchip mr-1"></i> Lab C3-09
                        </span>
                    </div>
                    <div class="p-6 flex-grow flex flex-col justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-[#0a2351] font-heading mb-2 group-hover:text-[#129468] transition-colors">
                                Electronics &amp; Hardware Testbench
                            </h3>
                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                                Integrated hardware testing benches equipped with digital storage oscilloscopes, microcontroller trainers, FPGA boards, and signal analysis instrumentation.
                            </p>
                        </div>
                        <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                            <span class="font-semibold text-slate-700">Practical Station</span>
                            <span class="text-[#129468] font-bold">ESD Protected</span>
                        </div>
                    </div>
                </div>

                <!-- Card 3: lab-workstations.jpg -->
                <div class="group bg-white rounded-3xl overflow-hidden border border-slate-200/90 shadow-md hover:shadow-2xl transition-all duration-300 hover:-translate-y-1.5 flex flex-col">
                    <div class="relative h-60 overflow-hidden bg-slate-100">
                        <img src="lab-workstations.jpg" alt="Collaborative Research and Project Lab" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent"></div>
                        <span class="absolute bottom-3 left-4 bg-[#0a2351]/85 backdrop-blur-md text-cyan-300 text-xs font-bold px-3 py-1 rounded-full border border-cyan-400/30">
                            <i class="fa-solid fa-users-gear mr-1"></i> Lab C5-08
                        </span>
                    </div>
                    <div class="p-6 flex-grow flex flex-col justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-[#0a2351] font-heading mb-2 group-hover:text-[#129468] transition-colors">
                                Collaborative PBL Research Pods
                            </h3>
                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                                Ergonomic computing cluster tailored for Project-Based Learning (PBL), collaborative capstone projects, AI/ML inference tasks, and open-source contribution.
                            </p>
                        </div>
                        <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                            <span class="font-semibold text-slate-700">PBL Specialized</span>
                            <span class="text-[#129468] font-bold">Smart Monitored</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- About College & Department Section -->
    <section id="about" class="py-16 bg-[#f1f5f9] border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-[#0a2351] bg-blue-100/80 px-3 py-1.5 rounded-full inline-block mb-3">
                        Zeal Education Society
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-[#0a2351] font-heading tracking-tight mb-4">
                        Excellence in Engineering &amp; Innovation
                    </h2>
                    <p class="text-sm text-slate-600 leading-relaxed mb-4">
                        Established in 1996 under the visionary leadership of <strong>Hon. Shri. S. M. Katkar</strong>, Zeal Education Society has blossomed into a premier institution imparting industry-focused technical education in Pune.
                    </p>
                    <p class="text-sm text-slate-600 leading-relaxed mb-6">
                        The <strong>Department of Electronics and Computer Engineering (ECE)</strong> bridges the synergy of embedded hardware systems with cutting-edge software paradigms, preparing students for emerging careers in Embedded AI, IoT, Edge Computing, and Cloud Infrastructure.
                    </p>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 pt-2">
                        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200/80">
                            <span class="block text-2xl font-black text-[#0a2351] font-heading">A+</span>
                            <span class="text-xs font-semibold text-slate-500">NAAC Accreditation</span>
                        </div>
                        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200/80">
                            <span class="block text-2xl font-black text-[#129468] font-heading">1996</span>
                            <span class="text-xs font-semibold text-slate-500">Estd. Legacy</span>
                        </div>
                        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200/80 col-span-2 sm:col-span-1">
                            <span class="block text-2xl font-black text-cyan-600 font-heading">100%</span>
                            <span class="text-xs font-semibold text-slate-500">Digital Smart Labs</span>
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <div class="relative rounded-3xl overflow-hidden shadow-2xl border-4 border-white">
                        <img src="zeal-campus-hero.png" alt="ZCOER Campus" class="w-full h-80 sm:h-96 object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0a2351]/80 via-transparent to-transparent flex items-end p-6">
                            <div class="text-white">
                                <span class="text-xs font-bold text-cyan-300 uppercase tracking-widest block mb-1">Campus View</span>
                                <h4 class="text-lg font-bold font-heading">ZCOER Main Engineering Complex</h4>
                                <p class="text-xs text-slate-200">Narhe, Pune-41, Maharashtra, India</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Deep Navy Footer -->
    <footer class="bg-[#0a2351] text-white border-t border-cyan-500/20 pt-12 pb-8 px-4 sm:px-6 lg:px-8 mt-auto">
        <div class="max-w-7xl mx-auto">
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 pb-10 border-b border-white/10">
                
                <!-- Col 1: Identity & Logo -->
                <div class="md:col-span-2">
                    <div class="flex items-center gap-3.5 mb-4">
                        <div class="bg-white p-1 rounded-2xl shadow-md flex items-center justify-center">
                            <img src="zeal-logo.png" alt="ZCOER Logo" class="h-12 w-auto object-contain">
                        </div>
                        <div>
                            <span class="block text-lg font-black tracking-tight text-white font-heading">
                                ZEAL COLLEGE OF ENGINEERING &amp; RESEARCH
                            </span>
                            <span class="block text-xs font-medium text-cyan-300">
                                Department of Electronics &amp; Computer Engineering
                            </span>
                        </div>
                    </div>
                    <p class="text-xs text-slate-300 leading-relaxed max-w-md">
                        Smart Digital Lab Monitoring System — Designed to provide secure, transparent, and productive computing environments for practical coursework and laboratory sessions.
                    </p>
                    <p class="text-xs text-slate-400 mt-3 flex items-center gap-2">
                        <i class="fa-solid fa-location-dot text-rose-400"></i>
                        Survey No. 39, Narhe, Pune - 411041, Maharashtra, India.
                    </p>
                </div>

                <!-- Col 2: Quick Links -->
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-cyan-300 font-heading mb-4">Navigation</h4>
                    <ul class="space-y-2 text-xs text-slate-300">
                        <li><a href="#home" class="hover:text-white transition">Home Overview</a></li>
                        <li><a href="#features" class="hover:text-white transition">System Capabilities</a></li>
                        <li><a href="#smart-labs" class="hover:text-white transition">Laboratories Gallery</a></li>
                        <li><a href="#about" class="hover:text-white transition">About ZCOER ECE</a></li>
                        <li><a href="add_teachers.php" class="hover:text-cyan-300 transition">Faculty Database Seeder</a></li>
                    </ul>
                </div>

                <!-- Col 3: Portal Access -->
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-cyan-300 font-heading mb-4">System Portals</h4>
                    <div class="space-y-2.5">
                        <a href="student_login.php" class="flex items-center gap-2 text-xs bg-white/10 hover:bg-[#129468] p-2.5 rounded-xl border border-white/15 transition text-white">
                            <i class="fa-solid fa-laptop-code text-emerald-300"></i>
                            <span>Student Login Portal</span>
                        </a>
                        <a href="faculty_login.php" class="flex items-center gap-2 text-xs bg-white/10 hover:bg-cyan-700 p-2.5 rounded-xl border border-white/15 transition text-white">
                            <i class="fa-solid fa-user-shield text-cyan-300"></i>
                            <span>Faculty Control Panel</span>
                        </a>
                    </div>
                </div>

            </div>

            <!-- Bottom Copyright -->
            <div class="pt-6 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-400 gap-3">
                <p>&copy; <?php echo date('Y'); ?> Zeal College of Engineering and Research (ZCOER). All rights reserved.</p>
                <p class="text-slate-400">Department of Electronics &amp; Computer Engineering (PBL Division)</p>
            </div>

        </div>
    </footer>

</body>
</html>
