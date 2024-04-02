<?php
session_start();
if(isset($_SESSION['audit'])) {
    unset($_SESSION['audit']);
    header('Location: ../index.php');
    exit();
}