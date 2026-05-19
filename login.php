<?php 
session_start(); 
include 'koneksi.php'; 

$error = false; 
$success_register = false; 
$register_error = ""; 
$active_tab = "login"; // Tab aktif bawaan 

// 1. PROSES AUTENTIKASI MASUK (LOGIN) 
if (isset($_POST['login'])) { 
    $active_tab = "login"; 
    $email = mysqli_real_escape_string($conn, $_POST['email']); 
    $password = $_POST['password']; 
    $role = $_POST['role']; 

    $query = "SELECT * FROM users WHERE email = '$email' AND password = '$password' AND role = '$role'"; 
    $result = mysqli_query($conn, $query); 

    if (mysqli_num_rows($result) === 1) { 
        $row = mysqli_fetch_assoc($result); 
        $_SESSION['login'] = true; 
        $_SESSION['id_user'] = $row['id_user']; 
        $_SESSION['nama'] = $row['nama']; 
        $_SESSION['email'] = $row['email']; 
        $_SESSION['role'] = $row['role']; 

        if ($_SESSION['role'] === 'penyelenggara') { 
            echo "<script>alert('Selamat Datang Pengelola, " . $row['nama'] . "!'); window.location.href='manage-campaigns.php';</script>"; 
        } else { 
            echo "<script>alert('Selamat Datang, " . $row['nama'] . "!'); window.location.href='index.php';</script>"; 
        } 
        exit; 
    } else { 
        $error = true; 
    } 
} 

// 2. PROSES PENDAFTARAN AKUN (REGISTER) 
if (isset($_POST['register'])) { 
    $active_tab = "register"; 
    $nama = mysqli_real_escape_string($conn, $_POST['nama_lengkap']); 
    $email = mysqli_real_escape_string($conn, $_POST['email']); 
    $no_telepon = mysqli_real_escape_string($conn, $_POST['no_telepon']); 
    $password = $_POST['password']; 
    $confirm_password = $_POST['confirm_password']; 
    $role = $_POST['role']; 
    
    // Jika mendaftar sebagai penyelenggara, ambil input alamat. Jika donatur, kosongkan.
    $alamat = ($role === 'penyelenggara') ? mysqli_real_escape_string($conn, $_POST['alamat']) : '';

    if ($password !== $confirm_password) { 
        $register_error = "Konfirmasi password tidak sesuai!"; 
    } else { 
        $check_query = "SELECT email FROM users WHERE email = '$email'"; 
        $check_result = mysqli_query($conn, $check_query); 
        if (mysqli_num_rows($check_result) > 0) { 
            $register_error = "Email tersebut sudah terdaftar!"; 
        } else { 
            // PERBAIKAN: Memasukkan kolom alamat ke query INSERT
            $insert_query = "INSERT INTO users (nama, email, no_telepon, alamat, password, role) VALUES ('$nama', '$email', '$no_telepon', '$alamat', '$password', '$role')"; 
            if (mysqli_query($conn, $insert_query)) { 
                $success_register = true; 
                $active_tab = "login"; 
                echo "<script>alert('Registrasi akun sebagai " . ucfirst($role) . " berhasil! Silakan masuk.');</script>"; 
            } else { 
                $register_error = "Gagal melakukan registrasi sistem."; 
            } 
        } 
    } 
} 
?> 
<!DOCTYPE html> 
<html lang="id"> 
<head> 
    <meta charset="UTF-8"> 
    <title>Halaman Kredensial - BantuSesama</title> 
    <link rel="stylesheet" href="style/login.css"> 
    <link rel="stylesheet" href="https://cloudflare.com">
    <style> 
        .msg-alert { 
            font-size: 13px; 
            text-align: center; 
            margin-bottom: 15px; 
            padding: 8px; 
            border-radius: 4px; 
        } 
        .msg-error { 
            background-color: #fde8e8; 
            color: #e53e3e; 
        } 
        .input-group select { 
            width: 100%; 
            background: transparent; 
            border: none; 
            border-bottom: 2px solid white; 
            color: white; 
            padding: 12px 0; 
            font-size: 1.05rem; 
            outline: none; 
            cursor: pointer; 
        } 
        .input-group select option { 
            background-color: #415f86; 
            color: white; 
        } 
        /* Gaya input untuk textarea Alamat */
        .input-group textarea {
            width: 100%;
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 6px;
            color: white;
            padding: 10px;
            font-size: 1rem;
            outline: none;
            resize: none;
            margin-top: 5px;
        }
    </style> 
</head> 
<body> 
    <div class="login-box"> 
        <div class="tab-switch"> 
            <button id="loginBtn" class="tab-button <?php echo ($active_tab === 'login') ? 'active' : ''; ?>">Login</button> 
            <button id="registerBtn" class="tab-button <?php echo ($active_tab === 'register') ? 'active' : ''; ?>">Register</button> 
        </div> 

        <!-- Formulir Log Masuk (Login Form) --> 
        <form id="loginForm" class="form-container <?php echo ($active_tab === 'login') ? 'active' : ''; ?>" action="" method="POST"> 
            <h2>Login</h2> 
            <?php if ($error) : ?> 
                <div class="msg-alert msg-error">Email, Password, atau Peran salah!</div> 
            <?php endif; ?> 
            <div class="input-group"> 
                <label>Email</label> 
                <input type="email" name="email" placeholder="contoh@gmail.com" value="<?php echo isset($_POST['email']) && isset($_POST['login']) ? htmlspecialchars($_POST['email']) : ''; ?>" required> 
            </div> 
            <div class="input-group"> 
                <label>Password</label> 
                <div class="password-wrapper"> 
                    <input id="loginPassword" type="password" name="password" placeholder="••••••••" required> 
                    <button type="button" class="password-toggle" data-target="loginPassword" aria-label="Tampilkan password">
                        <i class="fa-solid fa-eye" style="color: white; font-size: 1.1rem;"></i>
                    </button> 
                </div> 
            </div> 
            <div class="input-group"> 
                <label>Masuk Sebagai</label> 
                <select name="role" required> 
                    <option value="donatur" <?php echo (isset($_POST['role']) && $_POST['role'] === 'donatur' && isset($_POST['login'])) ? 'selected' : ''; ?>>Donatur</option> 
                    <option value="penyelenggara" <?php echo (isset($_POST['role']) && $_POST['role'] === 'penyelenggara' && isset($_POST['login'])) ? 'selected' : ''; ?>>Penyelenggara / Pengelola Kampanye</option> 
                </select> 
            </div> 
            <button type="submit" name="login" class="btn-submit">Masuk</button> 
        </form> 

        <!-- Formulir Pendaftaran (Register Form) --> 
        <form id="registerForm" class="form-container <?php echo ($active_tab === 'register') ? 'active' : ''; ?>" action="" method="POST"> 
            <h2>Register</h2> 
            <?php if (!empty($register_error)) : ?> 
                <div class="msg-alert msg-error"><?php echo $register_error; ?></div> 
            <?php endif; ?> 
            <div class="input-group"> 
                <label>Nama Lengkap</label> 
                <input type="text" name="nama_lengkap" placeholder="Nama lengkap" value="<?php echo isset($_POST['nama_lengkap']) ? htmlspecialchars($_POST['nama_lengkap']) : ''; ?>" required> 
            </div> 
            <div class="input-group"> 
                <label>Email</label> 
                <input type="email" name="email" placeholder="contoh@gmail.com" value="<?php echo isset($_POST['email']) && isset($_POST['register']) ? htmlspecialchars($_POST['email']) : ''; ?>" required> 
            </div> 
            <div class="input-group"> 
                <label>Nomor Telepon</label> 
                <input type="text" name="no_telepon" placeholder="Contoh: 08123456789" value="<?php echo isset($_POST['no_telepon']) ? htmlspecialchars($_POST['no_telepon']) : ''; ?>" required> 
            </div> 
            <div class="input-group"> 
                <label>Password</label> 
                <div class="password-wrapper"> 
                    <input id="registerPassword" type="password" name="password" placeholder="••••••••" required> 
                    <button type="button" class="password-toggle" data-target="registerPassword" aria-label="Tampilkan password">
                        <i class="fa-solid fa-eye" style="color: white; font-size: 1.1rem;"></i>
                    </button> 
                </div> 
            </div> 
            <div class="input-group"> 
                <label>Konfirmasi Password</label> 
                <div class="password-wrapper"> 
                    <input id="registerConfirmPassword" type="password" name="confirm_password" placeholder="Ulangi password" required> 
                    <button type="button" class="password-toggle" data-target="registerConfirmPassword" aria-label="Tampilkan password">
                        <i class="fa-solid fa-eye" style="color: white; font-size: 1.1rem;"></i>
                    </button> 
                </div> 
            </div> 
            <div class="input-group"> 
                <label>Daftar Sebagai</label> 
                <!-- Id roleRegister ditaruh disini untuk dibaca Javascript -->
                <select name="role" id="roleRegister" required> 
                    <option value="donatur" <?php echo (isset($_POST['role']) && $_POST['role'] === 'donatur' && isset($_POST['register'])) ? 'selected' : ''; ?>>Donatur</option> 
                    <option value="penyelenggara" <?php echo (isset($_POST['role']) && $_POST['role'] === 'penyelenggara' && isset($_POST['register'])) ? 'selected' : ''; ?>>Penyelenggara / Pengelola Kampanye</option> 
                </select> 
            </div> 
            
            <!-- TAMBAHAN: Kondisional Input Alamat Khusus Penyelenggara -->
            <div class="input-group" id="alamatGroup" style="display: none;"> 
                <label>Alamat Kantor / Institusi</label> 
                <textarea name="alamat" id="alamatInput" rows="3" placeholder="Masukkan alamat lengkap kantor..."></textarea> 
            </div> 

            <button type="submit" name="register" class="btn-submit">Daftar</button> 
        </form> 
    </div> 

    <script> 
        const loginBtn = document.getElementById('loginBtn'); 
        const registerBtn = document.getElementById('registerBtn'); 
        const loginForm = document.getElementById('loginForm'); 
        const registerForm = document.getElementById('registerForm'); 
        
        // Konstanta baru untuk kontrol field Alamat
        const roleRegister = document.getElementById('roleRegister');
        const alamatGroup = document.getElementById('alamatGroup');
        const alamatInput = document.getElementById('alamatInput');

        loginBtn.addEventListener('click', () => { 
            loginBtn.classList.add('active'); 
            registerBtn.classList.remove('active'); 
            loginForm.classList.add('active'); 
            registerForm.classList.remove('active'); 
        }); 

        registerBtn.addEventListener('click', () => { 
            registerBtn.classList.add('active'); 
            loginBtn.classList.remove('active'); 
            registerForm.classList.add('active'); 
            loginForm.classList.remove('active'); 
        }); 

        // JAVASCRIPT: Memunculkan/menyembunyikan field alamat secara interaktif
        function toggleAlamatField() {
            if (roleRegister.value === 'penyelenggara') {
                alamatGroup.style.display = 'block';
                alamatInput.setAttribute('required', 'required');
            } else {
                alamatGroup.style.display = 'none';
                alamatInput.removeAttribute('required');
                alamatInput.value = ''; // Reset nilai jika pindah ke donatur
            }
        }
        
        // Jalankan fungsi saat drop-down diganti perilakunya
        roleRegister.addEventListener('change', toggleAlamatField);
        // Jalankan saat halaman dimuat pertama kali untuk menjaga state pasca-reload eror
        window.addEventListener('DOMContentLoaded', toggleAlamatField);

        document.querySelectorAll('.password-toggle').forEach(button => { 
            button.addEventListener('click', () => { 
                const targetId = button.getAttribute('data-target'); 
                const input = document.getElementById(targetId); 
                const icon = button.querySelector('i'); 
                if (!input) return; 
                
                if (input.type === 'password') { 
                    input.type = 'text'; 
                    icon.classList.remove('fa-eye'); 
                    icon.classList.add('fa-eye-slash'); 
                } else { 
                    input.type = 'password'; 
                    icon.classList.remove('fa-eye-slash'); 
                    icon.classList.add('fa-eye'); 
                } 
            }); 
        }); 
    </script> 
</body> 
</html>
