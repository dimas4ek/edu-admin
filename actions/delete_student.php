<?php
require_once '../admin.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentId = $_POST['id'];

    deleteStudent($studentId);

    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
} else {
    echo 'Доступ запрещен';
}
?>