<?php
    session_start();
    if(!isset($_SESSION['user'])) header('Location: ../index.php');

    include '../db_connect.php';

    // Query to get attendance summary per student
    $query = "SELECT s.name,
            COUNT(CASE WHEN a.status='P' THEN 1 END) AS present,
            COUNT(CASE WHEN a.status='A' THEN 1 END) AS absent,
            COUNT(CASE WHEN a.status='L' THEN 1 END) AS `leave`
            FROM students s
            LEFT JOIN attendance a ON s.id = a.student_id
            GROUP BY s.id";

    $res = mysqli_query($conn, $query);

    if (!$res) {
        die("Query Failed: " . mysqli_error($conn));
    }
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Attendance Report</title>
        <link rel="stylesheet" href="../assets/css/styles.css">
    </head>

    <body>
        <h2>Student Attendance Report</h2>

        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Present</th>
                    <th>Absent</th>
                    <th>Leave</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($res)): ?>
                <tr>
                    <td data-label="Name"><?php echo htmlspecialchars($row['name']); ?></td>
                    <td data-label="Present"><?php echo $row['present']; ?></td>
                    <td data-label="Absent"><?php echo $row['absent']; ?></td>
                    <td data-label="Leave"><?php echo $row['leave']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </body>
</html>
