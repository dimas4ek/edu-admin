<?php
require_once 'admin.php';
require_once 'actions/login.php';
require_once 'actions/students_by_class.php';
require_once 'actions/audit.php';

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

function reports()
{
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
    if ($youngest_first_grader) {
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

function logout()
{
    if (isset($_SESSION['username'])) {
        echo 'Вы вошли как ' . $_SESSION['username'];
        echo '
<form action="actions/logout.php">
    <br><button type="submit">Выйти</button>
</form>';
    }
}

function getStudents()
{
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['class'])) {
        header('Location: ' . $_SERVER['PHP_SELF']);

        $class = $_GET['class'];
        $students = getStudentsByClass($class);

        $_SESSION['students'] = $students;
        $_SESSION['class'] = $class;
    }
}

function showAudit()
{
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['audit'])) {
        //header('Location: ' . $_SERVER['PHP_SELF']);
        $_SESSION['audit'] = true;
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
    <?php session_status() === PHP_SESSION_ACTIVE || session_start();
    if (isset($_SESSION['username'])): ?>
        <?php
        if (isset($_SESSION['students'])): studentsByClass($_SESSION['class'], $_SESSION['students']); ?>
        <?php else : ?>
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
                <form method="get">
                    <label for="classSelect">Выберите класс:</label>
                    <select name="class" id="classSelect">
                        <?php generateClassOptions(); ?>
                    </select>
                    <button type="submit">Показать учеников</button>
                </form>
                <?php getStudents(); ?>

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
        <?php endif; ?>

        <div class="divider"></div>

        <?php if (isset($_SESSION['audit'])): audit() ?>
        <?php else : ?>
            <div class="admin-management">
                <div class="account">
                    <h2>Аккаунт</h2>
                    <?php logout() ?>
                </div>

                <div class="audit">
                    <h2>Журнал аудита</h2>
                    <form method="get">
                        <input type="hidden" name="audit" value="1">
                        <button type="submit">Показать</button>
                    </form>
                    <?php showAudit(); ?>
                </div>
            </div>
        <?php endif; ?>

    <?php else: login(); ?>
    <?php endif; ?>
</div>
</body>
</html>
