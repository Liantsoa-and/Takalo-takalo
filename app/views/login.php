<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Messaging App</title>

    <!-- SEO Meta Tags -->
    <meta name="description"
        content="Advanced form examples with real-time validation, file uploads, and multi-step wizards">
    <meta name="keywords" content="bootstrap, admin, dashboard, forms, validation">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Custom Bootstrap Admin Template CSS -->
    <link rel="stylesheet" href="<?= BASE_PATH ?>/assets/styles/css/bootstrap-admin-complete.css">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="<?= BASE_PATH ?>/assets/icons/favicon.svg">
    <link rel="icon" type="image/png" href="<?= BASE_PATH ?>/assets/icons/favicon.png">

    <!-- PWA Manifest -->
    <link rel="manifest" href="<?= BASE_PATH ?>/manifest.json">

    <!-- Preload critical fonts -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
        as="style">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body data-page="forms" class="admin-layout">
    <!-- Admin App Container -->
    <div class="admin-app">
        <div class="admin-wrapper" id="admin-wrapper">

            <!-- Header -->
            <header class="admin-header">0
                <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
                    <div class="container-fluid">
                        <!-- Logo/Brand -->
                        <a class="navbar-brand d-flex align-items-center" href="<?= BASE_PATH ?>/index.php">
                            <img src="<?= BASE_PATH ?>/assets/images/logo.svg" alt="Logo" height="32"
                                class="d-inline-block align-text-top me-2">
                            <h1 class="h4 mb-0 fw-bold text-primary">Metis</h1>
                        </a>

                        <!-- Search Bar with Alpine.js -->
                        <div class="search-container flex-grow-1 mx-4" x-data="searchComponent">
                            <div class="position-relative">
                                <input type="search" class="form-control" placeholder="Search... (Ctrl+K)"
                                    x-model="query" @input="search()" data-search-input aria-label="Search">
                                <i class="bi bi-search position-absolute top-50 end-0 translate-middle-y me-3"></i>

                                <!-- Search Results Dropdown -->
                                <div x-show="results.length > 0" x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    class="position-absolute top-100 start-0 w-100 bg-white border rounded-2 shadow-lg mt-1 z-3">
                                    <template x-for="result in results" :key="result.title">
                                        <a :href="result.url"
                                            class="d-block px-3 py-2 text-decoration-none text-dark border-bottom">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-file-text me-2 text-muted"></i>
                                                <span x-text="result.title"></span>
                                                <small class="ms-auto text-muted" x-text="result.type"></small>
                                            </div>
                                        </a>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Right Side Icons -->
                        <div class="navbar-nav flex-row">
                            <!-- Theme Toggle with Alpine.js -->
                            <div x-data="themeSwitch">
                                <button class="btn btn-outline-secondary me-2" type="button" @click="toggle()"
                                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="Toggle theme">
                                    <i class="bi bi-sun-fill" x-show="currentTheme === 'light'"></i>
                                    <i class="bi bi-moon-fill" x-show="currentTheme === 'dark'"></i>
                                </button>
                            </div>

                            <!-- Fullscreen Toggle -->
                            <button class="btn btn-outline-secondary me-2" type="button" data-fullscreen-toggle
                                data-bs-toggle="tooltip" data-bs-placement="bottom" title="Toggle fullscreen">
                                <i class="bi bi-arrows-fullscreen icon-hover"></i>
                            </button>

                            <!-- Notifications -->
                            <div class="dropdown me-2">
                                <button class="btn btn-outline-secondary position-relative" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-bell"></i>
                                    <span
                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        3
                                    </span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <h6 class="dropdown-header">Notifications</h6>
                                    </li>
                                    <li><a class="dropdown-item" href="#">New user registered</a></li>
                                    <li><a class="dropdown-item" href="#">Server status update</a></li>
                                    <li><a class="dropdown-item" href="#">New message received</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item text-center" href="#">View all notifications</a></li>
                                </ul>
                            </div>

                            <!-- User Menu -->
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary d-flex align-items-center" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="/assets/images/avatar-placeholder.svg" alt="User Avatar" width="24"
                                        height="24" class="rounded-circle me-2">
                                    <span class="d-none d-md-inline">John Doe</span>
                                    <i class="bi bi-chevron-down ms-1"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile</a>
                                    </li>
                                    <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Settings</a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="#"><i
                                                class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>
            </header>

            <!-- Sidebar -->
            <aside class="admin-sidebar" id="admin-sidebar">
                <div class="sidebar-content">
                    <nav class="sidebar-nav">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="./index.html">
                                    <i class="bi bi-speedometer2"></i>
                                    <span>Dashboard</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="./analytics.html">
                                    <i class="bi bi-graph-up"></i>
                                    <span>Analytics</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="./users.html">
                                    <i class="bi bi-people"></i>
                                    <span>Users</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="./products.html">
                                    <i class="bi bi-box"></i>
                                    <span>Products</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="./orders.html">
                                    <i class="bi bi-bag-check"></i>
                                    <span>Orders</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="./forms.html">
                                    <i class="bi bi-ui-checks"></i>
                                    <span>Forms</span>
                                    <span class="badge bg-success rounded-pill ms-auto">Active</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#elementsSubmenu"
                                    aria-expanded="false">
                                    <i class="bi bi-puzzle"></i>
                                    <span>Elements</span>
                                    <span class="badge bg-primary rounded-pill ms-2 me-2">New</span>
                                    <i class="bi bi-chevron-down ms-auto"></i>
                                </a>
                                <div class="collapse" id="elementsSubmenu">
                                    <ul class="nav nav-submenu">
                                        <li class="nav-item">
                                            <a class="nav-link" href="./elements.html">
                                                <i class="bi bi-grid"></i>
                                                <span>Overview</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="./elements-buttons.html">
                                                <i class="bi bi-square"></i>
                                                <span>Buttons</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="./elements-alerts.html">
                                                <i class="bi bi-exclamation-triangle"></i>
                                                <span>Alerts</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="./elements-badges.html">
                                                <i class="bi bi-award"></i>
                                                <span>Badges</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="./elements-cards.html">
                                                <i class="bi bi-card-text"></i>
                                                <span>Cards</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="./elements-modals.html">
                                                <i class="bi bi-window"></i>
                                                <span>Modals</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="./elements-forms.html">
                                                <i class="bi bi-ui-checks"></i>
                                                <span>Forms</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="./elements-tables.html">
                                                <i class="bi bi-table"></i>
                                                <span>Tables</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="./reports.html">
                                    <i class="bi bi-file-earmark-text"></i>
                                    <span>Reports</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="./messages.html">
                                    <i class="bi bi-chat-dots"></i>
                                    <span>Messages</span>
                                    <span class="badge bg-danger rounded-pill ms-auto">3</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="./calendar.html">
                                    <i class="bi bi-calendar-event"></i>
                                    <span>Calendar</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="./files.html">
                                    <i class="bi bi-folder2-open"></i>
                                    <span>Files</span>
                                </a>
                            </li>
                            <li class="nav-item mt-3">
                                <small class="text-muted px-3 text-uppercase fw-bold">Admin</small>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="./settings.html">
                                    <i class="bi bi-gear"></i>
                                    <span>Settings</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="./security.html">
                                    <i class="bi bi-shield-check"></i>
                                    <span>Security</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="./help.html">
                                    <i class="bi bi-question-circle"></i>
                                    <span>Help & Support</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </aside>

            <!-- Floating Hamburger Menu -->
            <button class="hamburger-menu" type="button" data-sidebar-toggle aria-label="Toggle sidebar">
                <i class="bi bi-list"></i>
            </button>

            <!-- Main Content -->
            <main class="admin-main">
                <div class="container-fluid p-4">

                    <!-- Page Header -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h1 class="h3 mb-0">Login</h1>
                            <p class="text-muted mb-0">Enter your email to login or create a new account automatically
                            </p>
                        </div>
                    </div>

                    <!-- Login Form -->
                    <div class="row g-4 mb-5">
                        <div class="col-lg-6 mx-auto">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="bi bi-box-arrow-in-right me-2 text-primary"></i>
                                        Auto Login
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <form x-data="loginForm" method="post" action="login">
                                        <div class="mb-3">
                                            <label class="form-label">Email Address</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                                <input type="email" name="email" class="form-control"
                                                    x-model="form.email" @input="validateField('email')"
                                                    :class="getFieldClass('email')" placeholder="your@email.com"
                                                    required>
                                            </div>
                                            <div class="invalid-feedback d-block" x-show="errors.email"
                                                x-text="errors.email"></div>
                                            <small class="form-text text-muted">
                                                Enter your email to login or create an account automatically
                                            </small>
                                        </div>
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-primary btn-lg"
                                                :disabled="isSubmitting || !isFormValid">
                                                <span x-show="!isSubmitting">
                                                    <i class="bi bi-box-arrow-in-right me-2"></i>Continue
                                                </span>
                                                <span x-show="isSubmitting">
                                                    <div class="spinner-border spinner-border-sm me-2"></div>
                                                    Processing...
                                                </span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </main>

            <!-- Footer -->
            <footer class="admin-footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-0 text-muted">© 2025 Modern Bootstrap Admin Template</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p class="mb-0 text-muted">Built with Bootstrap 5.3.7</p>
                        </div>
                    </div>
                </div>
            </footer>

        </div> <!-- /.admin-wrapper -->
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Vendor Scripts -->
    <script src="<?= BASE_PATH ?>/scripts/vendor-bootstrap-simple.js"></script>
    <script src="<?= BASE_PATH ?>/scripts/vendor-ui-simple.js"></script>

    <!-- Page-specific Component -->
    <script src="<?= BASE_PATH ?>/scripts/forms-bootstrap-simple.js"></script>

    <!-- Main App Script -->
    <script src="<?= BASE_PATH ?>/scripts/main-bootstrap-simple.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggleButton = document.querySelector('[data-sidebar-toggle]');
            const wrapper = document.getElementById('admin-wrapper');

            if (toggleButton && wrapper) {
                // Set initial state from localStorage
                const isCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
                if (isCollapsed) {
                    wrapper.classList.add('sidebar-collapsed');
                    toggleButton.classList.add('is-active');
                }

                // Attach click listener
                toggleButton.addEventListener('click', () => {
                    const isCurrentlyCollapsed = wrapper.classList.contains('sidebar-collapsed');

                    if (isCurrentlyCollapsed) {
                        wrapper.classList.remove('sidebar-collapsed');
                        toggleButton.classList.remove('is-active');
                        localStorage.setItem('sidebar-collapsed', 'false');
                    } else {
                        wrapper.classList.add('sidebar-collapsed');
                        toggleButton.classList.add('is-active');
                        localStorage.setItem('sidebar-collapsed', 'true');
                    }
                });
            }
        });

        // Alpine.js component for login form
        document.addEventListener('alpine:init', () => {
            Alpine.data('loginForm', () => ({
                form: {
                    email: ''
                },
                errors: {
                    email: ''
                },
                isSubmitting: false,
                isFormValid: false,

                async validateField(field) {
                    // Clear the error for this field
                    this.errors[field] = '';

                    // Basic client-side validation
                    if (field === 'email') {
                        if (!this.form.email) {
                            this.errors.email = "L'email est obligatoire.";
                        } else if (!this.isValidEmail(this.form.email)) {
                            this.errors.email = "L'email n'est pas valide.";
                        }
                    }

                    // Check if form is valid
                    this.updateFormValidity();

                    // If field is valid and not empty, validate with server
                    if (this.form.email && !this.errors.email) {
                        await this.validateWithServer();
                    }
                },

                async validateWithServer() {
                    try {
                        const response = await fetch('<?= BASE_PATH ?>/api/validate/login', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify(this.form)
                        });

                        const result = await response.json();

                        if (!result.ok) {
                            this.errors = { ...this.errors, ...result.errors };
                        }

                        this.updateFormValidity();
                    } catch (error) {
                        console.error('Validation error:', error);
                    }
                },

                updateFormValidity() {
                    this.isFormValid = this.form.email &&
                        !this.errors.email;
                },

                isValidEmail(email) {
                    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    return re.test(email);
                },

                getFieldClass(field) {
                    if (!this.form[field]) return '';
                    return this.errors[field] ? 'is-invalid' : 'is-valid';
                }
            }));
        });
    </script>

</body>

</html>