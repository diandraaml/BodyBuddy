<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Saya - BodyBuddy</title>
    <link rel="stylesheet" href="App/assets/css/global.css">
    <link rel="stylesheet" href="App/assets/css/profile.css">
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
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                ✓ <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                ✕ <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Profile Card -->
        <div class="profile-card-wrapper" id="profileView">
            <div class="profile-card">
                <!-- Profile Avatar -->
                <div class="profile-avatar-wrapper">
                    <div class="profile-avatar">
                        <img src="<?php echo $user['photo'] ?: 'App/uploads/default-avatar.png'; ?>" alt="Profile">
                    </div>
                </div>

                <!-- Profile Name -->
                <h2 class="profile-name"><?php echo $user['full_name']; ?></h2>
                <p class="profile-email"><?php echo $user['email']; ?></p>

                <!-- Section Headers -->
                <div class="section-headers">
                    <div class="section-header-left">
                        <span class="section-title">Informasi Akun</span>
                    </div>
                    <div class="section-header-right">
                        <span class="section-title">Status Kesehatan</span>
                    </div>
                </div>

                <!-- Profile Content - 2 Columns -->
                <div class="profile-content">
                    <!-- Left Column - Biodata -->
                    <div class="profile-column">
                        <div class="profile-info-item">
                            <span class="info-label">Nama Lengkap</span>
                            <span class="info-value"><?php echo $user['full_name']; ?></span>
                        </div>

                        <div class="profile-info-item">
                            <span class="info-label">Username</span>
                            <span class="info-value"><?php echo $user['username']; ?></span>
                        </div>

                        <div class="profile-info-item">
                            <span class="info-label">Email</span>
                            <span class="info-value"><?php echo $user['email']; ?></span>
                        </div>

                        <div class="profile-info-item">
                            <span class="info-label">Role</span>
                            <span class="info-value"><?php echo ucfirst($user['role']); ?></span>
                        </div>
                    </div>

                    <!-- Right Column - Status BMI -->
                    <div class="profile-column">
                        <div class="profile-info-item">
                            <span class="info-label">Tinggi Badan</span>
                            <span class="info-value-highlight"><?php echo $user['height'] ?: '170.00'; ?> <small>cm</small></span>
                        </div>

                        <div class="profile-info-item">
                            <span class="info-label">Berat Badan</span>
                            <span class="info-value-highlight"><?php echo $user['weight'] ?: '55.00'; ?> <small>kg</small></span>
                        </div>

                        <?php if ($user['height'] && $user['weight']): ?>
                        <div class="profile-info-item">
                            <span class="info-label">BMI</span>
                            <span class="info-value-highlight">
                                <?php 
                                $bmi = $user['weight'] / (($user['height'] / 100) ** 2);
                                echo number_format($bmi, 2);
                                ?>
                            </span>
                        </div>

                        <div class="profile-info-item">
                            <span class="info-label">Kategori</span>
                            <span class="category-badge <?php 
                                if ($bmi < 18.5) echo 'underweight';
                                elseif ($bmi < 25) echo 'normal';
                                elseif ($bmi < 30) echo 'overweight';
                                else echo 'obese';
                            ?>">
                                <?php 
                                if ($bmi < 18.5) echo 'Kekurangan Berat Badan';
                                elseif ($bmi < 25) echo 'Ideal (Normal)';
                                elseif ($bmi < 30) echo 'Kelebihan Berat Badan';
                                else echo 'Obesitas';
                                ?>
                            </span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="profile-actions">
                    <button class="btn-edit-profile" onclick="showEditMode()">
                        Edit Profile
                    </button>
                </div>
            </div>
        </div>

        <!-- Edit Profile Card -->
        <div class="profile-card-wrapper hidden" id="profileEdit">
            <div class="profile-card edit-mode">
                <!-- Profile Avatar in Edit Mode -->
                <div class="profile-avatar-wrapper">
                    <div class="profile-avatar edit-avatar">
                        <img src="<?php echo $user['photo'] ?: 'App/uploads/default-avatar.png'; ?>" alt="Profile">
                        <div class="edit-icon">✏️</div>
                    </div>
                </div>

                <form id="editProfileForm" action="index.php?page=profile&action=update" method="POST">
                    
                    <!-- Section Headers -->
                    <div class="section-headers">
                        <div class="section-header-left">
                            <span class="section-title">Biodata</span>
                        </div>
                        <div class="section-header-right">
                            <span class="section-title">Status BMI</span>
                        </div>
                    </div>

                    <!-- Two Column Form -->
                    <div class="edit-form-columns">
                        <!-- Left Column - Biodata -->
                        <div class="edit-column">
                            <div class="edit-form-group">
                                <label>Nama Lengkap</label>
                                <input type="text" name="full_name" value="<?php echo $user['full_name']; ?>" required>
                            </div>

                            <div class="edit-form-group">
                                <label>Email</label>
                                <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
                            </div>

                            <div class="edit-form-group">
                                <label>Jenis Kelamin</label>
                                <select name="gender">
                                    <option value="">Pilih</option>
                                    <option value="Laki-laki" <?php echo ($user['gender'] ?? '') == 'Laki-laki' ? 'selected' : ''; ?>>Laki-laki</option>
                                    <option value="Perempuan" <?php echo ($user['gender'] ?? '') == 'Perempuan' ? 'selected' : ''; ?>>Perempuan</option>
                                </select>
                            </div>

                            <div class="edit-form-group">
                                <label>No. Telepon</label>
                                <input type="text" name="phone" value="<?php echo $user['phone'] ?? ''; ?>">
                            </div>

                            <div class="edit-form-group">
                                <label>Tanggal Lahir</label>
                                <input type="text" name="date_of_birth" value="<?php echo $user['date_of_birth'] ?? ''; ?>" placeholder="dd/mm/yy">
                            </div>

                            <div class="edit-form-group">
                                <label>Alamat</label>
                                <input type="text" name="address" value="<?php echo $user['address'] ?? ''; ?>" placeholder="Alamat lengkap">
                            </div>
                        </div>

                        <!-- Right Column - Status BMI -->
                        <div class="edit-column">
                            <div class="edit-form-group">
                                <label>Tinggi Badan</label>
                                <div class="input-with-unit">
                                    <input type="number" id="height" name="height" 
                                           value="<?php echo $user['height']; ?>"
                                           step="0.01" min="100" max="250">
                                    <span class="unit">CM</span>
                                </div>
                            </div>

                            <div class="edit-form-group">
                                <label>Berat Badan</label>
                                <div class="input-with-unit">
                                    <input type="number" id="weight" name="weight" 
                                           value="<?php echo $user['weight']; ?>"
                                           step="0.01" min="30" max="200">
                                    <span class="unit">KG</span>
                                </div>
                            </div>

                            <div class="edit-form-group">
                                <label>Target Berat Badan</label>
                                <div class="input-with-unit">
                                    <input type="number" name="target_weight" 
                                           value="<?php echo $user['target_weight'] ?? ''; ?>"
                                           step="0.01" min="30" max="200">
                                    <span class="unit">KG</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-save-changes">Simpan Perubahan</button>
                </form>
            </div>
        </div>

        <!-- BMI Category Info -->
        <div class="bmi-category-card">
            <h3 class="bmi-category-title">Kategori BMI</h3>
            <div class="bmi-category-list">
                <div class="bmi-category-item">
                    <span class="bmi-dot yellow"></span>
                    <span class="bmi-range">&lt; 18.5</span>
                    <span class="bmi-desc">: Kekurangan Berat Badan</span>
                </div>
                <div class="bmi-category-item">
                    <span class="bmi-dot green"></span>
                    <span class="bmi-range">18.5 - 24.9</span>
                    <span class="bmi-desc">: Ideal (normal)</span>
                </div>
                <div class="bmi-category-item">
                    <span class="bmi-dot orange"></span>
                    <span class="bmi-range">25.0 - 29.9</span>
                    <span class="bmi-desc">: Kelebihan Berat Badan</span>
                </div>
                <div class="bmi-category-item">
                    <span class="bmi-dot red"></span>
                    <span class="bmi-range">30.0 &gt;</span>
                    <span class="bmi-desc">: Obesitas</span>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script>
        // Toggle between view and edit mode
        function showEditMode() {
            document.getElementById('profileView').classList.add('hidden');
            document.getElementById('profileEdit').classList.remove('hidden');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function showViewMode() {
            document.getElementById('profileEdit').classList.add('hidden');
            document.getElementById('profileView').classList.remove('hidden');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    </script>
</body>
</html>