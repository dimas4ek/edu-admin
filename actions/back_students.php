<?php
session_start();

if(isset($_SESSION['students'])) {
    unset($_SESSION['students']);
    unset($_SESSION['class']);
    header('Location: ../index.php');
    exit();
}