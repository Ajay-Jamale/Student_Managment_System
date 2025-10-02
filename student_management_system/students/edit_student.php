<?php
session_start();
if(!isset($_SESSION['user'])) header('Location: ../index.php');

include '../db_connect.php';

$id = intval($_GET['id'] ?? 0);
if(!$id) {
    header('Location: view_students.php');
    exit();
}

$error = '';
$success = '';

// Fetch existing student data
$stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
$stmt->close();

if(!$student){
    $_SESSION['msg'] = "Student not found!";
    header('Location: view_students.php');
    exit();
}

// Handle form submission
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $dob = $_POST['dob'];
    $gender = $_POST['gender'] ?? '';
    $course = $_POST['course'] ?? '';
    $subjects = implode(',', $_POST['subjects'] ?? []);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    if($name && $email){
        $stmt = $conn->prepare("UPDATE students SET name=?, email=?, dob=?, gender=?, course=?, subjects=?, phone=?, address=? WHERE id=?");
        $stmt->bind_param("ssssssssi",$name,$email,$dob,$gender,$course,$subjects,$phone,$address,$id);

        if($stmt->execute()) $success = "Student updated successfully! <a href='view_students.php'>View Students</a>";
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
<title>Edit Student</title>
<link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>

<h2>Edit Student</h2>

<?php 
if($error) echo "<p style='color:red;text-align:center;'>$error</p>";
if($success) echo "<p style='color:green;text-align:center;'>$success</p>";
?>

<form method="post">
    <label>Name:</label><br>
    <input type="text" name="name" value="<?php echo htmlspecialchars($student['name']); ?>" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" required><br><br>

    <label>DOB:</label><br>
    <input type="date" name="dob" value="<?php echo $student['dob']; ?>"><br><br>

    <label>Gender:</label><br>
    <input type="radio" name="gender" value="Male" <?php echo ($student['gender']=='Male')?'checked':''; ?>> Male
    <input type="radio" name="gender" value="Female" <?php echo ($student['gender']=='Female')?'checked':''; ?>> Female<br><br>

    <label>Course:</label><br>
    <select name="course">
        <option value="">Select</option>
        <?php
        $courses = ["BCA","MCA","BSc Computer Science","MSc Computer Science","BBA","MBA"];
        foreach($courses as $c){
            $sel = ($student['course']==$c)?'selected':'';
            echo "<option value='$c' $sel>$c</option>";
        }
        ?>
    </select><br><br>

    <label>Subjects:</label><br>
    <?php
    $allSubjects = ["DBMS","PHP","C++","Java","Web"];
    $studentSubjects = explode(',', $student['subjects']);
    foreach($allSubjects as $sub){
        $chk = in_array($sub,$studentSubjects)?'checked':'';
        echo "<input type='checkbox' name='subjects[]' value='$sub' $chk> $sub ";
    }
    ?>
    <br><br>

    <label>Phone:</label><br>
    <input type="text" name="phone" value="<?php echo htmlspecialchars($student['phone']); ?>"><br><br>

    <label>Address:</label><br>
    <textarea name="address"><?php echo htmlspecialchars($student['address']); ?></textarea><br><br>

    <input type="submit" value="Update Student">
</form>

</body>
</html>
