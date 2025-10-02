<?php
session_start();
if(!isset($_SESSION['user'])) header('Location: ../index.php');

include '../db_connect.php';

$date = $_GET['date'] ?? date('Y-m-d');

// Fetch all students and their attendance for the selected date
$query = "SELECT s.id, s.name, COALESCE(a.status, '-') as status
          FROM students s
          LEFT JOIN attendance a 
          ON s.id = a.student_id AND a.attendance_date = ?
          ORDER BY s.name ASC";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $date);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Attendance</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>

<h2>View Attendance for <?php echo date('d-M-Y', strtotime($date)); ?></h2>

<form method="get" action="">
    <div style="text-align:center;margin-bottom:15px;">
        <label for="date">Select Date: </label>
        <input type="date" name="date" id="date" value="<?php echo $date; ?>">
        <input type="submit" value="View">
    </div>
</form>

<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td data-label="Name"><?php echo htmlspecialchars($row['name']); ?></td>
            <td data-label="Status">
                <?php
                    switch($row['status']){
                        case 'P': echo 'Present'; break;
                        case 'A': echo 'Absent'; break;
                        case 'L': echo 'Leave'; break;
                        default: echo '-'; break;
                    }
                ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>
