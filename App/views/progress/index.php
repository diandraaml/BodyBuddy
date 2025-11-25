<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progress - BodyBuddy</title>
    <link rel="stylesheet" href="App/assets/css/global.css">
    <link rel="stylesheet" href="App/assets/css/progress.css">
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
        <h2>Tracking Progress Saya</h2>

        <?php if ($user['target_weight']): ?>
        <!-- Progress ke Target -->
        <div class="card" style="background: #9BA34A; color: white;">
            
            <?php if ($user['weight']): ?>
                <?php 
                    $currentWeight = $user['weight'];
                    $targetWeight = $user['target_weight'];
                    
                    // Tentukan apakah target naik atau turun
                    if ($targetWeight < $currentWeight) {
                        // Target turun berat badan
                        $totalToLose = $currentWeight - $targetWeight;
                        $remaining = $currentWeight - $targetWeight;
                        $progress = 0;
                        
                        // Cek progress dari data history
                        if (count($progressData) > 0) {
                            $firstWeight = end($progressData)['weight'];
                            $lost = $firstWeight - $currentWeight;
                            $progress = ($lost / $totalToLose) * 100;
                        }
                    } else {
                        // Target naik berat badan
                        $totalToGain = $targetWeight - $currentWeight;
                        $remaining = $targetWeight - $currentWeight;
                        $progress = 0;
                        
                        if (count($progressData) > 0) {
                            $firstWeight = end($progressData)['weight'];
                            $gained = $currentWeight - $firstWeight;
                            $progress = ($gained / $totalToGain) * 100;
                        }
                    }
                    
                    $progress = max(0, min(100, $progress));
                ?>
                
                <div style="display: flex; justify-content: space-around; margin: 2rem 0; text-align: center;">
                    <div>
                        <h2 style="color: white; margin: 0;"><?php echo $currentWeight; ?> kg</h2>
                        <p style="opacity: 0.9;">Berat Saat Ini</p>
                    </div>
                    <div style="font-size: 2rem;">→</div>
                    <div>
                        <h2 style="color: white; margin: 0;"><?php echo $targetWeight; ?> kg</h2>
                        <p style="opacity: 0.9;">Target</p>
                    </div>
                </div>

                <div class="progress-bar" style="background-color: rgba(255,255,255,0.3);">
                    <div class="progress-fill" style="width: <?php echo $progress; ?>%; background: rgba(255,255,255,0.8); color: #333;">
                        <?php echo round($progress); ?>%
                    </div>
                </div>

                <p style="text-align: center; margin-top: 1rem; font-size: 1.2rem;">
                    <?php 
                        if ($remaining > 0) {
                            echo abs($remaining) . ' kg lagi menuju target!';
                        } else {
                            echo '🎉 Target tercapai! Pertahankan!';
                        }
                    ?>
                </p>
            <?php else: ?>
                <p>Update data berat badan Anda di menu Profile untuk melihat progress.</p>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Current Status -->
        <div class="card stats-horizontal-card">
            <div class="stats-horizontal">
                <div class="stat-item">
                    <h3><?php echo $user['weight'] ?? '-'; ?> kg</h3>
                    <p>Berat Badan Terkini</p>
                </div>
                <div class="stat-divider"></div>
                <div class="stat-item">
                    <h3>
                        <?php 
                            if ($user['height'] && $user['weight']) {
                                $heightInMeters = $user['height'] / 100;
                                $bmi = $user['weight'] / ($heightInMeters * $heightInMeters);
                                echo number_format($bmi, 1);
                            } else {
                                echo '-';
                            }
                        ?>
                    </h3>
                    <p>BMI Saat Ini</p>
                </div>
                <div class="stat-divider"></div>
                <div class="stat-item">
                    <h3>
                        <?php 
                            if ($user['height'] && $user['weight']) {
                                $heightInMeters = $user['height'] / 100;
                                $bmi = $user['weight'] / ($heightInMeters * $heightInMeters);
                                if ($bmi < 18.5) {
                                    echo 'Kekurangan';
                                } elseif ($bmi < 25) {
                                    echo 'Ideal';
                                } elseif ($bmi < 30) {
                                    echo 'Kelebihan';
                                } else {
                                    echo 'Obesitas';
                                }
                            } else {
                                echo '-';
                            }
                        ?>
                    </h3>
                    <p>Kategori</p>
                </div>
                <div class="stat-divider"></div>
                <div class="stat-item">
                    <h3><?php echo count($progressData); ?></h3>
                    <p>Total Record</p>
                </div>
            </div>
        </div>

        <!-- Grafik Progress -->
        <?php if (count($progressData) > 0): ?>
        <div class="card">
            <h2>Grafik Perkembangan Berat Badan</h2>
            <canvas id="progressChart" width="800" height="300" style="max-width: 100%;"></canvas>
        </div>
        <?php endif; ?>

        <!-- Tabel History -->
        <div class="card">
            <h2>Riwayat Pengukuran</h2>
            
            <?php if (count($progressData) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Berat Badan</th>
                            <th>BMI</th>
                            <th>Perubahan</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $prevWeight = null;
                            foreach ($progressData as $progress): 
                                $change = 0;
                                $changeClass = '';
                                $changeIcon = '';
                                
                                if ($prevWeight !== null) {
                                    $change = $progress['weight'] - $prevWeight;
                                    if ($change > 0) {
                                        $changeClass = 'color: #f44336;';
                                        $changeIcon = '↑';
                                    } elseif ($change < 0) {
                                        $changeClass = 'color: #4CAF50;';
                                        $changeIcon = '↓';
                                    } else {
                                        $changeClass = 'color: #757575;';
                                        $changeIcon = '→';
                                    }
                                }
                                
                                $prevWeight = $progress['weight'];
                        ?>
                            <tr>
                                <td><?php echo date('d M Y', strtotime($progress['recorded_date'])); ?></td>
                                <td><strong><?php echo $progress['weight']; ?> kg</strong></td>
                                <td><?php echo number_format($progress['bmi'], 2); ?></td>
                                <td style="<?php echo $changeClass; ?>">
                                    <?php 
                                        if ($change != 0) {
                                            echo $changeIcon . ' ' . number_format(abs($change), 1) . ' kg';
                                        } else {
                                            echo '-';
                                        }
                                    ?>
                                </td>
                                <td><?php echo $progress['notes'] ? $progress['notes'] : '-'; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="text-align: center; color: #666; padding: 2rem;">
                    Belum ada data progress. Update berat badan Anda di menu Profile untuk mulai tracking!
                </p>
            <?php endif; ?>
        </div>

        <!-- Call to Action -->
        <div style="text-align: center; margin: 2rem 0;">
            <a href="index.php?page=profile" class="btn btn-primary">Update Berat Badan</a>
            <a href="index.php?page=workout" class="btn btn-secondary">Mulai Workout</a>
        </div>
    </div>

    <script src="app/assets/js/main.js"></script>
    <script>
        <?php if (count($progressData) > 0): ?>
        // Prepare data untuk chart
        const progressData = <?php echo json_encode(array_reverse($progressData)); ?>;
        const chartData = progressData.map(item => ({
            date: item.recorded_date,
            value: parseFloat(item.weight)
        }));

        // Render chart
        createProgressChart('progressChart', chartData);
        <?php endif; ?>
    </script>
</body>
</html>