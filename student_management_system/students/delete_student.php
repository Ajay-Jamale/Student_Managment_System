<?php
session_start();
if(!isset($_SESSION['user'])) header('Location: ../index.php');

include '../db_connect.php';

// Get student ID from GET
$id = intval($_GET['id'] ?? 0);

if($id){
    // Use prepared statement for safety
    $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);

    if($stmt->execute()){
        $_SESSION['msg'] = "Student deleted successfully!";
    } else {
        $_SESSION['msg'] = "Error deleting student: " . $stmt->error;
    }
    $stmt->close();
}

header('Location: view_students.php');
exit();
