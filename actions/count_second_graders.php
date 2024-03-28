<?php
require_once '../admin.php';

$count = countSecondGraders();

echo '<h2>Количество учеников во всех вторых классах:</h2>';
echo '<p>' . $count . '</p>';
?>
