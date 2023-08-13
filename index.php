<?php

session_start(); 
$auth = $_SESSION['auth'] ?? null;

ini_set('display_errors', 1);
require_once 'application/bootstrap.php';

?>
