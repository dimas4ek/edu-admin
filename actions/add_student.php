<?php
require_once '../admin.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $middleName = $_POST['middleName'];
    $dob = $_POST['dob'];
    $class = $_POST['class'];

    addStudent($firstName, $lastName, $middleName, $class, $dob);

    header('Location: ../index.php');
    exit();
} else {
    echo 'Доступ запрещен';
}
?>
