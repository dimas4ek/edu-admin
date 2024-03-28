<?php
require_once '../admin.php';

$students = countStudentsByTeacher();

echo '<h2>Количество учеников у каждого классного руководителя:</h2>';
echo '<ul>';
foreach ($students as $row) {
    echo '<li>' . $row['name'] . ': ' . $row['count'] . ' учеников</li>';
}
echo '</ul>';
?>
