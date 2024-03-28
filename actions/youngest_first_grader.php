<?php
require_once '../admin.php';

$student = youngestFirstGrader();

if ($student == null) {
    echo '<h2>Нет первоклассников</h2>';
} else {
    echo '<h2>Самый младший первоклассник:</h2>';
    echo '<p>' . $student['last_name'] . ' ' . $student['first_name'] . ' ' . $student['middle_name'] . '</p>';
}
?>
