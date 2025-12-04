<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $workout['workout_name']; ?> - BodyBuddy</title>
    <link rel="stylesheet" href="App/assets/css/global.css">
    <link rel="stylesheet" href="App/assets/css/workout.css">
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <div class="logo">
                <img src="App/uploads/logo.png" alt="BodyBuddy Logo" class="logo-img">
                <h1>BodyBud</h1>
            </div>
            <nav class="nav-links">
                <a href="index.php?page=dashboard">Dashboard</a>
                <a href="index.php?page=workout">Workout</a>
                <a href="index.php?page=food">Makanan</a>
                <a href="index.php?page=profile">Profile</a>
                <a href="index.php?page=consultation">Konsultasi</a>
                <a href="index.php?page=progress">Progress</a>
                <a href="index.php?page=auth&action=logout">Logout</a>
            </nav>
        </div>
    </nav>

    <div class="container">
        <a href="index.php?page=workout" class="btn btn-secondary btn-small">← Kembali</a>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <div class="card" style="margin-top: 1rem;">
            <h2><?php echo $workout['workout_name']; ?></h2>
            <span class="badge badge-success"><?php echo $workout['category_name']; ?></span>
            
            <?php if (!empty($workout['video_url'])): 
                // Extract YouTube ID
                $videoId = '';
                if (preg_match('/youtube\.com\/watch\?v=([^&]+)/', $workout['video_url'], $matches)) {
                    $videoId = $matches[1];
                } elseif (preg_match('/youtu\.be\/([^?]+)/', $workout['video_url'], $matches)) {
                    $videoId = $matches[1];
                } elseif (preg_match('/youtube\.com\/embed\/([^?]+)/', $workout['video_url'], $matches)) {
                    $videoId = $matches[1];
                } else {
                    $videoId = $workout['video_url'];
                }
            ?>
            <div class="video-container" style="margin: 2rem 0;">
                <h3 style="margin-bottom: 1rem;">Video Tutorial</h3>
                <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 10px;">
                    <iframe 
                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none;"
                        src="https://www.youtube.com/embed/<?php echo $videoId; ?>" 
                        title="<?php echo $workout['workout_name']; ?> Tutorial"
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                    </iframe>
                </div>
            </div>
            <?php endif; ?>
            
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin: 2rem 0;">
                <div style="background: #fff3cd; padding: 1.5rem; border-radius: 10px; text-align: center; border: 2px solid #ffc107;">
                    <h4 style="margin: 0 0 0.5rem 0; color: #856404;">Repetisi</h4>
                    <h3 style="margin: 0; color: #856404; font-size: 2rem;"><?php echo $workout['repetitions']; ?>x</h3>
                </div>
                <div style="background: #d1ecf1; padding: 1.5rem; border-radius: 10px; text-align: center; border: 2px solid #17a2b8;">
                    <h4 style="margin: 0 0 0.5rem 0; color: #0c5460;">Durasi</h4>
                    <h3 style="margin: 0; color: #0c5460; font-size: 2rem;"><?php echo $workout['duration_minutes']; ?> menit</h3>
                </div>
                <div style="background: #d4edda; padding: 1.5rem; border-radius: 10px; text-align: center; border: 2px solid #28a745;">
                    <h4 style="margin: 0 0 0.5rem 0; color: #155724;">Kalori per Set</h4>
                    <h3 style="margin: 0; color: #155724; font-size: 2rem;"><?php echo $workout['calories_burned']; ?> kal</h3>
                </div>
            </div>

            <div style="background-color: var(--light-color); padding: 1.5rem; border-radius: 8px; margin: 1rem 0;">
                <h3>Deskripsi</h3>
                <p><?php echo nl2br($workout['description']); ?></p>
            </div>

            <div style="background-color: #e8f5e9; padding: 1.5rem; border-radius: 8px; margin: 1rem 0;">
                <h3>Timer Workout</h3>
                <div style="text-align: center; margin: 1rem 0;">
                    <div id="timer-display" style="font-size: 3rem; font-weight: bold; color: var(--primary-color);">
                        0:00
                    </div>
                    <div style="margin-top: 1rem;">
                        <button onclick="startWorkoutTimer(<?php echo $workout['duration_minutes']; ?>)" class="btn btn-primary">
                            Mulai Timer
                        </button>
                        <button onclick="stopWorkoutTimer()" class="btn btn-secondary">
                            Stop
                        </button>
                    </div>
                </div>
            </div>

            <?php if ($_SESSION['role'] === 'member'): ?>
            <form action="index.php?page=workout&action=complete" method="POST" style="margin-top: 2rem;">
                <input type="hidden" name="workout_id" value="<?php echo $workout['id']; ?>">
                
                <div class="form-group">
                    <label for="sets_completed">Jumlah Set yang Diselesaikan</label>
                    <input type="number" id="sets_completed" name="sets_completed" min="1" value="1" required 
                           onchange="document.getElementById('total-calories').textContent = this.value * <?php echo $workout['calories_burned']; ?>">
                    <small>Total kalori yang akan terbakar: 
                        <span id="total-calories"><?php echo $workout['calories_burned']; ?></span> kal
                    </small>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem; font-size: 1.1rem;">
                    ✓ Tandai Selesai
                </button>
            </form>
            <?php endif; ?>
        </div>
    </div>

    <script src="app/assets/js/main.js"></script>
    
</body>
</html>