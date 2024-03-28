<?php
require_once '../admin.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $class = $_POST['class'];
    $teacher = $_POST['teacher'];
    assignClassTeacher($class, $teacher);

    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
} else {
    echo 'Доступ запрещен';
}
?>