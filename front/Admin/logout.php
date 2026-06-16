<?php
// Admin logout
session_start();
$_SESSION = [];
session_destroy();
header('Location: adminportal.php');
exit;
