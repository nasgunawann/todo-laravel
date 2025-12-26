<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Todo App</title>
    
    <!-- bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- tabler icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.23.0/dist/tabler-icons.min.css">
    
    <style>
        /* black & white minimalist theme */
        :root {
            --color-black: #000000;
            --color-gray-900: #1a1a1a;
            --color-gray-700: #404040;
            --color-gray-300: #d4d4d4;
            --color-gray-100: #f5f5f5;
            --color-white: #ffffff;
        }
        
        body {
            background: var(--color-white);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .auth-container {
            max-width: 400px;
            width: 100%;
            padding: 2rem 1rem;
        }
        
        .auth-card {
            background: var(--color-white);
            border: 1px solid var(--color-gray-300);
            border-radius: 0.5rem;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        
        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .auth-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--color-black);
            margin-bottom: 0.5rem;
        }
        
        .auth-subtitle {
            color: var(--color-gray-700);
            font-size: 0.875rem;
        }
        
        .form-label {
            color: var(--color-gray-900);
            font-weight: 500;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }
        
        .form-control {
            border: 1px solid var(--color-gray-300);
            border-radius: 0.375rem;
            padding: 0.625rem 0.875rem;
            font-size: 0.9375rem;
        }
        
        .form-control:focus {
            border-color: var(--color-black);
            box-shadow: 0 0 0 3px rgba(0,0,0,0.1);
            outline: none;
        }
        
        .btn-primary {
            background: var(--color-black);
            border: 2px solid var(--color-black);
            color: var(--color-white);
            padding: 0.625rem;
            font-weight: 500;
            width: 100%;
            border-radius: 0.375rem;
        }
        
        .btn-primary:hover {
            background: var(--color-gray-900);
            border-color: var(--color-gray-900);
        }
        
        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--color-gray-300);
            color: var(--color-gray-700);
            font-size: 0.875rem;
        }
        
        .auth-footer a {
            color: var(--color-black);
            text-decoration: none;
            font-weight: 500;
        }
        
        .auth-footer a:hover {
            text-decoration: underline;
        }
        
        .form-check-input:checked {
            background-color: var(--color-black);
            border-color: var(--color-black);
        }
        
        .invalid-feedback {
            font-size: 0.8125rem;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1 class="auth-title">Sistem Manajemen Tugas</h1>
                <p class="auth-subtitle">Masuk ke akun Anda</p>
            </div>
            
            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <!-- email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- password -->
                <div class="mb-3">
                    <label for="kata_sandi" class="form-label">Password</label>
                    <input type="password" 
                           class="form-control @error('kata_sandi') is-invalid @enderror" 
                           id="kata_sandi" 
                           name="kata_sandi" 
                           required>
                    @error('kata_sandi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- remember me -->
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="ingat_saya" name="ingat_saya">
                    <label class="form-check-label" for="ingat_saya">Ingat Saya</label>
                </div>
                
                <!-- submit -->
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-login"></i> Masuk
                </button>
            </form>
            
            <div class="auth-footer">
                Belum punya akun? <a href="{{ route('register') }}">Daftar</a>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
