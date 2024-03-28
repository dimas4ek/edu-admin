<?php
require_once 'admin.php';

function generateClassOptions()
{
    $classes = getClasses();
    foreach ($classes as $class) {
        echo '<option value="' . $class['class'] . '">' . $class['class'] . ' класс</option>';
    }
}

function generateTeacherOptions()
{
    $teachers = getTeachers();
    foreach ($teachers as $teacher) {
        echo '<option value="' . $teacher['name'] . '">' . $teacher['name'] . '</option>';
    }
}

function reports() {
    $youngest_first_grader = youngestFirstGrader();
    $count_second_graders = countSecondGraders();
    $students_by_teacher = countStudentsByTeacher();

    echo '
<table class="reports-table">
    <tr>
        <th class="parameter-header">Самый младший первоклассник</th>
        <th class="parameter-header">Количество второклассников</th>
        <th class="parameter-header">Количество учеников у каждого классного руководителя</th>
    </tr>
    <tr>';
    if($youngest_first_grader) {
        echo '<td class="parameter">' . $youngest_first_grader['first_name'] . ' ' . $youngest_first_grader['last_name'] . '</td>';
    } else {
        echo '<td class="parameter">Нет</td>';
    }
    echo '
        <td class="parameter">' . $count_second_graders . '</td>
        <td class="parameter">
            <table class="inner-table">';
                foreach ($students_by_teacher as $row) {
                    echo '<tr><td class="teacher-name">' . $row['name'] . '</td><td class="student-count">' . $row['count'] . ' учеников</td></tr>';
                }
                echo '
            </table>
        </td>
    </tr>
</table>';
}

function login() {
    session_start();
    if (!isset($_SESSION['username'])) {
        header('Location: actions/login.php');
    }
}

function logout() {
    if (isset($_SESSION['username'])) {
        echo 'Вы вошли как ' . $_SESSION['username'];
        echo '
<form action="actions/logout.php">
    <br><button type="submit">Выйти</button>
</form>';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление учениками и классами</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Управление учениками и классами</h1>
<hr>
<div class="container">
    <?php login(); ?>

    <div class="student-management">
        <h2>Добавление ученика</h2>
        <form action="actions/add_student.php" method="post">
            <label for="firstName">Фамилия:</label><br>
            <input type="text" id="firstName" name="firstName" required><br>
            <label for="lastName">Имя:</label><br>
            <input type="text" id="lastName" name="lastName" required><br>
            <label for="middleName">Отчество:</label><br>
            <input type="text" id="middleName" name="middleName"><br>
            <label for="dob">Дата рождения:</label><br>
            <input type="date" id="dob" name="dob" required><br>
            <label for="class">Класс:</label><br>
            <select name="class" id="class">
                <?php generateClassOptions(); ?>
            </select>
            <br>
            <br>
            <button type="submit">Добавить ученика</button>
        </form>

        <h2>Ученики в классе</h2>
        <form action="actions/students_by_class.php" method="get">
            <label for="classSelect">Выберите класс:</label>
            <select name="class" id="classSelect">
                <?php generateClassOptions(); ?>
            </select>
            <button type="submit">Показать учеников</button>
        </form>

        <h2>Назначить классного руководителя</h2>
        <form action="actions/assign_class_teacher.php" method="post">
            <select name="class" id="class">
                <?php generateClassOptions(); ?>
            </select>
            <select name="teacher" id="teacher">
                <?php generateTeacherOptions(); ?>
            </select>
            <button type="submit">Назначить</button>
        </form>

        <h2>Отчеты</h2>
        <?php reports(); ?>
    </div>

    <div class="divider"></div>

    <div class="admin-management">
        <div class="account">
            <h2>Аккаунт</h2>
            <?php logout()?>
        </div>

        <div class="audit">
            <h2>Журнал аудита</h2>
            <button type="button" onclick="window.location.href='actions/audit.php'">Показать</button>
        </div>
    </div>
</div>
</body>
</html>
