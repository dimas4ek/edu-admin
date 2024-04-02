<?php

function studentsByClass($class, $students)
{
    echo '<div class="students"><div>
<form action="actions/back_students.php">
    <button type="submit">Назад</button>
</form></div>';
    echo '<div><h2>Ученики в ' . $class . ' классе:</h2></div>';
    if (count($students) > 0) {
        echo '<div><ul>';
        foreach ($students as $student) {
            echo '<li>' . $student['last_name'] . ' ' . $student['first_name'] . ' ' . $student['middle_name'] . '</li>';
            echo '<form action="delete_student.php" method="post">
                    <input type="hidden" name="id" value="' . $student['id'] . '">
                    <button type="submit">Удалить</button>
                  </form>';
        }
        echo '</ul></div></div>';
    } else {
        echo '<div>В ' . $class . ' классе нет учеников</div></div>';
    }
}
?>
