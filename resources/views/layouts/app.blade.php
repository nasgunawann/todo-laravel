<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Todo App</title>
    
    <!-- optimize CDN loading -->
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://code.jquery.com" crossorigin>
    
    <!-- bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- preload tabler icons font -->
    <link rel="preload" 
        href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.23.0/dist/fonts/tabler-icons.woff2" 
        as="font" 
        type="font/woff2" 
        crossorigin>
    
    <!-- tabler icons -->
    <link rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.23.0/dist/tabler-icons.min.css" />
    
    <!-- sweetalert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <!-- custom css -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <style>
        /* sidebar layout - notion inspired */
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #ffffff;
        }
        
        .app-container {
            display: flex;
            min-height: 100vh;
        }
        
        /* sidebar */
        .sidebar {
            width: 240px;
            background: #f7f7f7;
            border-right: 1px solid #e5e5e5;
            padding: 1.5rem 0 0 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }
        
        .sidebar-brand {
            padding: 0 1.5rem;
            margin-bottom: 2rem;
        }
        
        .sidebar-brand h5 {
            font-weight: 700;
            color: #000;
            margin: 0;
            font-size: 1.125rem;
        }
        
        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .nav-item {
            margin-bottom: 0.25rem;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1.5rem;
            color: #737373;
            text-decoration: none;
            transition: all 0.15s;
            font-size: 0.9375rem;
        }
        
        .nav-link:hover {
            background: #ececec;
            color: #000;
        }
        
        .nav-link.active {
            background: #e5e5e5;
            color: #000;
            font-weight: 500;
        }
        
        .nav-link i {
            font-size: 1.125rem;
        }
        
        .sidebar-menu {
            flex: 1;
            overflow-y: auto;
        }
        
        .sidebar-divider {
            margin: 1rem 1.5rem;
            border-top: 1px solid #e5e5e5;
        }
        
        .sidebar-section {
            padding: 0.5rem 1.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            color: #a3a3a3;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        /* main content */
        .main-content {
            margin-left: 240px;
            flex: 1;
            padding: 2rem;
            max-width: 100%;
        }
        
        /* user menu */
        .user-menu {
            margin-top: auto;
            padding: 1rem 1.5rem;
            border-top: 1px solid #e5e5e5;
            flex-shrink: 0;
        }
        
        .user-menu-trigger {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: background 0.15s;
        }
        
        .user-menu-trigger:hover {
            background: #ececec;
        }
        
        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #000;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
            flex-shrink: 0;
        }
        
        .user-info {
            flex: 1;
        }
        
        .user-name {
            font-size: 0.875rem;
            font-weight: 500;
            color: #000;
            margin: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 140px;
        }
        
        /* responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
                z-index: 1000;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
        }
        
        /* alerts */
        .alert {
            border-radius: 0.5rem;
            border: 1px solid;
            margin-bottom: 1.5rem;
        }
        
        .alert-success {
            background: #f0fdf4;
            border-color: #d1fae5;
            color: #065f46;
        }
        
        .alert-danger {
            background: #fef2f2;
            border-color: #fecaca;
            color: #991b1b;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="app-container">
        <!-- sidebar -->
        <aside class="sidebar">
            <div class="sidebar-brand">
                <h5><i class="ti ti-checkbox"></i> Todo App</h5>
            </div>
            
            <div class="sidebar-menu">
                <ul class="sidebar-nav">
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="ti ti-home"></i>
                            <span>Beranda</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('todo.index') }}" class="nav-link {{ request()->routeIs('todo.*') ? 'active' : '' }}">
                            <i class="ti ti-list-check"></i>
                            <span>Semua Tugas</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('kategori.index') }}" class="nav-link {{ request()->routeIs('kategori.*') ? 'active' : '' }}">
                            <i class="ti ti-category"></i>
                            <span>Kategori</span>
                        </a>
                    </li>
                </ul>
            </div>
            

            
            <div class="user-menu">
                <div class="user-menu-trigger" data-bs-toggle="dropdown">
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
                    </div>
                    <div class="user-info">
                        <p class="user-name">{{ Auth::user()->nama }}</p>
                    </div>
                </div>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="{{ route('profil.edit') }}">
                            <i class="ti ti-user"></i> Profil
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" id="logout-form">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="ti ti-logout"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </aside>
        
        <!-- main content -->
        <main class="main-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="ti ti-check"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="ti ti-alert-circle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @yield('content')
        </main>
    </div>
    
    <!-- bootstrap js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jquery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- sweetalert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- custom js -->
    <script src="{{ asset('js/app.js') }}"></script>
    
    @stack('scripts')
</body>
</html>
