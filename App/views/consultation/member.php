<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konsultasi - BodyBuddy</title>
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
        <h2>Konsultasi dengan Trainer Profesional</h2>
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                ✓ <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <!-- Status Booking Saya -->
        <?php if (count($myBookings) > 0): ?>
        <div class="card booking-status-card">
            <h3>Status Booking Konsultasi Saya</h3>
            
            <?php foreach ($myBookings as $booking): ?>
                <div class="booking-status-item status-<?php echo $booking['status']; ?>">
                    <div class="booking-status-header">
                        <div class="trainer-mini-info">
                            <img src="<?php echo $booking['trainer_photo'] ?: 'App/uploads/trainer.png'; ?>" 
                                 alt="<?php echo $booking['trainer_name']; ?>">
                            <div>
                                <strong><?php echo $booking['trainer_name']; ?></strong>
                                <small><?php echo $booking['specialization'] ?: 'Personal Trainer'; ?></small>
                            </div>
                        </div>
                        <div class="booking-status-badge">
                            <?php if ($booking['status'] === 'pending'): ?>
                                <span class="badge badge-warning">Menunggu Konfirmasi</span>
                            <?php elseif ($booking['status'] === 'confirmed'): ?>
                                <span class="badge badge-success">✓ Dikonfirmasi</span>
                            <?php elseif ($booking['status'] === 'completed'): ?>
                                <span class="badge badge-info">✓ Selesai</span>
                            <?php else: ?>
                                <span class="badge badge-danger">✕ Dibatalkan</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="booking-details">
                        <div class="booking-detail-item">
                            <span class="icon"></span>
                            <div>
                                <small>Tanggal Booking</small>
                                <strong><?php echo date('d M Y, H:i', strtotime($booking['created_at'])); ?></strong>
                            </div>
                        </div>
                        
                        <div class="booking-detail-item">
                            <span class="icon"></span>
                            <div>
                                <small>Topik Konsultasi</small>
                                <strong><?php echo htmlspecialchars($booking['topic']); ?></strong>
                            </div>
                        </div>

                        <div class="booking-detail-item">
                            <span class="icon"></span>
                            <div>
                                <small>Biaya Konsultasi</small>
                                <strong>Rp <?php echo number_format($booking['payment_amount'], 0, ',', '.'); ?></strong>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Status -->
                    <div class="payment-status-box">
                        <?php if ($booking['payment_status'] === 'unpaid'): ?>
                            <div class="payment-unpaid">
                                <span class="payment-icon"></span>
                                <div>
                                    <strong>Menunggu Pembayaran</strong>
                                    <p>Silakan upload bukti pembayaran untuk melanjutkan konsultasi</p>
                                </div>
                                <button class="btn btn-primary btn-small" onclick="openPaymentModal(<?php echo htmlspecialchars(json_encode($booking)); ?>)">
                                    Bayar Sekarang
                                </button>
                            </div>
                        <?php elseif ($booking['payment_status'] === 'pending'): ?>
                            <div class="payment-pending">
                                <span class="payment-icon"></span>
                                <div>
                                    <strong>Pembayaran Sedang Diverifikasi</strong>
                                    <p>Bukti pembayaran Anda sedang diverifikasi oleh trainer</p>
                                </div>
                            </div>
                        <?php elseif ($booking['payment_status'] === 'paid'): ?>
                            <div class="payment-success">
                                <span class="payment-icon">✓</span>
                                <div>
                                    <strong>Pembayaran Berhasil</strong>
                                    <p>Dibayar pada: <?php echo date('d M Y, H:i', strtotime($booking['payment_date'])); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if ($booking['trainer_notes']): ?>
                        <div class="trainer-response">
                            <strong>Pesan dari Trainer:</strong>
                            <p><?php echo nl2br(htmlspecialchars($booking['trainer_notes'])); ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if ($booking['status'] === 'confirmed' && $booking['payment_status'] === 'paid'): ?>
                        <div class="whatsapp-contact">
                            <a href="https://wa.me/085161630235<?php echo $booking['trainer_whatsapp']; ?>?text=Halo%20<?php echo urlencode($booking['trainer_name']); ?>,%20saya%20ingin%20konsultasi" 
                               class="btn btn-whatsapp" target="_blank">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                </svg>
                                Hubungi via WhatsApp
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Daftar Trainer -->
        <div class="trainers-grid">
            <?php foreach ($trainers as $trainer): ?>
                <div class="trainer-card">
                    <div class="trainer-photo">
                        <img src="App/uploads/trainer.png" alt="<?php echo $trainer['full_name']; ?>">
                        <div class="trainer-status">
                            <span class="status-badge">✓ Tersedia</span>
                        </div>
                        <div class="trainer-price-badge">
                            <span>Rp <?php echo number_format($trainer['consultation_price'], 0, ',', '.'); ?></span>
                        </div>
                    </div>
                    
                    <div class="trainer-info">
                        <h3><?php echo $trainer['full_name']; ?></h3>
                        <p class="trainer-specialization"><?php echo $trainer['specialization']; ?></p>
                        
                        <div class="trainer-stats">
                            <div class="stat-item">
                                <span class="icon"></span>
                                <span><?php echo $trainer['rating']; ?>/5.0</span>
                            </div>
                            <div class="stat-item">
                                <span class="icon"></span>
                                <span><?php echo $trainer['total_clients']; ?> Klien</span>
                            </div>
                            <div class="stat-item">
                                <span class="icon"></span>
                                <span><?php echo $trainer['experience']; ?> Tahun</span>
                            </div>
                        </div>

                        <div class="trainer-expertise">
                            <strong>Keahlian:</strong>
                            <div class="expertise-tags">
                                <?php 
                                $expertises = explode(',', $trainer['expertise']);
                                foreach ($expertises as $expertise): 
                                ?>
                                    <span class="tag"><?php echo trim($expertise); ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="trainer-bio">
                            <p><?php echo $trainer['bio']; ?></p>
                        </div>

                        <button class="btn btn-primary btn-booking" onclick="openBookingModal(<?php echo htmlspecialchars(json_encode($trainer)); ?>)">
                            Booking Konsultasi - Rp <?php echo number_format($trainer['consultation_price'], 0, ',', '.'); ?>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Modal Booking -->
    <div id="bookingModal" class="modal">
        <div class="modal-content">
            <span class="modal-close" onclick="closeBookingModal()">&times;</span>
            
            <div class="modal-header">
                <h3>Booking Konsultasi</h3>
            </div>

            <div class="modal-body">
                <div class="selected-trainer-info">
                    <img id="modalTrainerPhoto" src="" alt="">
                    <div>
                        <h4 id="modalTrainerName"></h4>
                        <p id="modalTrainerSpec"></p>
                        <p class="modal-price">Biaya: <strong id="modalTrainerPrice"></strong></p>
                    </div>
                </div>

                <form id="bookingForm" action="index.php?page=consultation&action=book" method="POST">
                    <input type="hidden" id="bookingTrainerId" name="trainer_id">
                    
                    <div class="form-group">
                        <label for="topic">Topik Konsultasi *</label>
                        <select id="topic" name="topic" required>
                            <option value="">-- Pilih Topik --</option>
                            <option value="Program Workout">Program Workout</option>
                            <option value="Nutrisi & Diet">Nutrisi & Diet</option>
                            <option value="Penurunan Berat Badan">Penurunan Berat Badan</option>
                            <option value="Pembentukan Otot">Pembentukan Otot</option>
                            <option value="Pemulihan Cedera">Pemulihan Cedera</option>
                            <option value="Konsultasi Umum">Konsultasi Umum</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="message">Pesan / Pertanyaan *</label>
                        <textarea id="message" name="message" required 
                                  placeholder="Jelaskan kondisi, target, atau pertanyaan Anda..."></textarea>
                        <small>Jelaskan kondisi kesehatan, target fitness, dan pertanyaan spesifik Anda</small>
                    </div>

                    <div class="form-group">
                        <label for="preferred_time">Waktu Konsultasi yang Diinginkan</label>
                        <input type="datetime-local" id="preferred_time" name="preferred_time">
                        <small>Opsional - Trainer akan menghubungi untuk konfirmasi jadwal</small>
                    </div>

                    <div class="payment-info-box">
                        <h4>Informasi Pembayaran</h4>
                        <p>Setelah booking, Anda akan diminta untuk melakukan pembayaran dan upload bukti transfer.</p>
                    </div>

                    <div class="modal-actions">
                        <button type="button" class="btn btn-secondary" onclick="closeBookingModal()">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Lanjutkan Booking
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Payment -->
    <div id="paymentModal" class="modal">
        <div class="modal-content">
            <span class="modal-close" onclick="closePaymentModal()">&times;</span>
            
            <div class="modal-header">
                <h3>Pembayaran Konsultasi</h3>
            </div>

            <div class="modal-body">
                <div class="payment-summary">
                    <h4>Detail Pembayaran</h4>
                    <div class="payment-detail">
                        <span>Trainer:</span>
                        <strong id="paymentTrainerName"></strong>
                    </div>
                    <div class="payment-detail">
                        <span>Topik:</span>
                        <strong id="paymentTopic"></strong>
                    </div>
                    <div class="payment-detail total">
                        <span>Total Pembayaran:</span>
                        <strong id="paymentAmount" class="price-large"></strong>
                    </div>
                </div>

                <div id="paymentMethods" class="payment-methods">
                    <!-- Will be filled by JavaScript -->
                </div>

                <form id="paymentForm" action="index.php?page=consultation&action=upload_payment" method="POST" enctype="multipart/form-data">
                    <input type="hidden" id="paymentBookingId" name="booking_id">
                    
                    <div class="form-group">
                        <label for="payment_method">Metode Pembayaran *</label>
                        <select id="payment_method" name="payment_method" required>
                            <option value="">-- Pilih Metode Pembayaran --</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="payment_proof">Upload Bukti Pembayaran *</label>
                        <input type="file" id="payment_proof" name="payment_proof" accept="image/*" required>
                        <small>Format: JPG, PNG. Maksimal 2MB</small>
                    </div>

                    <div class="modal-actions">
                        <button type="button" class="btn btn-secondary" onclick="closePaymentModal()">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-success">
                            ✓ Kirim Bukti Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openBookingModal(trainer) {
            const modal = document.getElementById('bookingModal');
            document.getElementById('modalTrainerPhoto').src = 'App/uploads/trainer.png';
            document.getElementById('modalTrainerName').textContent = trainer.full_name;
            document.getElementById('modalTrainerSpec').textContent = trainer.specialization;
            document.getElementById('modalTrainerPrice').textContent = 'Rp ' + Number(trainer.consultation_price).toLocaleString('id-ID');
            document.getElementById('bookingTrainerId').value = trainer.id;
            modal.style.display = 'flex';
        }

        function closeBookingModal() {
            const modal = document.getElementById('bookingModal');
            modal.style.display = 'none';
            document.getElementById('bookingForm').reset();
        }

        function openPaymentModal(booking) {
            const modal = document.getElementById('paymentModal');
            document.getElementById('paymentTrainerName').textContent = booking.trainer_name;
            document.getElementById('paymentTopic').textContent = booking.topic;
            document.getElementById('paymentAmount').textContent = 'Rp ' + Number(booking.payment_amount).toLocaleString('id-ID');
            document.getElementById('paymentBookingId').value = booking.id;
            
            // Fetch payment methods via AJAX
            fetchPaymentMethods(booking.trainer_id);
            
            modal.style.display = 'flex';
        }

        function closePaymentModal() {
            const modal = document.getElementById('paymentModal');
            modal.style.display = 'none';
            document.getElementById('paymentForm').reset();
        }

        function fetchPaymentMethods(trainerId) {
            fetch(`index.php?page=consultation&action=get_payment_methods&trainer_id=${trainerId}`)
                .then(response => response.json())
                .then(data => {
                    const methodsDiv = document.getElementById('paymentMethods');
                    const selectElement = document.getElementById('payment_method');
                    
                    methodsDiv.innerHTML = '<h4>Metode Pembayaran Tersedia:</h4>';
                    selectElement.innerHTML = '<option value="">-- Pilih Metode Pembayaran --</option>';
                    
                    data.forEach(method => {
                        // Display payment info
                        const methodCard = document.createElement('div');
                        methodCard.className = 'payment-method-card';
                        methodCard.innerHTML = `
                            <div class="method-icon">${method.method_type === 'bank_transfer' ? '🏦' : '💳'}</div>
                            <div class="method-info">
                                <strong>${method.bank_name || method.method_type}</strong>
                                <p>${method.account_name}</p>
                                <p class="account-number">${method.account_number}</p>
                            </div>
                        `;
                        methodsDiv.appendChild(methodCard);
                        
                        // Add to select
                        const option = document.createElement('option');
                        option.value = method.id;
                        option.textContent = `${method.bank_name || method.method_type} - ${method.account_number}`;
                        selectElement.appendChild(option);
                    });
                });
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const bookingModal = document.getElementById('bookingModal');
            const paymentModal = document.getElementById('paymentModal');
            
            if (event.target === bookingModal) {
                closeBookingModal();
            }
            if (event.target === paymentModal) {
                closePaymentModal();
            }
        }
    </script>
</body>
</html>