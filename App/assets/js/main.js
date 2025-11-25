// assets/js/main.js

// BMI Calculator
function calculateBMI(weight, height) {
    const heightInMeters = height / 100;
    const bmi = weight / (heightInMeters * heightInMeters);
    return bmi.toFixed(2);
}

function getBMICategory(bmi) {
    if (bmi < 18.5) return 'Kekurangan Berat Badan';
    if (bmi >= 18.5 && bmi < 25) return 'Ideal';
    if (bmi >= 25 && bmi < 30) return 'Kelebihan Berat Badan';
    return 'Obesitas';
}

// Update BMI display
function updateBMIDisplay() {
    const weightInput = document.getElementById('weight');
    const heightInput = document.getElementById('height');
    const bmiDisplay = document.getElementById('bmi-display');
    const categoryDisplay = document.getElementById('category-display');
    
    if (weightInput && heightInput && bmiDisplay) {
        weightInput.addEventListener('input', calculateAndDisplay);
        heightInput.addEventListener('input', calculateAndDisplay);
        
        function calculateAndDisplay() {
            const weight = parseFloat(weightInput.value);
            const height = parseFloat(heightInput.value);
            
            if (weight && height && weight > 0 && height > 0) {
                const bmi = calculateBMI(weight, height);
                const category = getBMICategory(bmi);
                
                bmiDisplay.textContent = 'BMI: ' + bmi;
                if (categoryDisplay) {
                    categoryDisplay.textContent = 'Kategori: ' + category;
                    
                    // Update badge color
                    categoryDisplay.className = 'badge';
                    if (category === 'Ideal') {
                        categoryDisplay.classList.add('badge-success');
                    } else if (category === 'Kelebihan Berat Badan') {
                        categoryDisplay.classList.add('badge-warning');
                    } else {
                        categoryDisplay.classList.add('badge-danger');
                    }
                }
            }
        }
    }
}

// Calorie calculator for food
function calculateFoodCalories(baseCalories, quantity) {
    return baseCalories * quantity;
}

// Update food calorie display
function updateFoodCalorieDisplay() {
    const quantityInputs = document.querySelectorAll('.food-quantity');
    
    quantityInputs.forEach(input => {
        input.addEventListener('input', function() {
            const baseCalories = parseFloat(this.dataset.calories);
            const quantity = parseFloat(this.value) || 1;
            const totalCalories = calculateFoodCalories(baseCalories, quantity);
            
            const displayElement = this.parentElement.querySelector('.total-calories');
            if (displayElement) {
                displayElement.textContent = totalCalories + ' kal';
            }
        });
    });
}

// Form validation
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return;
    
    form.addEventListener('submit', function(e) {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.style.borderColor = 'red';
            } else {
                field.style.borderColor = '';
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Mohon lengkapi semua field yang wajib diisi!');
        }
    });
}

// Confirmation dialog
function confirmAction(message) {
    return confirm(message);
}

// Delete confirmation
document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.btn-delete');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirmAction('Apakah Anda yakin ingin menghapus data ini?')) {
                e.preventDefault();
            }
        });
    });
    
    // Initialize BMI calculator
    updateBMIDisplay();
    
    // Initialize food calorie calculator
    updateFoodCalorieDisplay();
});

// Auto-hide alerts
setTimeout(function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        alert.style.transition = 'opacity 0.5s';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
    });
}, 5000);

// Progress chart (if chart.js is available)
function createProgressChart(canvasId, data) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;
    
    const ctx = canvas.getContext('2d');
    
    // Simple line chart implementation
    const width = canvas.width;
    const height = canvas.height;
    const padding = 40;
    
    ctx.clearRect(0, 0, width, height);
    
    // Draw axes
    ctx.beginPath();
    ctx.moveTo(padding, padding);
    ctx.lineTo(padding, height - padding);
    ctx.lineTo(width - padding, height - padding);
    ctx.strokeStyle = '#333';
    ctx.stroke();
    
    // Draw data points
    if (data && data.length > 0) {
        const maxValue = Math.max(...data.map(d => d.value));
        const minValue = Math.min(...data.map(d => d.value));
        const range = maxValue - minValue || 1;
        
        ctx.beginPath();
        data.forEach((point, index) => {
            const x = padding + (index / (data.length - 1)) * (width - 2 * padding);
            const y = height - padding - ((point.value - minValue) / range) * (height - 2 * padding);
            
            if (index === 0) {
                ctx.moveTo(x, y);
            } else {
                ctx.lineTo(x, y);
            }
            
            // Draw point
            ctx.fillStyle = '#4CAF50';
            ctx.beginPath();
            ctx.arc(x, y, 4, 0, 2 * Math.PI);
            ctx.fill();
        });
        
        ctx.strokeStyle = '#4CAF50';
        ctx.lineWidth = 2;
        ctx.stroke();
    }
}

// Workout timer
let timerInterval;
function startWorkoutTimer(duration) {
    let timeLeft = duration * 60; // Convert to seconds
    const display = document.getElementById('timer-display');
    
    if (!display) return;
    
    clearInterval(timerInterval);
    
    timerInterval = setInterval(function() {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        
        display.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
        
        if (timeLeft <= 0) {
            clearInterval(timerInterval);
            alert('Workout selesai! Selamat!');
        }
        
        timeLeft--;
    }, 1000);
}

function stopWorkoutTimer() {
    clearInterval(timerInterval);
    const display = document.getElementById('timer-display');
    if (display) {
        display.textContent = '0:00';
    }
}

        // Update total calories when sets change
        document.getElementById('sets_completed').addEventListener('input', function() {
            const sets = parseInt(this.value) || 1;
            const caloriesPerSet = $workout['calories_burned'];
            const total = sets * caloriesPerSet;
            document.getElementById('total-calories').textContent = total;
        });
