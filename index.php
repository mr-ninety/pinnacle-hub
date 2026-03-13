<?php
// index.php - Public Portfolio / Landing Page
session_start();
require_once 'config.example.php'; // fallback – in real use you'll have config.php
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth" data-theme="dark">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Unnnati | Naveen – Full-Stack Developer & Systems Builder</title>
  
  <!-- SEO basics -->
  <meta name="description" content="Full-stack PHP developer from Bengaluru. Building clean, fast personal & business dashboards – portfolio, ERP, asset tracking, personal growth."/>
  
  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          colors: {
            primary: '#10b981',
            'primary-dark': '#059669',
          }
        }
      }
    }
  </script>

  <!-- AOS animations -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

  <!-- Custom styles -->
  <link rel="stylesheet" href="style.css">

  <!-- PWA manifest -->
  <link rel="manifest" href="manifest.json">
  <meta name="theme-color" content="#10b981">

  <!-- Favicon (add your own later) -->
  <link rel="icon" href="uploads/favicon.ico" type="image/x-icon">
</head>
<body class="min-h-screen bg-gradient-to-b from-slate-950 to-slate-900 text-slate-100 antialiased">

  <!-- Theme Toggle + Mobile Menu Button -->
  <header class="fixed top-0 left-0 right-0 z-50 bg-slate-950/80 backdrop-blur-lg border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
      <a href="./" class="text-2xl font-bold text-emerald-400">Unnnati</a>
      
      <div class="flex items-center gap-6">
        <button id="theme-toggle" class="text-2xl theme-toggle" aria-label="Toggle dark/light mode">
          🌙
        </button>
        <a href="login.php" class="btn btn-outline px-6 py-2 text-sm font-medium">
          Login / Dashboard
        </a>
      </div>
    </div>
  </header>

  <!-- Hero -->
  <section class="min-h-screen flex items-center pt-24 pb-20">
    <div class="max-w-6xl mx-auto px-6 grid md:grid-cols-2 gap-16 items-center">
      <div data-aos="fade-right" data-aos-duration="800">
        <h1 class="text-5xl md:text-7xl font-extrabold leading-tight mb-6">
          Hi, I'm <span class="text-emerald-400">Naveen</span>
        </h1>
        <p class="text-xl md:text-2xl text-slate-300 mb-8">
          Full-Stack Developer • Systems Builder • Bengaluru
        </p>
        <p class="text-lg text-slate-400 mb-10 max-w-xl">
          Creating clean, fast, maintainable tools: personal dashboards, ERP systems, asset trackers, habit & finance journals — all in pure PHP.
        </p>
        <div class="flex flex-wrap gap-4">
          <a href="resume.pdf" class="btn btn-primary px-8 py-4 text-lg" download>
            Download Resume (PDF)
          </a>
          <a href="#projects" class="btn btn-outline px-8 py-4 text-lg">
            Explore Projects
          </a>
        </div>
      </div>

      <div data-aos="fade-left" data-aos-duration="1000" class="relative">
        <div class="aspect-square rounded-3xl overflow-hidden border-4 border-emerald-500/30 shadow-2xl shadow-emerald-900/40">
          <!-- Replace with your photo -->
          <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&auto=format&fit=crop&q=80" 
               alt="Naveen – Developer Portrait" 
               class="w-full h-full object-cover">
        </div>
        <!-- Floating badges -->
        <div class="absolute -bottom-6 -right-6 bg-slate-800 border border-slate-700 rounded-2xl px-6 py-4 shadow-xl">
          <p class="text-emerald-400 font-bold">5+ Years Experience</p>
          <p class="text-sm text-slate-400">PHP • MySQL • Tailwind • JS</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Skills Timeline -->
  <section id="skills" class="py-24 bg-slate-900/50">
    <div class="max-w-5xl mx-auto px-6">
      <h2 class="text-4xl md:text-5xl font-bold text-center mb-16" data-aos="fade-up">
        Core <span class="text-emerald-400">Skills</span>
      </h2>
      
      <div class="space-y-12">
        <!-- Skill item -->
        <div class="flex flex-col md:flex-row gap-8 items-center" data-aos="fade-up" data-aos-delay="100">
          <div class="w-32 h-32 rounded-full bg-emerald-900/40 flex items-center justify-center text-5xl flex-shrink-0">
            ⚡
          </div>
          <div>
            <h3 class="text-2xl font-bold mb-3">Backend & Databases</h3>
            <p class="text-slate-300">Pure PHP • MySQLi / PDO • Secure CRUD • Authentication • File handling • Performance tuning</p>
          </div>
        </div>

        <div class="flex flex-col md:flex-row-reverse gap-8 items-center" data-aos="fade-up" data-aos-delay="200">
          <div class="w-32 h-32 rounded-full bg-emerald-900/40 flex items-center justify-center text-5xl flex-shrink-0">
            🎨
          </div>
          <div>
            <h3 class="text-2xl font-bold mb-3">Frontend & UX</h3>
            <p class="text-slate-300">Tailwind CSS • Vanilla JS • Responsive Design • Dark Mode • AOS Animations • PWA basics</p>
          </div>
        </div>

        <div class="flex flex-col md:flex-row gap-8 items-center" data-aos="fade-up" data-aos-delay="300">
          <div class="w-32 h-32 rounded-full bg-emerald-900/40 flex items-center justify-center text-5xl flex-shrink-0">
            🛠
          </div>
          <div>
            <h3 class="text-2xl font-bold mb-3">Architecture & Tools</h3>
            <p class="text-slate-300">Single-folder apps • .htaccess routing • Security (CSRF, XSS, hashing) • Shared hosting friendly</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Testimonials -->
  <section id="testimonials" class="py-24">
    <div class="max-w-6xl mx-auto px-6">
      <h2 class="text-4xl md:text-5xl font-bold text-center mb-16" data-aos="fade-up">
        What People Say
      </h2>

      <div class="grid md:grid-cols-2 gap-8">
        <!-- Testimonial 1 -->
        <div class="card p-8" data-aos="fade-up" data-aos-delay="100">
          <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 rounded-full bg-slate-700"></div>
            <div>
              <h4 class="font-bold">Rahul Sharma</h4>
              <p class="text-sm text-slate-400">CTO @ TechNova</p>
            </div>
          </div>
          <p class="text-slate-300 italic">
            "Naveen delivered a full ERP dashboard in pure PHP — fast, clean, and zero dependencies. Truly impressive."
          </p>
        </div>

        <!-- Testimonial 2 -->
        <div class="card p-8" data-aos="fade-up" data-aos-delay="200">
          <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 rounded-full bg-slate-700"></div>
            <div>
              <h4 class="font-bold">Priya Menon</h4>
              <p class="text-sm text-slate-400">Founder @ GrowEasy</p>
            </div>
          </div>
          <p class="text-slate-300 italic">
            "The Unnnati personal dashboard changed how I track habits and finances. Simple yet powerful."
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="py-12 bg-slate-950 border-t border-slate-800 text-center text-slate-400">
    <p>© <?php echo date('Y'); ?> Naveen • Built with pure PHP • Bengaluru, India</p>
  </footer>

  <!-- Scripts -->
  <script>
    AOS.init({ once: true, duration: 800 });

    // Dark / Light mode toggle
    const toggle = document.getElementById('theme-toggle');
    const html = document.documentElement;

    function setTheme(theme) {
      html.setAttribute('data-theme', theme);
      localStorage.setItem('theme', theme);
      toggle.textContent = theme === 'dark' ? '🌙' : '☀️';
    }

    const savedTheme = localStorage.getItem('theme') || 
                      (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
    setTheme(savedTheme);

    toggle.addEventListener('click', () => {
      const current = html.getAttribute('data-theme');
      setTheme(current === 'dark' ? 'light' : 'dark');
    });

    // Register service worker
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', () => {
        navigator.serviceWorker.register('./sw.js')
          .then(reg => console.log('SW registered'))
          .catch(err => console.log('SW failed:', err));
      });
    }
  </script>
</body>
</html>