<?php
session_start();
session_destroy();
header("location:".__DIR__."/login.php");
?>
