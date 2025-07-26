<!-- php/logout.php: Logout Handler -->
<?php
session_start();
session_unset();
session_destroy();
header("Location: ../index.php");
exit();
?>