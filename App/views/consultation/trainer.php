<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Konsultasi - BodyBuddy</title>
    <link rel="stylesheet" href="App/assets/css/global.css">
    <link rel="stylesheet" href="App/assets/css/consultation.css">
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
        <h2>Kelola Booking Konsultasi</h2>
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

        <!-- Statistics Dashboard -->
        <div class="stats-grid">
            <div class="stat-card" style="background: linear-gradient(135deg, #ff9800, #f57c00);">
                <div class="stat-icon">⏳</div>
                <div class="stat-info">
                    <h3><?php echo count(array_filter($bookings, function($b) { return $b['payment_status'] === 'pending'; })); ?></h3>
                    <p>Verifikasi Pembayaran</p>
                </div>
            </div>
            
            <div class="stat-card" style="background: linear-gradient(135deg, #4CAF50, #388E3C);">
                <div class="stat-icon">✓</div>
                <div class="stat-info">
                    <h3><?php echo count(array_filter($bookings, function($b) { return $b['status'] === 'confirmed' && $b['payment_status'] === 'paid'; })); ?></h3>
                    <p>Konsultasi Aktif</p>
                </div>
            </div>
            
            <div class="stat-card" style="background: linear-gradient(135deg, #2196F3, #1976D2);">
                <div class="stat-icon">✓</div>
                <div class="stat-info">
                    <h3><?php echo count(array_filter($bookings, function($b) { return $b['status'] === 'completed'; })); ?></h3>
                    <p>Selesai</p>
                </div>
            </div>
            
            <div class="stat-card" style="background: linear-gradient(135deg, #9C27B0, #7B1FA2);">
                <div class="stat-icon">💰</div>
                <div class="stat-info">
                    <h3>Rp <?php echo number_format(array_sum(array_map(function($b) { return $b['payment_status'] === 'paid' ? $b['payment_amount'] : 0; }, $bookings)), 0, ',', '.'); ?></h3>
                    <p>Total Pendapatan</p>
                </div>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="filter-tabs">
            <button class="tab-btn active" onclick="filterBookings('all')">
                Semua (<?php echo count($bookings); ?>)
            </button>
            <button class="tab-btn" onclick="filterBookings('unpaid')">
                ⚠️ Belum Bayar (<?php echo count(array_filter($bookings, function($b) { return $b['payment_status'] === 'unpaid'; })); ?>)
            </button>
            <button class="tab-btn" onclick="filterBookings('pending_payment')">
                ⏳ Verifikasi (<?php echo count(array_filter($bookings, function($b) { return $b['payment_status'] === 'pending'; })); ?>)
            </button>
            <button class="tab-btn" onclick="filterBookings('paid')">
                ✓ Lunas (<?php echo count(array_filter($bookings, function($b) { return $b['payment_status'] === 'paid'; })); ?>)
            </button>
            <button class="tab-btn" onclick="filterBookings('completed')">
                ✓ Selesai (<?php echo count(array_filter($bookings, function($b) { return $b['status'] === 'completed'; })); ?>)
            </button>
        </div>

        <!-- Booking List -->
        <div class="bookings-container">
            <?php if (count($bookings) > 0): ?>
                <?php foreach ($bookings as $booking): ?>
                    <div class="booking-card" data-status="<?php echo $booking['status']; ?>" data-payment="<?php echo $booking['payment_status']; ?>">
                        <!-- Booking Header -->
                        <div class="booking-header">
                            <div class="member-info">
                                <img src="<?php echo $booking['member_photo'] ?: 'App/uploads/default-avatar.png'; ?>" 
                                     alt="<?php echo $booking['member_name']; ?>" class="member-avatar">
                                <div>
                                    <h4><?php echo $booking['member_name']; ?></h4>
                                    <p class="booking-topic"><?php echo $booking['topic']; ?></p>
                                </div>
                            </div>
                            
                            <div class="booking-status-badges">
                                <!-- Booking Status -->
                                <?php if ($booking['status'] === 'pending'): ?>
                                    <span class="badge badge-warning">⏳ Menunggu Konfirmasi</span>
                                <?php elseif ($booking['status'] === 'confirmed'): ?>
                                    <span class="badge badge-success">✓ Dikonfirmasi</span>
                                <?php elseif ($booking['status'] === 'completed'): ?>
                                    <span class="badge badge-info">✓ Selesai</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">✕ Dibatalkan</span>
                                <?php endif; ?>

                                <!-- Payment Status -->
                                <?php if ($booking['payment_status'] === 'unpaid'): ?>
                                    <span class="badge badge-danger">💳 Belum Bayar</span>
                                <?php elseif ($booking['payment_status'] === 'pending'): ?>
                                    <span class="badge badge-warning">⏳ Verifikasi Pembayaran</span>
                                <?php elseif ($booking['payment_status'] === 'paid'): ?>
                                    <span class="badge badge-success">✓ Lunas</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Booking Details -->
                        <div class="booking-body">
                            <div class="booking-meta">
                                <div class="meta-item">
                                    <span class="icon">📅</span>
                                    <div>
                                        <small>Tanggal Booking</small>
                                        <strong><?php echo date('d M Y, H:i', strtotime($booking['created_at'])); ?></strong>
                                    </div>
                                </div>
                                
                                <?php if ($booking['preferred_time']): ?>
                                <div class="meta-item">
                                    <span class="icon">⏰</span>
                                    <div>
                                        <small>Waktu yang Diinginkan</small>
                                        <strong><?php echo date('d M Y, H:i', strtotime($booking['preferred_time'])); ?></strong>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <div class="meta-item">
                                    <span class="icon">💰</span>
                                    <div>
                                        <small>Biaya Konsultasi</small>
                                        <strong>Rp <?php echo number_format($booking['payment_amount'], 0, ',', '.'); ?></strong>
                                    </div>
                                </div>

                                <div class="meta-item">
                                    <span class="icon">📱</span>
                                    <div>
                                        <small>Kontak Member</small>
                                        <strong><?php echo $booking['member_phone']; ?></strong>
                                    </div>
                                </div>
                            </div>

                            <div class="booking-message">
                                <strong>💬 Pesan dari Member:</strong>
                                <p><?php echo nl2br(htmlspecialchars($booking['message'])); ?></p>
                            </div>

                            <!-- Member Info Box -->
                            <div class="member-detail-box">
                                <div class="member-stat">
                                    <span class="label">Tinggi Badan:</span>
                                    <span class="value"><?php echo $booking['member_height'] ?: 'N/A'; ?> cm</span>
                                </div>
                                <div class="member-stat">
                                    <span class="label">Berat Badan:</span>
                                    <span class="value"><?php echo $booking['member_weight'] ?: 'N/A'; ?> kg</span>
                                </div>
                                <div class="member-stat">
                                    <span class="label">Target:</span>
                                    <span class="value"><?php echo $booking['member_goal'] ?: 'N/A'; ?></span>
                                </div>
                                <div class="member-stat">
                                    <span class="label">Level:</span>
                                    <span class="value"><?php echo $booking['member_level'] ?: 'N/A'; ?></span>
                                </div>
                            </div>

                            <!-- Payment Proof Section -->
                            <?php if ($booking['payment_status'] === 'pending' && $booking['payment_proof']): ?>
                                <div class="payment-proof-section">
                                    <h4>💳 Bukti Pembayaran</h4>
                                    <div class="payment-proof-container">
                                        <img src="<?php echo $booking['payment_proof']; ?>" 
                                             alt="Bukti Pembayaran" 
                                             class="payment-proof-image"
                                             onclick="window.open(this.src, '_blank')">
                                        <div class="payment-proof-info">
                                            <p><strong>Metode:</strong> <?php echo $booking['payment_method']; ?></p>
                                            <p><strong>Upload:</strong> <?php echo date('d M Y, H:i', strtotime($booking['payment_date'])); ?></p>
                                            <small>Klik gambar untuk memperbesar</small>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($booking['trainer_notes']): ?>
                                <div class="trainer-notes-display">
                                    <strong>📝 Catatan Anda:</strong>
                                    <p><?php echo nl2br(htmlspecialchars($booking['trainer_notes'])); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Booking Actions -->
                        <div class="booking-actions">
                            <?php if ($booking['payment_status'] === 'pending'): ?>
                                <!-- Verifikasi Pembayaran -->
                                <div class="payment-verification-box">
                                    <h4>✅ Verifikasi Pembayaran</h4>
                                    <p>Member telah mengupload bukti pembayaran. Silakan verifikasi dan konfirmasi booking.</p>
                                    
                                    <form action="index.php?page=consultation&action=verify_payment" method="POST" class="action-form">
                                        <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                        
                                        <div class="form-group">
                                            <label for="notes_<?php echo $booking['id']; ?>">Catatan untuk Member:</label>
                                            <textarea id="notes_<?php echo $booking['id']; ?>" name="trainer_notes" 
                                                      placeholder="Berikan informasi jadwal, persiapan, atau instruksi untuk member..."
                                                      required></textarea>
                                        </div>

                                        <div class="action-buttons">
                                            <button type="submit" name="action_type" value="approve" class="btn btn-success">
                                                ✓ Terima & Konfirmasi
                                            </button>
                                            <button type="button" class="btn btn-danger" onclick="rejectPayment(<?php echo $booking['id']; ?>)">
                                                ✕ Tolak Pembayaran
                                            </button>
                                        </div>
                                    </form>
                                </div>

                            <?php elseif ($booking['payment_status'] === 'paid' && $booking['status'] === 'confirmed'): ?>
                                <!-- WhatsApp Contact & Complete -->
                                <div class="confirmed-actions">
                                    <a href="https://wa.me/<?php echo $booking['member_whatsapp']; ?>?text=Halo%20<?php echo urlencode($booking['member_name']); ?>,%20saya%20<?php echo urlencode($_SESSION['user_name']); ?>%20dari%20BodyBuddy" 
                                       class="btn btn-whatsapp" target="_blank">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                        </svg>
                                        Hubungi Member
                                    </a>
                                    
                                    <form action="index.php?page=consultation&action=complete" method="POST" style="display: inline;">
                                        <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                        <button type="submit" class="btn btn-info" onclick="return confirm('Tandai konsultasi ini sebagai selesai?')">
                                            ✓ Tandai Selesai
                                        </button>
                                    </form>
                                </div>

                            <?php elseif ($booking['status'] === 'completed'): ?>
                                <div class="completed-badge">
                                    <span class="icon">✓</span>
                                    <span>Konsultasi telah selesai - Rp <?php echo number_format($booking['payment_amount'], 0, ',', '.'); ?></span>
                                </div>

                            <?php elseif ($booking['payment_status'] === 'unpaid'): ?>
                                <div class="waiting-payment-badge">
                                    <span class="icon">⚠️</span>
                                    <span>Menunggu pembayaran dari member</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">📭</div>
                    <h3>Belum Ada Booking</h3>
                    <p>Anda belum memiliki booking konsultasi dari member</p>
                </div>
            <?php endif; ?>
        </div>

    </div>

    <!-- Reject Payment Modal -->
    <div id="rejectPaymentModal" class="modal">
        <div class="modal-content modal-small">
            <span class="modal-close" onclick="closeRejectPaymentModal()">&times;</span>
            
            <div class="modal-header">
                <h3>❌ Tolak Pembayaran</h3>
            </div>

            <div class="modal-body">
                <form id="rejectPaymentForm" action="index.php?page=consultation&action=verify_payment" method="POST">
                    <input type="hidden" id="rejectPaymentBookingId" name="booking_id">
                    <input type="hidden" name="action_type" value="reject">
                    
                    <div class="form-group">
                        <label for="reject_reason">Alasan Penolakan:</label>
                        <textarea id="reject_reason" name="trainer_notes" required
                                  placeholder="Jelaskan alasan penolakan pembayaran kepada member..."></textarea>
                    </div>

                    <div class="modal-actions">
                        <button type="button" class="btn btn-secondary" onclick="closeRejectPaymentModal()">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-danger">
                            Tolak Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Filter bookings by status
        function filterBookings(status) {
            const cards = document.querySelectorAll('.booking-card');
            const tabs = document.querySelectorAll('.tab-btn');
            
            // Update active tab
            tabs.forEach(tab => tab.classList.remove('active'));
            event.target.classList.add('active');
            
            // Filter cards
            cards.forEach(card => {
                const bookingStatus = card.dataset.status;
                const paymentStatus = card.dataset.payment;
                
                let show = false;
                
                if (status === 'all') {
                    show = true;
                } else if (status === 'unpaid') {
                    show = paymentStatus === 'unpaid';
                } else if (status === 'pending_payment') {
                    show = paymentStatus === 'pending';
                } else if (status === 'paid') {
                    show = paymentStatus === 'paid';
                } else if (status === 'completed') {
                    show = bookingStatus === 'completed';
                }
                
                card.style.display = show ? 'block' : 'none';
            });
        }

        // Reject payment modal
        function rejectPayment(bookingId) {
            document.getElementById('rejectPaymentBookingId').value = bookingId;
            document.getElementById('rejectPaymentModal').style.display = 'flex';
        }

        function closeRejectPaymentModal() {
            document.getElementById('rejectPaymentModal').style.display = 'none';
            document.getElementById('rejectPaymentForm').reset();
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('rejectPaymentModal');
            if (event.target === modal) {
                closeRejectPaymentModal();
            }
        }
    </script>
</body>
</html>