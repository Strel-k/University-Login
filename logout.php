<?php
session_start();
session_destroy();
header("Location: create.php");
exit();
?>