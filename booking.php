<?php
$pageTitle = "Gym Flow Booking";
$currentPage = "booking";
$extraStyles = ["booking.css"];
include 'includes/header.php';
?>

<?php
include 'includes/Database.php';


$sql = "SELECT c.*, 
        (SELECT COUNT(*) FROM bookings b WHERE b.class_id = c.id AND b.status = 'active') as booked_count 
        FROM classes c";

$schedule_res = $conn->query($sql);

if (!$schedule_res) {
    die("<div class='container' style='padding:100px; color:red;'>
            <h2>Database Error</h2>
            <p>It seems your database tables are not up to date. Please run the SQL file: <code>sql/fix_bookings_error.sql</code> in PHPMyAdmin.</p>
            <p>Error details: " . $conn->error . "</p>
         </div>");
}

$schedule_data = [];
while($row = $schedule_res->fetch_assoc()) {
    $schedule_data[$row['day']][] = [
        'id' => $row['id'],
        'time' => date("h:i A", strtotime($row['time'])),
        'name' => $row['name'],
        'instructor' => $row['instructor'],
        'total' => (int)$row['total_spots'],
        'booked' => (int)$row['booked_count']
    ];
}
$json_schedule = json_encode($schedule_data);
?>

<div class="header" style="margin-top: 100px;">
    <h1>Book a Class</h1>
    <p>Select a day and choose from our wide range of fitness classes</p>
</div>

<div class="days" id="daysContainer"></div>
<div id="classContainer"></div>

<div class="info">
    <h3>Booking Information</h3>
    <ul>
        <li>Classes can be booked up to 7 days in advance</li>
        <li>Free cancellation up to 2 hours before class starts</li>
        <li>Please login to confirm your booking</li>
    </ul>
</div>

<script>
const schedule = <?php echo $json_schedule; ?>;
const days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
const daysContainer = document.getElementById("daysContainer");

days.forEach((day) => {
    const btn = document.createElement("button");
    btn.classList.add("day");
    if(day === "Monday") btn.classList.add("active");
    btn.innerText = day;

    btn.onclick = function(){
        document.querySelectorAll(".day").forEach(d => d.classList.remove("active"));
        this.classList.add("active");
        displayClasses(day);
    };
    daysContainer.appendChild(btn);
});

async function bookClass(classId) {
    <?php if (!isset($_SESSION['user_id'])): ?>
        alert("Please login to book a class!");
        window.location.href = "login.php";
        return;
    <?php endif; ?>

    const formData = new FormData();
    formData.append('class_id', classId);

    try {
        const response = await fetch('process/book_class.php', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();
        alert(data.message);
        if(data.status === 'success') location.reload();
    } catch (e) {
        alert("An error occurred. Please try again.");
    }
}

function displayClasses(day){
    const container = document.getElementById("classContainer");
    container.innerHTML = "";
    const classes = schedule[day] || [];

    if(classes.length === 0){
        container.innerHTML = "<p style='margin:40px 60px;color:#aaa;'>No classes scheduled for this day.</p>";
        return;
    }

    classes.forEach(cls => {
        const percentage = (cls.booked / cls.total) * 100;
        const spotsLeft = cls.total - cls.booked;
        const almostFull = spotsLeft <= 3;

        const card = document.createElement("div");
        card.className = "card";
        if(almostFull) card.classList.add("almost");

        card.innerHTML = `
            <div>
                <small>${cls.time} ${almostFull ? "• Almost Full" : ""}</small>
                <h2>${cls.name}</h2>
                <p>Instructor: ${cls.instructor}</p>
            </div>
            <div class="right">
                <div class="spots">${spotsLeft} spots left (${cls.booked}/${cls.total})</div>
                <div class="progress">
                    <div class="fill ${almostFull ? "red" : ""}" style="width:${percentage}%"></div>
                </div>
                <button class="book-btn" onclick="bookClass(${cls.id})">Book</button>
            </div>
        `;
        container.appendChild(card);
    });
}

displayClasses("Monday");
</script>

<?php include 'includes/footer.php'; ?>