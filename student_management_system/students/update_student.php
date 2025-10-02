<?php
session_start();
if(!isset($_SESSION['user'])) header('Location: ../index.php');

include '../db_connect.php';

$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $id = intval($_POST['id'] ?? 0);
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $dob = $_POST['dob'];
    $gender = $_POST['gender'] ?? '';
    $course = $_POST['course'] ?? '';
    $subjects = implode(',', $_POST['subjects'] ?? []);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    if($id && $name && $email){
        $stmt = $conn->prepare("UPDATE students SET name=?, email=?, dob=?, gender=?, course=?, subjects=?, phone=?, address=? WHERE id=?");
        $stmt->bind_param("ssssssssi",$name,$email,$dob,$gender,$course,$subjects,$phone,$address,$id);

        if($stmt->execute()){
            $_SESSION['msg'] = "Student updated successfully!";
        } else {
            $_SESSION['msg'] = "DB Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $_SESSION['msg'] = "ID, Name and Email are required!";
    }

    header("Location: view_students.php");
    exit();
}
