<?php
session_start();
if(!isset($_SESSION['user'])) header('Location: ../index.php');

include '../db_connect.php';
$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD']=='POST'){
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $dob = $_POST['dob'];
    $gender = $_POST['gender'] ?? '';
    $course = $_POST['course'] ?? '';
    $subjects = implode(',', $_POST['subjects'] ?? []);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    if($name && $email){
        $stmt = $conn->prepare("INSERT INTO students(name,email,dob,gender,course,subjects,phone,address) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->bind_param("ssssssss",$name,$email,$dob,$gender,$course,$subjects,$phone,$address);

        if($stmt->execute()) $success = "Student added successfully! <a href='view_students.php'>View Students</a>";
        else $error = "DB Error: ".$stmt->error;

        $stmt->close();
    } else $error = "Name and Email are required!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Student</title>
<link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>

<h2>Add Student</h2>

<?php 
if($error) echo "<p style='color:red;text-align:center;'>$error</p>";
if($success) echo "<p style='color:green;text-align:center;'>$success</p>";
?>

<form method="post">
    <label>Name:</label><br>
    <input type="text" name="name" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>DOB:</label><br>
    <input type="date" name="dob"><br><br>

    <label>Gender:</label><br>
    <input type="radio" name="gender" value="Male"> Male
    <input type="radio" name="gender" value="Female"> Female<br><br>

    <label>Course:</label><br>
    <select name="course">
        <option value="">Select</option>
        <option value="BCA">BCA</option>
        <option value="MCA">MCA</option>
        <option value="BSc Computer Science">BSc Computer Science</option>
        <option value="MSc Computer Science">MSc Computer Science</option>
        <option value="BBA">BBA</option>
        <option value="MBA">MBA</option>
    </select><br><br>

    <label>Subjects:</label><br>
    <input type="checkbox" name="subjects[]" value="DBMS"> DBMS
    <input type="checkbox" name="subjects[]" value="PHP"> PHP
    <input type="checkbox" name="subjects[]" value="C++"> C++
    <input type="checkbox" name="subjects[]" value="Java"> Java
    <input type="checkbox" name="subjects[]" value="Web"> Web<br><br>

    <label>Phone:</label><br>
    <input type="text" name="phone"><br><br>

    <label>Address:</label><br>
    <textarea name="address"></textarea><br><br>

    <input type="submit" value="Add Student">
</form>

</body>
</html>
