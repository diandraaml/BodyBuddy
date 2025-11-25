<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BodyBuddy - Your Fitness Companion</title>
    <link rel="stylesheet" href="App/assets/css/global.css">
    <link rel="stylesheet" href="App/assets/css/home.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-container">
            <div class="logo">
                <img src="App/uploads/logo.png" alt="BodyBuddy Logo" class="logo-img">
                <h1>BodyBud</h1>
            </div>
            <nav class="nav-links">
                <a href="index.php?page=home">Home</a>
                <a href="index.php?page=auth">Login</a>
                <a href="index.php?page=auth&action=register" class="btn-register">Register</a>
            </nav>
        </div>
    </nav>

    <!-- Hero Section Fullwidth -->
    <section class="hero-fullwidth">
        <div class="hero-content">
            <div class="hero-text">
                <h1 class="hero-title">WORK OUT<br>WITH ME</h1>
                <p class="hero-description">
                    Transformasikan diri Anda dengan program fitness lengkap.<br>
                    Dapatkan panduan workout, nutrisi, dan konsultasi trainer profesional.
                </p>
                <div class="hero-buttons">
                    <a href="index.php?page=auth&action=register" class="btn-hero-primary">Join the workout!</a>
                    <a href="#features" class="btn-hero-secondary">Learn More</a>
                </div>
                <div class="hero-social">
                    <span>Follow us:</span>
                    <div class="social-icons">
                        <a href="#"><img src="App/assets/images/facebook.png" alt="Facebook"></a>
                        <a href="#"><img src="App/assets/images/instagram.png" alt="Instagram"></a>
                        <a href="#"><img src="App/assets/images/youtube.png" alt="YouTube"></a>
                    </div>
                </div>
            </div>
            <div class="hero-image">
                <img src="App/uploads/run.png" alt="Workout" class="runner-image">
            </div>
        </div>
        <div class="hero-nav-dots">
            <span class="dot active"></span>
            <span class="dot"></span>
            <span class="dot"></span>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section">
        <div class="container">
            <div class="card about-card">
                <h2>Tentang BodyBuddy</h2>
                <p>BodyBuddy adalah platform fitness komprehensif yang membantu Anda mencapai target kesehatan dan kebugaran. Dengan kombinasi workout terstruktur, panduan nutrisi, dan konsultasi dengan trainer profesional, perjalanan fitness Anda akan lebih mudah dan menyenangkan.</p>
            </div>
        </div>
    </section>

    <!-- Workout Categories -->
    <section class="workout-categories">
        <div class="container">
            <h2 class="section-title">Jenis Workout Tersedia</h2>
            <div class="grid workout-grid">
                <div class="grid-item workout-card">
                    <div class="workout-icon"><img src="App/uploads/arm.png"></div>
                    <h3>Arm Workout</h3>
                    <p>Program latihan untuk membentuk dan menguatkan otot lengan Anda. Termasuk push-ups, bicep curls, dan tricep dips.</p>
                </div>
                <div class="grid-item workout-card">
                    <div class="workout-icon" ><img src="App/uploads/back.png"></div>
                    <h3>Back Workout</h3>
                    <p>Latihan yang fokus pada penguatan otot punggung untuk postur tubuh yang lebih baik. Termasuk pull-ups dan bent-over rows.</p>
                </div>
                <div class="grid-item workout-card">
                    <div class="workout-icon"><img src="App/uploads/leg.png"></div>
                    <h3>Leg Workout</h3>
                    <p>Program latihan kaki yang efektif untuk membentuk otot paha dan betis. Termasuk squats, lunges, dan calf raises.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section" id="features">
        <div class="container">
            <h2 class="section-title">Fitur Unggulan</h2>
            <div class="grid features-grid">
                <div class="grid-item feature-card">
                    <div class="feature-icon"><img src="App/uploads/track.png"></div>
                    <h3>Tracking Progress</h3>
                    <p>Pantau perkembangan berat badan dan BMI Anda secara real-time</p>
                </div>
                <div class="grid-item feature-card">
                    <div class="feature-icon"><img src="App/uploads/makan.png"></div>
                    <h3>Panduan Nutrisi</h3>
                    <p>Akses database makanan lengkap dengan informasi kalori dan nutrisi</p>
                </div>
                <div class="grid-item feature-card">
                    <div class="feature-icon"><img src="App/uploads/konsultasi.png"></div>
                    <h3>Konsultasi Trainer</h3>
                    <p>Berkonsultasi langsung dengan trainer profesional</p>
                </div>
                <div class="grid-item feature-card">
                    <div class="feature-icon"><img src="App/uploads/kalori.png"></div>
                    <h3>Calorie Tracking</h3>
                    <p>Hitung kalori masuk dan kalori yang terbakar setiap hari</p>
                </div>
                <div class="grid-item feature-card">
                    <div class="feature-icon"><img src="App/uploads/BMI.png"></div>
                    <h3>BMI Calculator</h3>
                    <p>Ukur dan kategorikan berat badan Anda (ideal, berlebihan, dll)</p>
                </div>
                <div class="grid-item feature-card">
                    <div class="feature-icon"><img src="App/uploads/goal.png"></div>
                    <h3>Goal Setting</h3>
                    <p>Tetapkan target berat badan dan pantau pencapaiannya</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">

            <div class="footer-left">
                <h2>BODYBUD</h2>
                <p fill = "#fff">Your personal fitness buddy to track workouts, stay motivated, and grow stronger every day.</p>
            </div>

            <div class="footer-links">
                <h3>Menu</h3>
                <a href="/">Home</a>
                <a href="/dashboard">Dashboard</a>
                <a href="#">Workouts</a>
                <a href="#">Progress</a>
            </div>

            <div class="footer-links">
                <h3>Support</h3>
                <a href="#">FAQ</a>
                <a href="#">Contact</a>
                <a href="#">Feedback</a>
            </div>

            <div class="footer-social">
                <h3>Follow Us</h3>
                <div class="social-icons">
                    <a href="#"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#4A5D3F" viewBox="0 0 24 24"><path d="M22.23 0H1.77A1.77 1.77 0 000 1.77v20.46A1.77 1.77 0 001.77 24h11V14.7h-3V11h3V8.41c0-3 1.8-4.66 4.53-4.66 1.31 0 2.68.23 2.68.23v3h-1.51c-1.49 0-1.95.92-1.95 1.86V11h3.32l-.53 3.7H17.5V24h4.73A1.77 1.77 0 0024 22.23V1.77A1.77 1.77 0 0022.23 0z"/></svg></a>

                    <a href="#"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#4A5D3F" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775A4.958 4.958 0 0023.337 3a9.864 9.864 0 01-3.127 1.2A4.92 4.92 0 0016.616 3c-2.72 0-4.924 2.208-4.924 4.917 0 .39.045.765.127 1.124C7.728 8.89 4.1 6.89 1.67 3.9a4.822 4.822 0 00-.666 2.475c0 1.708.875 3.214 2.207 4.096a4.903 4.903 0 01-2.225-.616v.06c0 2.385 1.723 4.374 4.067 4.827a4.996 4.996 0 01-2.212.084c.623 1.934 2.445 3.342 4.6 3.383A9.868 9.868 0 010 19.54 13.94 13.94 0 007.548 22c9.142 0 14.307-7.72 13.995-14.646A9.936 9.936 0 0024 4.59z"/></svg></a>

                    <a href="#"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#4A5D3F" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 1.17.054 1.97.24 2.43.403a4.92 4.92 0 011.73 1.122 4.92 4.92 0 011.122 1.73c.163.46.349 1.26.403 2.43.058 1.266.07 1.646.07 4.85s-.012 3.584-.07 4.85c-.054 1.17-.24 1.97-.403 2.43a4.92 4.92 0 01-1.122 1.73 4.92 4.92 0 01-1.73 1.122c-.46.163-1.26.349-2.43.403-1.266.058-1.646.07-4.85.07s-3.584-.012-4.85-.07c-1.17-.054-1.97-.24-2.43-.403a4.92 4.92 0 01-1.73-1.122 4.92 4.92 0 01-1.122-1.73c-.163-.46-.349-1.26-.403-2.43C2.175 15.747 2.163 15.367 2.163 12s.012-3.584.07-4.85c.054-1.17.24-1.97.403-2.43a4.92 4.92 0 011.122-1.73A4.92 4.92 0 015.488 1.76c.46-.163 1.26-.349 2.43-.403C9.184 1.299 9.564 1.287 12 1.287m0-1.287C8.735 0 8.332.013 7.053.072 5.775.131 4.89.322 4.18.588a6.18 6.18 0 00-2.24 1.462A6.18 6.18 0 00.477 4.29c-.266.71-.457 1.595-.516 2.873C-.013 8.442 0 8.845 0 12c0 3.155-.013 3.558.072 4.837.059 1.278.25 2.163.516 2.873.33.88.82 1.64 1.462 2.24a6.18 6.18 0 002.24 1.462c.71.266 1.595.457 2.873.516C8.332 24.013 8.735 24 12 24s3.668.013 4.947-.072c1.278-.059 2.163-.25 2.873-.516a6.18 6.18 0 002.24-1.462 6.18 6.18 0 001.462-2.24c.266-.71.457-1.595.516-2.873.059-1.279.072-1.682.072-4.837 0-3.155-.013-3.558-.072-4.837-.059-1.278-.25-2.163-.516-2.873a6.18 6.18 0 00-1.462-2.24A6.18 6.18 0 0019.82.588c-.71-.266-1.595-.457-2.873-.516C15.668.013 15.265 0 12 0z"/></svg></a>
                </div>
            </div>

        </div>

        <div class="footer-bottom">
            <p>© 2025 BODYBUD — All Rights Reserved.</p>
        </div>
    </footer>

    <script src="App/assets/js/main.js"></script>
</body>
</html>