<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Workout - BodyBuddy</title>
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
        
        <div class="card" style="max-width: 700px; margin: 2rem auto;">
            <h2>Buat Workout Baru</h2>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <form action="index.php?page=workout&action=store" method="POST">
                <div class="form-group">
                    <label for="workout_name">Nama Workout *</label>
                    <input type="text" id="workout_name" name="workout_name" required placeholder="Contoh: Push-ups">
                </div>

                <div class="form-group">
                    <label for="category_id">Kategori *</label>
                    <select id="category_id" name="category_id" required>
                        <option value="">-- Pilih Kategori --</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>"><?php echo $cat['category_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi *</label>
                    <textarea id="description" name="description" required placeholder="Jelaskan cara melakukan workout ini..."></textarea>
                </div>

                <div class="form-group">
                    <label for="video_url">Link Video YouTube *</label>
                    <input type="url" id="video_url" name="video_url" required 
                           placeholder="https://www.youtube.com/watch?v=XXXXXXX atau https://youtu.be/XXXXXXX">
                    <small>Masukkan link video tutorial dari YouTube. Format yang didukung:
                        <ul style="margin: 0.5rem 0; padding-left: 1.5rem; font-size: 0.85rem;">
                            <li>https://www.youtube.com/watch?v=VIDEO_ID</li>
                            <li>https://youtu.be/VIDEO_ID</li>
                            <li>https://www.youtube.com/embed/VIDEO_ID</li>
                        </ul>
                    </small>
                </div>

                <!-- Video Preview -->
                <div id="video-preview" style="display: none; margin: 1rem 0; padding: 1rem; background: #f0f0f0; border-radius: 8px;">
                    <h4>Preview Video:</h4>
                    <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 8px; margin-top: 0.5rem;">
                        <iframe id="preview-iframe"
                            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none;"
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>

                <div class="form-group">
                    <label for="repetitions">Jumlah Repetisi *</label>
                    <input type="number" id="repetitions" name="repetitions" required min="1" placeholder="Contoh: 15">
                    <small>Jumlah pengulangan per set</small>
                </div>

                <div class="form-group">
                    <label for="duration_minutes">Durasi (Menit) *</label>
                    <input type="number" id="duration_minutes" name="duration_minutes" required min="1" placeholder="Contoh: 10">
                    <small>Estimasi waktu untuk menyelesaikan workout</small>
                </div>

                <div class="form-group">
                    <label for="calories_burned">Kalori yang Dibakar (per set) *</label>
                    <input type="number" id="calories_burned" name="calories_burned" required min="1" placeholder="Contoh: 50">
                    <small>Estimasi kalori yang terbakar per set</small>
                </div>

                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">Simpan Workout</button>
                    <a href="index.php?page=workout" class="btn btn-secondary" style="flex: 1; text-align: center; line-height: 2.5;">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
    <script>
        // Video preview functionality
        document.getElementById('video_url').addEventListener('input', function() {
            const url = this.value;
            const preview = document.getElementById('video-preview');
            const iframe = document.getElementById('preview-iframe');
            
            // Extract video ID from different YouTube URL formats
            let videoId = '';
            
            const patterns = [
                /youtube\.com\/watch\?v=([^&]+)/,
                /youtu\.be\/([^?]+)/,
                /youtube\.com\/embed\/([^?]+)/
            ];
            
            for (let pattern of patterns) {
                const match = url.match(pattern);
                if (match) {
                    videoId = match[1];
                    break;
                }
            }
            
            if (videoId) {
                iframe.src = `https://www.youtube.com/embed/${videoId}`;
                preview.style.display = 'block';
            } else {
                preview.style.display = 'none';
            }
        });
    </script>
</body>
</html>