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

<div class="container">
    <?php
    session_start();
    if (!isset($_SESSION['username'])) {
        header('Location: actions/login.php');
    }
    ?>
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
        <ul>
            <li>Самый младший первоклассник: <a href="actions/youngest_first_grader.php">Показать</a></li>
            <li>Количество учеников во всех вторых классах: <a href="actions/count_second_graders.php">Показать</a></li>
            <li>Количество учеников у каждого классного руководителя: <a href="actions/students_by_teacher.php">Показать</a>
            </li>
        </ul>
    </div>

    <div class="admin-management">
        <div class="account">
            <h2>Аккаунт</h2>
            <?php
            if (isset($_SESSION['username'])) {
                echo 'Вы вошли как ' . $_SESSION['username'];
                echo '<form action="actions/logout.php">
                        <br><button type="submit">Выйти</button>
                    </form>';
            }
            ?>
        </div>

        <div class="audit">
            <h2>Журнал аудита</h2>
            <button type="button" onclick="window.location.href='actions/audit.php'">Показать</button>
        </div>
    </div>
</div>
</body>
</html>
