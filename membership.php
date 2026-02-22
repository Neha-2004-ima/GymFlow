<?php
$pageTitle = "Gym Flow Membership";
$currentPage = "membership";
$extraStyles = ["membership.css"];
include 'includes/header.php';
?>

<div class="header-row" style="margin-top: 50px;">
  <h1>Membership Plans</h1>
  <p>Choose the perfect plan that fits your fitness goals and budget</p>
</div>


<div class="plans-row">
<?php
include 'includes/Database.php';
$sql = "SELECT * FROM membership_plans";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $features = explode(',', $row['features']);
        $popularClass = $row['is_popular'] ? 'most-popular' : '';
        ?>
        <div class="plan-box <?php echo $popularClass; ?>">
            <div class="plan-content">
                <div class="plan-title"><?php echo $row['title']; ?></div>
                <div class="plan-subtitle"><?php echo $row['subtitle']; ?></div>
                <div class="plan-price">LKR:<?php echo number_format($row['price'], 0); ?> / month</div>
                <ul class="plan-features">
                    <?php foreach($features as $feature): ?>
                        <li><?php echo trim($feature); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <button class="plan-button">
                <a href="login.php">Get Started</a>
            </button>
        </div>
        <?php
    }
} else {
    echo "<p style='color: white;'>No plans available at the moment.</p>";
}
?>
</div>


<div class="features-row">

  
  <div class="feature-box">
    <div class="feature-icon">🏋️</div>
    <div class="feature-title">No Commitment</div>
    <div class="feature-desc">Cancel anytime, no questions asked</div>
  </div>

  
  <div class="feature-box">
    <div class="feature-icon">💪</div>
    <div class="feature-title">7-Day Free Trial</div>
    <div class="feature-desc">Try any plan for free before you commit</div>
  </div>

  
  <div class="feature-box">
    <div class="feature-icon">🎯</div>
    <div class="feature-title">Money-Back Guarantee</div>
    <div class="feature-desc">30-day satisfaction guarantee</div>
  </div>

</div>

<?php include 'includes/footer.php'; ?>
