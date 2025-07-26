<!-- php/db_connect.php: Updated Database Connection (Replace Existing) -->
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "online_nursery";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
