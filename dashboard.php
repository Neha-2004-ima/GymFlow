<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'] ?? 'member';
$pageTitle = "Dashboard - GymFlow";
$currentPage = "dashboard";

if ($role == 'admin') {
    $extraStyles = ["admin-dashboard.css"];
    include 'includes/Database.php';
    include 'includes/header.php';
    
     
    $members_sql = "SELECT COUNT(*) AS total_members FROM users WHERE role = 'member'";
    $m_result = $conn->query($members_sql);
    $m_row = $m_result->fetch_assoc();
    $total_members = $m_row['total_members'];

    $classes_sql = "SELECT COUNT(*) AS total_classes FROM classes";
    $c_result = $conn->query($classes_sql);
    $c_row = $c_result->fetch_assoc();
    $total_classes = $c_row['total_classes'];

    $bookings_sql = "SELECT COUNT(*) AS today_bookings FROM bookings WHERE DATE(created_at) = CURDATE()";
    $b_result = $conn->query($bookings_sql);
    $b_row = $b_result->fetch_assoc();
    $today_bookings = $b_row['today_bookings'];
    ?>
    <div class="container" style="padding: 100px 20px 20px;">
        <div class="welcome">
            <h1>Admin Dashboard 👑</h1>
            <p>Manage your gym efficiently and monitor activities.</p>
        </div>

        <div class="summary-container" style="display: flex; gap: 20px; margin: 20px 0;">
            <div class="summary-card" style="flex: 1; padding: 20px; border-radius: 10px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); text-align: center;">
                <h3>Total Members</h3>
                <p style="font-size: 24px; font-weight: bold; color: #ff3c00;">
                    <?php echo $total_members; ?>
                </p>
            </div>
            <div class="summary-card" style="flex: 1; padding: 20px; border-radius: 10px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); text-align: center;">
                <h3>Total Classes</h3>
                <p style="font-size: 24px; font-weight: bold; color: #ff3c00;">
                    <?php echo $total_classes; ?>
                </p>
            </div>
            <div class="summary-card" style="flex: 1; padding: 20px; border-radius: 10px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); text-align: center;">
                <h3>Today's Bookings</h3>
                <p style="font-size: 24px; font-weight: bold; color: #ff3c00;">
                    <?php echo $today_bookings; ?>
                </p>
            </div>
        </div>

        <div class="card" style="margin-top: 40px;">
            <h2>Management Controls</h2>
            <div style="display: flex; gap: 20px; margin-top: 15px;">
                <a href="members_manage.php" style="background:rgba(255,255,255,0.05); border: 1px solid #00ff00; color: #00ff00; padding: 15px 30px; border-radius: 10px; text-decoration: none; font-weight: bold; flex: 1; text-align: center; ">Manage Members</a>
                <a href="plans_manage.php" style="background: rgba(255,255,255,0.05); border: 1px solid #00ff00; color: #00ff00; padding: 15px 30px; border-radius: 10px; text-decoration: none; font-weight: bold; flex: 1; text-align: center;">Manage Plans</a>
                <a href="classes_manage.php" style="background:rgba(255,255,255,0.05); border: 1px solid #00ff00; color: #00ff00; padding: 15px 30px; border-radius: 10px; text-decoration: none; font-weight: bold; flex: 1; text-align: center;">Manage Classes</a>
                
            </div>
        </div>
    </div>
    <?php
} else {
    $extraStyles = ["member-dashboard.css"];
    include 'includes/Database.php';
    include 'includes/header.php';
    
    
    $user_id = $_SESSION['user_id'];
    $bookings_sql = "SELECT b.id as booking_id, c.name, c.instructor, c.day, c.time 
                    FROM bookings b 
                    JOIN classes c ON b.class_id = c.id 
                    WHERE b.user_id = ? AND b.status = 'active'
                    ORDER BY FIELD(c.day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'), c.time";
    $b_stmt = $conn->prepare($bookings_sql);
    $b_stmt->bind_param("i", $user_id);
    $b_stmt->execute();
    $bookings = $b_stmt->get_result();
    ?>
    <div class="container" style="padding: 120px 20px 50px;">
        <div class="welcome">
            <h1 style="color: var(--neon-green);">Welcome, <?php echo $_SESSION['firstname']; ?> 👋</h1>
            <p style="color: #aaa;">Track your fitness journey and stay consistent.</p>
        </div>

        <div class="summary-container" style="display: flex; gap: 20px; margin: 30px 0;">
            <div class="summary-card" style="flex: 1; padding: 25px; border-radius: 12px; background: rgba(255,255,255,0.02); border: 1px solid rgba(0,255,0,0.1); text-align: center;">
                <h3 style="color: #666; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;">Membership Plan</h3>
                <p style="font-size: 24px; font-weight: 800; color: var(--neon-green); margin-top: 10px;"><?php echo $_SESSION['plan']; ?></p>
            </div>
            <div class="summary-card" style="flex: 1; padding: 25px; border-radius: 12px; background: rgba(255,255,255,0.02); border: 1px solid rgba(0,255,0,0.1); text-align: center;">
                <h3 style="color: #666; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;">Status</h3>
                <p style="font-size: 24px; font-weight: 800; color: var(--neon-green); margin-top: 10px;">Active</p>
            </div>
        </div>

        <div class="card" style="margin-top: 50px; background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); padding: 30px; border-radius: 15px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                <h2 style="color: white; font-weight: 800;">My Booked Classes</h2>
                <a href="booking.php" class="btn-login" style="padding: 10px 20px; text-decoration: none; border-radius: 5px; font-size: 14px;">+ Book New Class</a>
            </div>

            <?php if ($bookings->num_rows > 0): ?>
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.1); text-align: left;">
                                <th style="padding: 15px; color: #666; font-size: 13px; text-transform: uppercase;">Class</th>
                                <th style="padding: 15px; color: #666; font-size: 13px; text-transform: uppercase;">Instructor</th>
                                <th style="padding: 15px; color: #666; font-size: 13px; text-transform: uppercase;">Day</th>
                                <th style="padding: 15px; color: #666; font-size: 13px; text-transform: uppercase;">Time</th>
                                <th style="padding: 15px; color: #666; font-size: 13px; text-transform: uppercase;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($b = $bookings->fetch_assoc()): ?>
                                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05); transition: 0.3s;" onmouseover="this.style.background='rgba(0,255,0,0.02)'" onmouseout="this.style.background='transparent'">
                                    <td style="padding: 15px; color: white; font-weight: 600;"><?php echo $b['name']; ?></td>
                                    <td style="padding: 15px; color: #aaa;"><?php echo $b['instructor']; ?></td>
                                    <td style="padding: 15px;"><span style="background: rgba(0,255,0,0.1); color: var(--neon-green); padding: 4px 12px; border-radius: 20px; font-size: 12px;"><?php echo $b['day']; ?></span></td>
                                    <td style="padding: 15px; color: #aaa;"><?php echo date("h:i A", strtotime($b['time'])); ?></td>
                                    <td style="padding: 15px;">
                                        <a href="process/cancel_booking.php?id=<?php echo $b['booking_id']; ?>" onclick="return confirm('Are you sure you want to cancel this booking?')" style="color: #ff3c00; text-decoration: none; font-size: 14px;">Cancel</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div style="text-align: center; padding: 40px;">
                    <p style="color: #666; margin-bottom: 20px;">No classes booked yet.</p>
                    <a href="booking.php" class="btn" style="padding: 12px 30px;">Browse Class Schedules</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php
}

include 'includes/footer.php';
?>