<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: login.php");
    exit();
}

$pageTitle = "Update User - Admin";
$currentPage = "dashboard";
$extraStyles = ["admin-dashboard.css"];
include 'includes/header.php';
include 'includes/Database.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: members_manage.php");
    exit();
}

$sql = "SELECT * FROM users WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "<div class='container' style='padding:150px 5%; color:white; text-align:center;'>
            <h2 style='color:var(--neon-green);'>User Not Found</h2>
            <p style='color:#666; margin-top:10px;'>The user you are trying to edit does not exist.</p>
            <a href='members_manage.php' class='btn' style='margin-top:30px; display:inline-block;'>Back to User List</a>
          </div>";
    include 'includes/footer.php';
    exit();
}
?>

<div class="container" style="padding: 120px 5% 50px;">
    <div style="max-width: 800px; margin: auto;">
        <h1 style="color: var(--neon-green); margin-bottom: 30px;">Update Member Profile</h1>
        
        <div class="card" style="background: var(--card-bg); padding: 40px; border-radius: 15px; border: 1px solid rgba(0,255,0,0.1); box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
            <form action="process/Update_process.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-bottom: 25px;">
                    <div class="input-group">
                        <label style="color: #666; font-size: 13px; text-transform: uppercase; font-weight: bold; letter-spacing: 1px; margin-bottom: 8px; display: block;">First Name</label>
                        <input type="text" name="firstname" value="<?php echo $user['firstname']; ?>" required style="width: 100%; padding: 14px; background: #0a0a0a; border: 1px solid #333; color: white; border-radius: 6px; outline: none; transition: 0.3s;" onfocus="this.style.borderColor='var(--neon-green)'" onblur="this.style.borderColor='#333'">
                    </div>
                    <div class="input-group">
                        <label style="color: #666; font-size: 13px; text-transform: uppercase; font-weight: bold; letter-spacing: 1px; margin-bottom: 8px; display: block;">Last Name</label>
                        <input type="text" name="lastname" value="<?php echo $user['lastname']; ?>" required style="width: 100%; padding: 14px; background: #0a0a0a; border: 1px solid #333; color: white; border-radius: 6px; outline: none; transition: 0.3s;" onfocus="this.style.borderColor='var(--neon-green)'" onblur="this.style.borderColor='#333'">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-bottom: 25px;">
                    <div class="input-group">
                        <label style="color: #666; font-size: 13px; text-transform: uppercase; font-weight: bold; letter-spacing: 1px; margin-bottom: 8px; display: block;">Email Address</label>
                        <input type="email" name="email" value="<?php echo $user['email']; ?>" required style="width: 100%; padding: 14px; background: #0a0a0a; border: 1px solid #333; color: white; border-radius: 6px; outline: none; transition: 0.3s;" onfocus="this.style.borderColor='var(--neon-green)'" onblur="this.style.borderColor='#333'">
                    </div>
                    <div class="input-group">
                        <label style="color: #666; font-size: 13px; text-transform: uppercase; font-weight: bold; letter-spacing: 1px; margin-bottom: 8px; display: block;">Phone Number</label>
                        <input type="text" name="number" value="<?php echo $user['number']; ?>" required style="width: 100%; padding: 14px; background: #0a0a0a; border: 1px solid #333; color: white; border-radius: 6px; outline: none; transition: 0.3s;" onfocus="this.style.borderColor='var(--neon-green)'" onblur="this.style.borderColor='#333'">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-bottom: 35px;">
                    <div class="input-group">
                        <label style="color: #666; font-size: 13px; text-transform: uppercase; font-weight: bold; letter-spacing: 1px; margin-bottom: 8px; display: block;">Age</label>
                        <input type="number" name="age" value="<?php echo $user['age']; ?>" required style="width: 100%; padding: 14px; background: #0a0a0a; border: 1px solid #333; color: white; border-radius: 6px; outline: none; transition: 0.3s;" onfocus="this.style.borderColor='var(--neon-green)'" onblur="this.style.borderColor='#333'">
                    </div>
                    <div class="input-group">
                        <label style="color: #666; font-size: 13px; text-transform: uppercase; font-weight: bold; letter-spacing: 1px; margin-bottom: 8px; display: block;">Membership Plan</label>
                        <select name="plan" required style="width: 100%; padding: 14px; background: #0a0a0a; border: 1px solid #333; color: white; border-radius: 6px; outline: none; transition: 0.3s;" onfocus="this.style.borderColor='var(--neon-green)'" onblur="this.style.borderColor='#333'">
                            <?php
                            $plans_res = $conn->query("SELECT title FROM membership_plans ORDER BY price ASC");
                            while($p = $plans_res->fetch_assoc()) {
                                $selected = ($user['plan'] == $p['title']) ? "selected" : "";
                                echo "<option value='".$p['title']."' $selected>".$p['title']."</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div style="display: flex; gap: 20px; align-items: center;">
                    <button type="submit" class="btn" style="flex: 2; padding: 18px;">Update Member Profile</button>
                    <a href="members_manage.php" class="btn-login" style="flex: 1; text-decoration: none; padding: 18px; text-align: center;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
