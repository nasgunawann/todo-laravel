<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar - Sistem Manajemen Tugas</title>
    
    <!-- bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- tabler icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.23.0/dist/tabler-icons.min.css">
    
    <!-- google fonts: inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --font-sans: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            --color-black: #111111;
            --color-gray-500: #737373;
            --color-border: #e5e5e5;
        }

        body {
            font-family: var(--font-sans);
            background-color: #f5f5f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .auth-wrapper {
            background: #fff;
            width: 100%;
            max-width: 1000px;
            min-height: 600px;
            border-radius: 24px;
            box-shadow: 0 20px 40px -10px rgba(0,0,0,0.1);
            overflow: hidden;
            display: flex;
        }

        /* Left Side - Register Form */
        .login-section {
            flex: 1;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        /* Right Side - Team Members */
        .team-section {
            flex: 1;
            background: var(--color-black);
            color: white;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Decorative Elements in Team Section */
        .team-section::before {
            content: "";
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
        }
        
        .team-section::after {
            content: "";
            position: absolute;
            bottom: -50px;
            left: -50px;
            width: 300px;
            height: 300px;
            background: rgba(255,255,255,0.03);
            border-radius: 50%;
        }

        .brand-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            background: var(--color-black);
            color: #fff;
            border-radius: 12px;
            margin-bottom: 2rem;
            font-size: 1.5rem;
        }

        .welcome-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--color-black);
            margin-bottom: 0.5rem;
            letter-spacing: -0.03em;
            line-height: 1.2;
        }

        .welcome-subtitle {
            color: var(--color-gray-500);
            margin-bottom: 2.5rem;
        }

        .form-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: #404040;
            margin-bottom: 0.5rem;
        }

        .form-control {
            padding: 0.75rem 1rem;
            font-size: 0.9375rem;
            border-color: var(--color-border);
            border-radius: 8px;
            height: 48px;
        }

        .form-control:focus {
            border-color: var(--color-black);
            box-shadow: 0 0 0 4px rgba(0,0,0,0.05);
        }

        .btn-black {
            background: var(--color-black);
            color: white;
            padding: 0 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            height: 48px;
            width: 100%;
            font-size: 0.9375rem;
            transition: transform 0.1s;
        }

        .btn-black:hover {
            opacity: 0.9;
        }

        /* Team List Styling */
        .team-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 2rem;
            position: relative;
            z-index: 1;
        }

        .team-list {
            list-style: none;
            padding: 0;
            margin: 0;
            position: relative;
            z-index: 1;
        }

        .team-member {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.25rem;
            padding: 1rem;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            backdrop-filter: blur(10px);
            transition: transform 0.2s;
        }

        .team-member:hover {
            background: rgba(255,255,255,0.15);
            transform: translateX(5px);
        }

        .member-avatar {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .member-info h6 {
            margin: 0;
            font-weight: 600;
            font-size: 0.9375rem;
        }

        .member-info p {
            margin: 0;
            font-size: 0.8125rem;
            color: rgba(255,255,255,0.6);
        }

        @media (max-width: 768px) {
            .auth-wrapper {
                flex-direction: column;
                max-width: 500px;
            }
            .team-section {
                display: none; /* Hide team section on mobile */
            }
        }
    </style>
</head>
<body>
    <div class="auth-wrapper">
        <!-- Left Side: Register Form -->
        <div class="login-section">
            <div class="d-flex align-items-center gap-2 mb-4">
                <div class="d-flex align-items-center justify-content-center rounded-2" style="width: 36px; height: 36px; background: var(--color-black);">
                    <i class="ti ti-checkbox text-white fs-5"></i>
                </div>
                <span class="fw-bold text-dark" style="font-size: 1.125rem; letter-spacing: -0.01em;">Sistem Manajemen Tugas</span>
            </div>
            
            <h1 class="welcome-title">Buat Akun Baru</h1>
            <p class="welcome-subtitle">Lengkapi data diri untuk mendaftar.</p>

            <form method="POST" action="{{ route('register') }}">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                           name="nama" value="{{ old('nama') }}" required autofocus>
                    @error('nama')
                        <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control @error('kata_sandi') is-invalid @enderror" 
                           name="kata_sandi" required>
                    @error('kata_sandi')
                        <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Minimal 6 karakter</small>
                </div>
                
                <div class="mb-4">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" class="form-control" 
                           name="kata_sandi_confirmation" required>
                </div>
                
                <button type="submit" class="btn btn-black mb-3">
                    Daftar Sekarang
                </button>

                <p class="text-secondary mb-0" style="font-size: 0.875rem;">
                    Sudah punya akun? <a href="{{ route('login') }}" class="text-dark fw-bold text-decoration-none">Masuk</a>
                </p>
            </form>
        </div>

        <!-- Right Side: Team Members -->
        <div class="team-section">
            <h3 class="team-title">Tugas Kelompok Matakuliah <br>Pemrograman Berbasis Web Lanjutan</h3>
            <ul class="team-list">
                <!-- Member 1 -->
                <li class="team-member">
                    <div class="member-avatar">NG</div>
                    <div class="member-info">
                        <h6>Nasrullah Gunawan</h6>
                    </div>
                </li>
                <!-- Member 2 -->
                <li class="team-member">
                    <div class="member-avatar">MS</div>
                    <div class="member-info">
                        <h6>Maharani Br. Saragih</h6>
                    </div>
                </li>
                <!-- Member 3 -->
                <li class="team-member">
                    <div class="member-avatar">TH</div>
                    <div class="member-info">
                        <h6>Tiofandy Hasibuan</h6>
                    </div>
                </li>
                <!-- Member 4 -->
                <li class="team-member">
                    <div class="member-avatar">DA</div>
                    <div class="member-info">
                        <h6>Dzakwan Abbas</h6>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
