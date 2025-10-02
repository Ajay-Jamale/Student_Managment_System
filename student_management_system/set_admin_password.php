<?php
include 'db_connect.php';

// Change this to the password you want
$new_password = 'Admin123';

// Generate bcrypt hash
$hash = password_hash($new_password, PASSWORD_BCRYPT);

// Update admin password
mysqli_query($conn,"UPDATE users SET password='$hash' WHERE username='admin'");

echo "Admin password has been reset to: $new_password";
