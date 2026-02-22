<?php
$pageTitle = "Gym Flow Register";
$currentPage = "register";
$extraStyles = ["style.css"];
$extraScripts = ["script.js"];
include 'includes/header.php';
?>

<div class="register-container" style="margin: 80px auto;">
    <h1>Create Account</h1>

    <form action="process/Register_process.php" method="POST">

        
        <div class="row">
            <div class="input-group">
                <label>First Name</label>
                <input type="text" name="firstname" placeholder="John" maxlength="30" required>
            </div>

            <div class="input-group">
                <label>Last Name</label>
                <input type="text" name="lastname" placeholder="Doe" maxlength="30" required>
            </div>
        </div>

        
        <div class="input-group">
            <label>Email Address</label>
            <input type="email" name="email" placeholder="your.email@example.com" maxlength="100" required>
        </div>

        <div class="input-group">
            <label>Phone Number</label>
            <input 
                type="tel"
                name="number" 
                placeholder="0771234567"
                maxlength="10"
                inputmode="numeric"
                required>
        </div>

        <div class="input-group">
            <label>Age</label>
            <input type="number" name="age" placeholder="25" min="18" max="100" required>
        </div>

        <div class="row">
            <div class="input-group password-group">
                <label>Password</label>
                <div class="password-wrapper">
                    <input type="password" id="password" name="password" placeholder="Enter password" 
                    minlength="6" 
                    maxlength="12" 
                    required>
                    <i class="fa-solid fa-eye toggle-password" onclick="toggleBothPassword(this)"></i>
                </div>
            </div>

            <div class="input-group password-group">
                <label>Confirm Password</label>
                <div class="password-wrapper">
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Re-enter password" 
                    minlength="6" 
                    maxlength="12" 
                    required>
                    <i class="fa-solid fa-eye toggle-password" onclick="toggleBothPassword(this)"></i>
                </div>
            </div>
        </div>

        
        <div class="input-group">
            <label>Select Membership Plan</label>
            <select name="plan" required>
                <option value="">Choose a plan</option>
                <?php
                include 'includes/Database.php';
                $plans_res = $conn->query("SELECT title FROM membership_plans ORDER BY price ASC");
                while($p = $plans_res->fetch_assoc()) {
                    echo "<option value='".$p['title']."'>".$p['title']."</option>";
                }
                ?>
            </select>
        </div>

        
        <div class="terms">
            <input type="checkbox" required>
            <span>I agree to the <a href="#">Terms and Conditions</a> and <a href="#">Privacy Policy</a></span>
        </div>

        <button type="submit" class="member-btn">Create Account</button>

        <p style="text-align: center; margin-top: 15px;">Already have an account? <a href="login.php">Login</a></p>
    </form>
</div>

<?php include 'includes/footer.php'; ?>