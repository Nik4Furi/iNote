<?php
$server = 'localhost';
$root = 'root';
$password = '123456';
$database = 'harry';

// Connected
$conn = mysqli_connect($server,$root,$password,$database);

if (!$conn) {
    die("Connection Fatel Error");
}
// else{
//     echo "You connected";
// }

?>