<?php
/**
 * E-DSR Shared Header / Navbar
 * This file is included on every authenticated page.
 * Anti-flash script is first to prevent theme flicker before CSS loads.
 */
?>
<!-- ═══════════════════════════════════════════════════════════════
     ANTI-FLASH THEME SCRIPT
     Must run before any CSS renders to avoid light→dark flicker.
     ════════════════════════════════════════════════════════════════ -->
<script>
(function(){
    var t = localStorage.getItem('edsr-theme');
    if (!t) {
        t = (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) ? 'dark' : 'light';
    }
    document.documentElement.setAttribute('data-theme', t);
    document.documentElement.setAttribute('data-bs-theme', t);
    window.EDSR_THEME = t;
})();
</script>

<!-- ═══════════════════════════════════════════════════════════════
     NAVBAR — Sticky top, role-based links, theme toggle
     ════════════════════════════════════════════════════════════════ -->
<nav class="edsr-navbar navbar navbar-expand-lg px-3 px-lg-4">
    <div class="container-fluid p-0">

        <!-- Brand -->
        <a class="navbar-brand d-flex align-items-center gap-2" href="welcome_page.php">
            <span style="background:var(--primary);border-radius:6px;width:28px;height:28px;display:inline-flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="fa-solid fa-chart-line" style="color:#fff;font-size:0.75rem;"></i>
            </span>
            <span>E-DSR</span>
        </a>

        <!-- User badge + toggler row for mobile -->
        <div class="d-flex align-items-center gap-2 d-lg-none ms-auto me-2">
            <span class="edsr-user-badge">
                <i class="fa-solid fa-user me-1" style="font-size:0.7rem;opacity:0.7;"></i>
                <?php echo htmlspecialchars($name ?? ''); ?>
            </span>
            <!-- Theme toggle (mobile) -->
            <button class="theme-toggle-btn" aria-label="Toggle theme" title="Toggle theme">
                <i class="fa-solid fa-moon"></i>
            </button>
        </div>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav" aria-controls="navbarNav"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Left nav links -->
            <ul class="navbar-nav me-auto ms-2 gap-1">
                <li class="nav-item">
                    <a class="nav-link" href="welcome_page.php">
                        <i class="fa-solid fa-house me-1" style="font-size:0.8rem;opacity:0.75;"></i>Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="encode.php">
                        <i class="fa-solid fa-pen-to-square me-1" style="font-size:0.8rem;opacity:0.75;"></i>Encode
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="bo_search.php">
                        <i class="fa-solid fa-magnifying-glass me-1" style="font-size:0.8rem;opacity:0.75;"></i>Search
                    </a>
                </li>
                <!-- Admin-only links — rendered via JS hideElement.js based on role -->
                <li id="users" class="nav-item admin">
                    <a class="nav-link" href="user.php">
                        <i class="fa-solid fa-users me-1" style="font-size:0.8rem;opacity:0.75;"></i>Users
                    </a>
                </li>
                <li id="leave" class="nav-item admin">
                    <a class="nav-link" href="leaveData.php">
                        <i class="fa-solid fa-calendar-xmark me-1" style="font-size:0.8rem;opacity:0.75;"></i>Leave Data
                    </a>
                </li>
                <li id="customize" class="nav-item admin">
                    <a class="nav-link" href="customize.php">
                        <i class="fa-solid fa-sliders me-1" style="font-size:0.8rem;opacity:0.75;"></i>Customize
                    </a>
                </li>
                <li id="bo_dashboard" class="nav-item admin">
                    <a class="nav-link" href="bo_dashboard.php">
                        <i class="fa-solid fa-chart-bar me-1" style="font-size:0.8rem;opacity:0.75;"></i>BO Dashboard
                    </a>
                </li>
            </ul>

            <!-- Right side: user badge + theme toggle + logout -->
            <div class="d-none d-lg-flex align-items-center gap-2 ms-auto">
                <span class="edsr-user-badge">
                    <i class="fa-solid fa-user me-1" style="font-size:0.7rem;opacity:0.7;"></i>
                    <?php echo htmlspecialchars($name ?? ''); ?>
                </span>

                <!-- Theme Toggle Button -->
                <button class="theme-toggle-btn" aria-label="Toggle theme" title="Toggle Dark Mode">
                    <i class="fa-solid fa-moon"></i>
                </button>

                <!-- Logout -->
                <a href="../php/logout.php?logoutid=<?php echo urlencode($name ?? ''); ?>"
                   onclick="return confirm('Logout Account?')"
                   class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1"
                   style="border-color:rgba(255,255,255,0.2);color:var(--sidebar-text);">
                    <i class="fa-solid fa-right-from-bracket" style="font-size:0.8rem;"></i>
                    <span>Logout</span>
                </a>
            </div>

            <!-- Mobile logout -->
            <div class="d-flex d-lg-none mt-2 pb-1">
                <a href="../php/logout.php?logoutid=<?php echo urlencode($name ?? ''); ?>"
                   onclick="return confirm('Logout Account?')"
                   class="nav-link text-danger">
                    <i class="fa-solid fa-right-from-bracket me-1"></i>Logout
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- Theme toggle JS — deferred so it doesn't block rendering -->
<script defer src="/e-dsr/js/theme-toggle.js"></script>

<!-- Active nav link highlighter -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    var currentPath = window.location.pathname.split('/').pop();
    document.querySelectorAll('.edsr-navbar .nav-link').forEach(function (link) {
        var href = link.getAttribute('href') || '';
        if (href && currentPath && currentPath === href) {
            link.classList.add('active');
        }
    });
});
</script>
