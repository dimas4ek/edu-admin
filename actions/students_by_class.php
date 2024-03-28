<?php
require_once '../admin.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['class'])) {
    $class = $_GET['class'];

    $students = getStudentsByClass($class);

    echo '<h2>Ученики в ' . $class . ' классе:</h2>';
    if (count($students) > 0) {
        echo '<ul>';
        foreach ($students as $student) {
            echo '<li>' . $student['last_name'] . ' ' . $student['first_name'] . ' ' . $student['middle_name'] . '</li>'; // Вот здесь была пропущена точка с запятой
            echo '<form action="delete_student.php" method="post">
                    <input type="hidden" name="id" value="' . $student['id'] . '">
                    <button type="submit">Удалить</button>
                  </form>';
        }
        echo '</ul>';
    } else {
        echo 'В ' . $class . ' классе нет учеников';
    }
} else {
    echo 'Доступ запрещен';
}
?>
