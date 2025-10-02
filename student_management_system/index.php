<?php
session_start();
include 'db_connect.php';
$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if($username && $password){
        $stmt = $conn->prepare("SELECT * FROM users WHERE username=? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $res = $stmt->get_result();

        if($res->num_rows == 1){
            $row = $res->fetch_assoc();
            if(password_verify($password, $row['password'])){
                $_SESSION['user'] = $row['username'];
                header('Location: dashboard.php'); 
                exit;
            } else $error = "Invalid password";
        } else $error = "User not found";

        $stmt->close();
    } else {
        $error = "Enter username and password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>
<link rel="stylesheet" href="assets/css/styles.css">
<style>
body {
    font-family: Arial, sans-serif;
    background-color: #f4f6f9;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}
.login-box {
    background-color: #fff;
    padding: 30px 40px;
    border-radius: 8px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    width: 100%;
    max-width: 400px;
    text-align: center;
}
.login-box h2 {
    margin-bottom: 25px;
    color: #333;
}
.login-box input[type="text"],
.login-box input[type="password"] {
    width: 90%;
    padding: 12px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
}
.login-box input[type="submit"] {
    width: 95%;
    padding: 12px;
    border: none;
    background-color: #4CAF50;
    color: white;
    font-size: 16px;
    border-radius: 5px;
    cursor: pointer;
}
.login-box input[type="submit"]:hover {
    background-color: #45a049;
}
.error {
    color: red;
    margin-bottom: 15px;
}
</style>
</head>
<body>

<div class="login-box">
    <h2>Login</h2>
    <?php if($error) echo "<p class='error'>$error</p>"; ?>
    <form method="post">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <input type="submit" value="Login">
    </form>
</div>

</body>
</html>
