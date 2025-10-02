<?php
session_start();
if(!isset($_SESSION['user'])) header('Location: index.php');
include 'db_connect.php';

// Quick stats
$total_students = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM students"))['total'];
$total_attendance_today = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM attendance WHERE attendance_date='".date('Y-m-d')."'"))['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard</title>
<link rel="stylesheet" href="assets/css/styles.css">
<style>
body {
    font-family: Arial, Helvetica, sans-serif;
    margin: 0;
    background-color: #f4f6f9;
}
header {
    background-color: #4CAF50;
    color: white;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
header h1 {
    margin: 0;
    font-size: 24px;
}
header a.logout {
    background-color: #fff;
    color: #4CAF50;
    padding: 8px 15px;
    text-decoration: none;
    border-radius: 5px;
    font-weight: bold;
}
header a.logout:hover {
    background-color: #ddd;
}
.container {
    max-width: 1200px;
    margin: 30px auto;
    padding: 0 20px;
}
.welcome {
    text-align: center;
    margin-bottom: 30px;
}
.grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
}
.card {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.1);
    text-align: center;
    transition: transform 0.2s;
}
.card:hover {
    transform: translateY(-5px);
}
.card h3 {
    margin: 0 0 10px;
    color: #333;
}
.card p {
    font-size: 18px;
    color: #555;
}
.card a {
    display: inline-block;
    margin-top: 10px;
    padding: 8px 15px;
    background-color: #4CAF50;
    color: white;
    text-decoration: none;
    border-radius: 5px;
}
.card a:hover {
    background-color: #45a049;
}
@media(max-width:768px){
    header h1 {
        font-size: 20px;
    }
}
</style>
</head>
<body>

<header>
    <h1>Student Management Dashboard</h1>
    <a href="logout.php" class="logout">Logout</a>
</header>

<div class="container">
    <div class="welcome">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?></h2>
        <p>Manage students, attendance, and reports efficiently</p>
    </div>

    <div class="grid">
        <div class="card">
            <h3>Total Students</h3>
            <p><?php echo $total_students; ?></p>
            <a href="students/view_students.php">View Students</a>
        </div>

        <div class="card">
            <h3>Mark Attendance</h3>
            <p><?php echo date('d-M-Y'); ?></p>
            <a href="attendance/mark_attendance.php">Mark Now</a>
        </div>

        <div class="card">
            <h3>Attendance Today</h3>
            <p><?php echo $total_attendance_today; ?></p>
            <a href="attendance/view_attendance.php?date=<?php echo date('Y-m-d'); ?>">View</a>
        </div>

        <div class="card">
            <h3>Attendance Report</h3>
            <p>Full summary</p>
            <a href="attendance/attendance_report.php">View Report</a>
        </div>
    </div>
</div>

</body>
</html>
