<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: login.php");
    exit();
}

$pageTitle = "Manage Users - Admin";
$currentPage = "dashboard";
$extraStyles = ["admin-dashboard.css"];
include 'includes/header.php';
include 'includes/Database.php';


$msg = $_GET['msg'] ?? '';
if ($msg == 'deleted') echo "<script>alert('Member successfully deleted!');</script>";
if ($msg == 'updated') echo "<script>alert('Member successfully updated!');</script>";

$sql = "SELECT id, firstname, lastname, email, number, age, plan, role FROM users WHERE role != 'admin' ORDER BY id DESC";
$result = $conn->query($sql);
?>

<div class="container" style="padding: 120px 5% 50px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h1 style="color: var(--neon-green); margin: 0;">Manage Registered Members</h1>
        <p style="color: #666; font-weight: bold; text-transform: uppercase; letter-spacing: 1px;">Admin Control Panel</p>
    </div>

    <div style="overflow-x: auto; background: var(--card-bg); border-radius: 12px; border: 1px solid rgba(0,255,0,0.1); overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: rgba(0,255,0,0.1);">
                <tr>
                    <th style="padding: 20px; text-align: left; color: #666; font-size: 13px; text-transform: uppercase;">Name</th>
                    <th style="padding: 20px; text-align: left; color: #666; font-size: 13px; text-transform: uppercase;">Contact info</th>
                    <th style="padding: 20px; text-align: left; color: #666; font-size: 13px; text-transform: uppercase;">Age</th>
                    <th style="padding: 20px; text-align: left; color: #666; font-size: 13px; text-transform: uppercase;">Plan</th>
                    <th style="padding: 20px; text-align: left; color: #666; font-size: 13px; text-transform: uppercase;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.05); transition: 0.3s;" onmouseover="this.style.background='rgba(0,255,0,0.02)'" onmouseout="this.style.background='transparent'">
                        <td style="padding: 20px;">
                            <div style="color: white; font-weight: 700; font-size: 16px;"><?php echo $row['firstname'] . " " . $row['lastname']; ?></div>
                            <div style="color: #666; font-size: 12px; margin-top: 4px;">UID: #<?php echo $row['id']; ?></div>
                        </td>
                        <td style="padding: 20px;">
                            <div style="color: #aaa; font-size: 14px;"><?php echo $row['email']; ?></div>
                            <div style="color: #666; font-size: 12px; margin-top: 4px;"><?php echo $row['number']; ?></div>
                        </td>
                        <td style="padding: 20px; color: #aaa;"><?php echo $row['age']; ?></td>
                        <td style="padding: 20px;">
                            <span style="background: rgba(0,255,0,0.1); color: var(--neon-green); padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: bold;">
                                <?php echo $row['plan']; ?>
                            </span>
                        </td>
                        <td style="padding: 20px;">
                            <div style="display: flex; gap: 15px;">
                                <a href="members_update.php?id=<?php echo $row['id']; ?>" style="color: var(--neon-green); text-decoration: none; font-size: 14px; font-weight: 600;">Update</a>
                                <a href="members_delete.php?id=<?php echo $row['id']; ?>" style="color: #ff3c00; text-decoration: none; font-size: 14px; font-weight: 600;" onclick="return confirm('Are you sure you want to delete this member?')">Delete</a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="padding: 50px; text-align: center; color: #666;">No members registered yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    
    <div style="margin-top: 30px;">
        <a href="dashboard.php" class="btn-login" style="text-decoration: none; padding: 12px 25px;">← Return to Dashboard</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
