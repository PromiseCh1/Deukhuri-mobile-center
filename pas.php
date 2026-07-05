<?php
// create_admin.php
$password = '@dmin'; // change to your desired password
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Password hash: " . $hash . "\n";
?>