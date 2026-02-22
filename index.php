<?php
$pageTitle = "Gym Flow Home";
$currentPage = "home";
$extraStyles = ["home.css"];
include 'includes/header.php';
?>


<section class="hero">
    
    <h1>
        <span>TRANSFORM YOUR BODY</span>
    </h1>

    
    <p>
        Join Gym Flow and experience a revolutionary approach to fitness with cutting-edge facilities and expert guidance
    </p>

    
    <div class="hero-buttons">
        <a href="membership.php" class="btn">Get Started</a>
        <a href="booking.php" class="btn">Book a Class</a>
    </div>
</section>



<section class="stats">
  <div class="stat-box">
    <div class="inner-box">
      <h2>50+</h2>
      <p>Members</p>
    </div>
  </div>
  <div class="stat-box">
    <div class="inner-box">
      <h2>10+</h2>
      <p>Classes</p>
    </div>
  </div>
  <div class="stat-box">
    <div class="inner-box">
      <h2>5+</h2>
      <p>Trainers</p>
    </div>
  </div>
  <div class="stat-box">
    <div class="inner-box">
      <h2>24/7</h2>
      <p>Access</p>
    </div>
  </div>
</section>

<h3 class="section-title">Why Choose <span>Gym Flow?</span></h3>


<section class="features">
  <div class="feature-box">
    <h3>Modern Equipment</h3>
    <p>State-of-the-art fitness equipment for all your workout needs</p>
  </div>
  <div class="feature-box">
    <h3>Expert Trainers</h3>
    <p>Certified professionals to guide your fitness journey</p>
  </div>
  <div class="feature-box">
    <h3>Flexible Scheduling</h3>
    <p>Book classes and sessions that fit your schedule</p>
  </div>
  <div class="feature-box">
    <h3>Track Progress</h3>
    <p>Monitor your fitness goals with advanced analytics</p>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
