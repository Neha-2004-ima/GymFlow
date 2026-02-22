<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : "Gym Flow"; ?></title>
    <?php if (isset($extraStyles)): ?>
        <?php foreach ($extraStyles as $style): ?>
            <link rel="stylesheet" href="css/<?php echo $style; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    <link rel="stylesheet" href="css/nav.css">
        
    
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico">

    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>

<nav>
    <div class="logo">
        <i data-lucide="dumbbell"></i>
        <span>GYM FLOW</span>
    </div>

    
    <div class="menu-toggle" id="mobile-menu">
        <i data-lucide="menu"></i>
    </div>

    <ul class="nav-links">
        <li><a href="index.php" class="<?php echo ($currentPage == 'home') ? 'active' : ''; ?>">Home</a></li>
        <li><a href="membership.php" class="<?php echo ($currentPage == 'membership') ? 'active' : ''; ?>">Membership</a></li>
        <li><a href="booking.php" class="<?php echo ($currentPage == 'booking') ? 'active' : ''; ?>">Booking</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="dashboard.php" class="<?php echo ($currentPage == 'dashboard') ? 'active' : ''; ?>">Dashboard</a></li>
            <li><a href="process/logout.php" class="logout-nav-btn">Logout</a></li>
        <?php else: ?>
            <li><a href="login.php" class="login-nav-btn <?php echo ($currentPage == 'login') ? 'active' : ''; ?>">Login</a></li>
        <?php endif; ?>
    </ul>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        lucide.createIcons();
        
        const mobileMenu = document.getElementById('mobile-menu');
        const navLinks = document.querySelector('.nav-links');
        
        if (mobileMenu && navLinks) {
            mobileMenu.addEventListener('click', function() {
                navLinks.classList.toggle('active');
                const icon = this.querySelector('i');
                if (navLinks.classList.contains('active')) {
                    icon.setAttribute('data-lucide', 'x');
                } else {
                    icon.setAttribute('data-lucide', 'menu');
                }
                lucide.createIcons();
            });
        }

        
        window.addEventListener('scroll', function() {
            const nav = document.querySelector('nav');
            if (window.scrollY > 50) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        });
    });
</script>
