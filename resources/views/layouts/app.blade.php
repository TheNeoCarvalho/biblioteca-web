<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistema de Biblioteca')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Form Enhancements CSS -->
    <link href="{{ asset('css/form-enhancements.css') }}" rel="stylesheet">
    
    <!-- Notifications CSS -->
    <link href="{{ asset('css/notifications.css') }}" rel="stylesheet">
    
    <style>
        .sidebar {
            min-height: calc(100vh - 56px);
            background-color: #f8f9fa;
            border-right: 1px solid #dee2e6;
        }
        
        .sidebar .nav-link {
            color: #495057;
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            margin: 0.125rem 0.5rem;
        }
        
        .sidebar .nav-link:hover {
            background-color: #e9ecef;
            color: #212529;
        }
        
        .sidebar .nav-link.active {
            background-color: #0d6efd;
            color: white;
        }
        
        .sidebar .nav-link i {
            width: 1.25rem;
            margin-right: 0.5rem;
        }
        
        .main-content {
            min-height: calc(100vh - 56px);
        }
        
        .breadcrumb-item + .breadcrumb-item::before {
            content: ">";
        }
        
        @media (max-width: 767.98px) {
            .sidebar {
                min-height: auto;
            }
            
            .offcanvas-sidebar {
                width: 280px;
            }
        }
        
        .navbar-brand {
            font-weight: 600;
        }
        
        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.125);
        }
        
        .btn {
            border-radius: 0.375rem;
        }
        
        .alert {
            border-radius: 0.5rem;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    @auth
    <!-- Top Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
        <div class="container-fluid">
            <!-- Sidebar Toggle (Mobile) -->
            <button class="navbar-toggler d-lg-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="bi bi-book"></i> Sistema de Biblioteca
            </a>
            
            <!-- User Menu -->
            <div class="dropdown">
                <a class="nav-link dropdown-toggle text-white d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle me-2"></i>
                    <span class="d-none d-sm-inline">{{ Auth::user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><h6 class="dropdown-header">{{ Auth::user()->name }}</h6></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="bi bi-box-arrow-right me-2"></i> Sair
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 col-xl-2 p-0">
                <!-- Desktop Sidebar -->
                <nav class="sidebar d-none d-lg-block">
                    <div class="p-3">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                    <i class="bi bi-house"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}" href="{{ route('students.index') }}">
                                    <i class="bi bi-people"></i> Alunos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('books.*') ? 'active' : '' }}" href="{{ route('books.index') }}">
                                    <i class="bi bi-book"></i> Livros
                                </a>
                            </li>
                            <li class="nav-item">
                                <hr class="my-2">
                                <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted text-uppercase">
                                    <span>Empréstimos</span>
                                </h6>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('loans.index') ? 'active' : '' }}" href="{{ route('loans.index') }}">
                                    <i class="bi bi-list"></i> Empréstimos Ativos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('loans.create') ? 'active' : '' }}" href="{{ route('loans.create') }}">
                                    <i class="bi bi-plus-circle"></i> Novo Empréstimo
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('loans.overdue') ? 'active' : '' }}" href="{{ route('loans.overdue') }}">
                                    <i class="bi bi-exclamation-triangle"></i> Em Atraso
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('loans.history') ? 'active' : '' }}" href="{{ route('loans.history') }}">
                                    <i class="bi bi-clock-history"></i> Histórico
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>

                <!-- Mobile Sidebar (Offcanvas) -->
                <div class="offcanvas offcanvas-start offcanvas-sidebar d-lg-none" tabindex="-1" id="sidebar" aria-labelledby="sidebarLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="sidebarLabel">
                            <i class="bi bi-book"></i> Menu
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body p-0">
                        <nav class="sidebar">
                            <div class="p-3">
                                <ul class="nav flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                            <i class="bi bi-house"></i> Dashboard
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}" href="{{ route('students.index') }}">
                                            <i class="bi bi-people"></i> Alunos
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('books.*') ? 'active' : '' }}" href="{{ route('books.index') }}">
                                            <i class="bi bi-book"></i> Livros
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <hr class="my-2">
                                        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted text-uppercase">
                                            <span>Empréstimos</span>
                                        </h6>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('loans.index') ? 'active' : '' }}" href="{{ route('loans.index') }}">
                                            <i class="bi bi-list"></i> Empréstimos Ativos
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('loans.create') ? 'active' : '' }}" href="{{ route('loans.create') }}">
                                            <i class="bi bi-plus-circle"></i> Novo Empréstimo
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('loans.overdue') ? 'active' : '' }}" href="{{ route('loans.overdue') }}">
                                            <i class="bi bi-exclamation-triangle"></i> Em Atraso
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('loans.history') ? 'active' : '' }}" href="{{ route('loans.history') }}">
                                            <i class="bi bi-clock-history"></i> Histórico
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9 col-xl-10">
                <main class="main-content p-4">
                    <!-- Breadcrumbs -->
                    @if(!request()->routeIs('dashboard'))
                    <nav aria-label="breadcrumb" class="mb-4">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}" class="text-decoration-none">
                                    <i class="bi bi-house"></i> Dashboard
                                </a>
                            </li>
                            @yield('breadcrumbs')
                        </ol>
                    </nav>
                    @endif

                    <!-- Flash Messages (Hidden - handled by JavaScript notifications) -->
                    <div style="display: none;">
                        @if(session('success'))
                            <div class="alert alert-success d-none" data-message="{{ session('success') }}" data-type="success"></div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger d-none" data-message="{{ session('error') }}" data-type="error"></div>
                        @endif

                        @if(session('warning'))
                            <div class="alert alert-warning d-none" data-message="{{ session('warning') }}" data-type="warning"></div>
                        @endif

                        @if(session('info'))
                            <div class="alert alert-info d-none" data-message="{{ session('info') }}" data-type="info"></div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger d-none" data-errors="{{ json_encode($errors->all()) }}" data-type="validation"></div>
                        @endif
                    </div>

                    @yield('content')
                </main>
            </div>
        </div>
    </div>
    @else
    <!-- Guest Layout -->
    <main class="container-fluid">
        @yield('content')
    </main>
    @endauth

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Form Validations JS -->
    <script src="{{ asset('js/form-validations.js') }}"></script>
    
    <!-- Notifications JS -->
    <script src="{{ asset('js/notifications.js') }}"></script>
    
    <!-- Flash Messages Integration -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Convert Laravel flash messages to notifications
            @if(session('success'))
                window.notifications.success('{{ session('success') }}');
            @endif
            
            @if(session('error'))
                window.notifications.error('{{ session('error') }}');
            @endif
            
            @if(session('warning'))
                window.notifications.warning('{{ session('warning') }}');
            @endif
            
            @if(session('info'))
                window.notifications.info('{{ session('info') }}');
            @endif
            
            @if($errors->any())
                window.notifications.validationError(@json($errors->all()));
            @endif
        });
    </script>
    
    @stack('scripts')
</body>
</html>