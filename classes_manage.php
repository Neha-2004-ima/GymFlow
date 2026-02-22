<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'includes/Database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$pageTitle = "Manage Classes - Admin";
$currentPage = "manage_classes";
$extraStyles = ["admin-dashboard.css"];
$extraScripts = ["manage-classes.js"];
include 'includes/header.php';


if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM classes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "<script>alert('Class successfully deleted!'); window.location.href='classes_manage.php';</script>";
    }
}


$edit = false;
$edit_data = ['name' => '', 'instructor' => '', 'day' => '', 'time' => '', 'total_spots' => ''];
if (isset($_GET['edit'])) {
    $edit = true;
    $id = $_GET['edit'];
    $res = $conn->query("SELECT * FROM classes WHERE id = $id");
    $edit_data = $res->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $instructor = $_POST['instructor'];
    $day = $_POST['day'];
    $time = $_POST['time'];
    $spots = $_POST['spots'];

    if ($edit) {
        $id = $_POST['id'];
        $sql = "UPDATE classes SET name=?, instructor=?, day=?, time=?, total_spots=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssii", $name, $instructor, $day, $time, $spots, $id);
    } else {
        $sql = "INSERT INTO classes (name, instructor, day, time, total_spots) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $name, $instructor, $day, $time, $spots);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Class successfully added/updated!'); window.location.href='classes_manage.php';</script>";
    }
}
?>

<div class="container" style="padding: 120px 5% 50px;">
    <h1 style="color: var(--neon-green); margin-bottom: 30px;"><?php echo $edit ? 'Update Class' : 'Add New Class'; ?></h1>
    
    <div class="card" style="background: var(--card-bg); padding: 30px; border-radius: 12px; border: 1px solid rgba(0,255,0,0.1); margin-bottom: 50px;">
        <form method="POST">
            <?php if ($edit): ?>
                <input type="hidden" name="id" value="<?php echo $edit_data['id']; ?>">
            <?php endif; ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                <div class="input-group">
                    <label>Class Name</label>
                    <input type="text" name="name" value="<?php echo $edit_data['name']; ?>" required style="width: 100%; padding: 12px; background: #111; border: 1px solid #333; color: white; border-radius: 5px;">
                </div>
                <div class="input-group">
                    <label>Instructor</label>
                    <input type="text" name="instructor" value="<?php echo $edit_data['instructor']; ?>" required style="width: 100%; padding: 12px; background: #111; border: 1px solid #333; color: white; border-radius: 5px;">
                </div>
                <div class="input-group">
                    <label>Day</label>
                    <select name="day" required style="width: 100%; padding: 12px; background: #111; border: 1px solid #333; color: white; border-radius: 5px;">
                        <option value="Monday" <?php echo $edit_data['day'] == 'Monday' ? 'selected' : ''; ?>>Monday</option>
                        <option value="Tuesday" <?php echo $edit_data['day'] == 'Tuesday' ? 'selected' : ''; ?>>Tuesday</option>
                        <option value="Wednesday" <?php echo $edit_data['day'] == 'Wednesday' ? 'selected' : ''; ?>>Wednesday</option>
                        <option value="Thursday" <?php echo $edit_data['day'] == 'Thursday' ? 'selected' : ''; ?>>Thursday</option>
                        <option value="Friday" <?php echo $edit_data['day'] == 'Friday' ? 'selected' : ''; ?>>Friday</option>
                        <option value="Saturday" <?php echo $edit_data['day'] == 'Saturday' ? 'selected' : ''; ?>>Saturday</option>
                        <option value="Sunday" <?php echo $edit_data['day'] == 'Sunday' ? 'selected' : ''; ?>>Sunday</option>
                    </select>
                </div>
                <div class="input-group">
                    <label>Time</label>
                    <input type="time" name="time" value="<?php echo $edit_data['time']; ?>" required style="width: 100%; padding: 12px; background: #111; border: 1px solid #333; color: white; border-radius: 5px;">
                </div>
                <div class="input-group">
                    <label>Capacity</label>
                    <input type="number" name="spots" value="<?php echo $edit_data['total_spots']; ?>" required style="width: 100%; padding: 12px; background: #111; border: 1px solid #333; color: white; border-radius: 5px;">
                </div>
            </div>
            <button type="submit" class="btn" style="margin-top: 20px;"><?php echo $edit ? 'Update Class' : 'Create Class'; ?></button>
            <?php if ($edit): ?>
                <a href="classes_manage.php" style="color: #666; margin-left: 20px;">Cancel</a>
            <?php endif; ?>
        </form>
    </div>

    <h2 style="color: white; margin-bottom: 20px;">Existing Classes</h2>
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; background: var(--card-bg); border-radius: 12px; overflow: hidden;">
            <thead style="background: rgba(0,255,0,0.1);">
                <tr>
                    <th style="padding: 15px; text-align: left;">Class</th>
                    <th style="padding: 15px; text-align: left;">Instructor</th>
                    <th style="padding: 15px; text-align: left;">Day</th>
                    <th style="padding: 15px; text-align: left;">Time</th>
                    <th style="padding: 15px; text-align: left;">Spots</th>
                    <th style="padding: 15px; text-align: left;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $classes = $conn->query("SELECT * FROM classes ORDER BY FIELD(day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'), time");
                while ($c = $classes->fetch_assoc()): ?>
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <td style="padding: 15px;"><?php echo $c['name']; ?></td>
                        <td style="padding: 15px;"><?php echo $c['instructor']; ?></td>
                        <td style="padding: 15px;"><?php echo $c['day']; ?></td>
                        <td style="padding: 15px;"><?php echo date("h:i A", strtotime($c['time'])); ?></td>
                        <td style="padding: 15px;"><?php echo $c['total_spots']; ?></td>
                        <td style="padding: 15px;">
                            <a href="?edit=<?php echo $c['id']; ?>" style="color: #00ff00; text-decoration: none; margin-right: 15px;">Update</a>
                            <a href="?delete=<?php echo $c['id']; ?>" onclick="return confirm('Are you sure you want to delete this class?')" style="color: #ff3333; text-decoration: none;">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div style="margin-top: 30px;">
        <a href="dashboard.php" class="btn-login" style="text-decoration: none; padding: 12px 25px;">← Return to Dashboard</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
