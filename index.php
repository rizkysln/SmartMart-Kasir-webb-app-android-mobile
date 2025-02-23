<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartMart - Login Kasir</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <ul class="circles">
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
    </ul>
    
    <div class="main-wrapper">
        <!-- Tambahkan class login-container -->
        <div class="container login-container">
            <div class="left-panel">
                <i class="fas fa-store store-icon"></i>
                <h1>Selamat Datang di SmartMart</h1>
                <p>Sistem kasir pintar untuk manajemen toko yang lebih efisien. Silakan masuk untuk memulai aktivitas Anda.</p>
            </div>
            <div class="login-form">
                <div class="login-header">
                    <h2>Login Kasir</h2>
                    <p>Masukkan kredensial Anda untuk melanjutkan</p>
                </div>
                <div class="input-group">
                    <input type="text" id="username" placeholder="Nama Pengguna" autocomplete="off">
                    <i class="fas fa-user"></i>
                </div>
                <div class="input-group">
                    <input type="password" id="password" placeholder="Kata Sandi">
                    <i class="fas fa-lock"></i>
                </div>
                <button class="login-btn" onclick="checkLogin()">
                    <i class="fas fa-sign-in-alt"></i> Masuk
                </button>
                
            </div>
        </div>
    </div>

    <script>
        function checkLogin() {
            const username = document.getElementById("username").value;
            const password = document.getElementById("password").value;
            
            if (username === "puskom" && password === "puskom") {
                // Tambahkan animasi sebelum redirect
                document.querySelector('.container').style.animation = 'containerAppear 1s ease-out reverse';
                setTimeout(() => {
                    window.location.href = "dashboard.php";
                }, 1000);
            } else {
                // Animasi getar untuk input yang salah
                const inputs = document.querySelectorAll('input');
                inputs.forEach(input => {
                    input.style.animation = 'shake 0.5s ease-in-out';
                    setTimeout(() => {
                        input.style.animation = '';
                    }, 500);
                });
                alert("Username atau password salah!");
            }
        }

        // Support untuk tombol Enter
        document.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                checkLogin();
            }
        });

        // Animasi tambahan untuk input saat fokus
        const inputs = document.querySelectorAll('input');
        inputs.forEach(input => {
            input.addEventListener('focus', () => {
                input.parentElement.style.transform = 'scale(1.05)';
            });
            input.addEventListener('blur', () => {
                input.parentElement.style.transform = 'scale(1)';
            });
        });

        // Tambahkan animasi shake
        if (!document.querySelector('@keyframes shake')) {
            const style = document.createElement('style');
            style.textContent = `
                @keyframes shake {
                    0%, 100% { transform: translateX(0); }
                    25% { transform: translateX(-10px); }
                    75% { transform: translateX(10px); }
                }
            `;
            document.head.appendChild(style);
        }
    </script>
</body>
</html>