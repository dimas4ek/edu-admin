<?php
require_once 'db.php';
require_once 'admin_actions.php';

function getClasses()
{
    global $pdo;
    $stmt = $pdo->query('SELECT class FROM classes');
    return $stmt->fetchAll();
}

function getTeachers()
{
    global $pdo;
    $stmt = $pdo->query('SELECT name FROM teachers');
    return $stmt->fetchAll();
}

function assignClassTeacher($class, $teacher)
{
    global $pdo;

    $stmt = $pdo->prepare('select t.name from teachers t join classes c on t.id = c.teacher_id where c.id = ?');
    $stmt->execute([$class]);
    $old_teacher = $stmt->fetchColumn();

    $stmt = $pdo->prepare('UPDATE classes SET teacher_id = (select id from teachers where name = ?) WHERE class = ?');
    $stmt->execute([$teacher, $class]);

    session_start();
    $stmt = $pdo->prepare('insert into audit (admin_id, action, timestamp) values ((select id from admins where username = ?), ?, ?)');
    $stmt->execute([$_SESSION['username'], Action::AssignClassTeacher->value, date('Y-m-d H:i:s')]);

    $audit_log_id = $pdo->lastInsertId();
    $stmt = $pdo->prepare('INSERT INTO audit_details (audit_log_id, old_value, new_value) VALUES (?, ?, ?)');
    $stmt->execute([$audit_log_id, $class . ' ' . $old_teacher, $class . ' ' . $teacher]);
}

function addStudent($firstName, $lastName, $middleName, $class, $dob)
{
    global $pdo;
    $stmt = $pdo->prepare('INSERT INTO students (first_name, last_name, middle_name, class_id, dob) VALUES (?, ?, ?, (SELECT id FROM classes WHERE class = ?), ?)');
    $stmt->execute([$firstName, $lastName, $middleName, $class, $dob]);

    session_start();
    $stmt = $pdo->prepare('insert into audit (admin_id, action, timestamp) values ((select id from admins where username = ?), ?, ?)');
    $stmt->execute([$_SESSION['username'], Action::AddStudent->value, date('Y-m-d H:i:s')]);

    $audit_log_id = $pdo->lastInsertId();

    $stmt_audit_details = $pdo->prepare('INSERT INTO audit_details (audit_log_id, new_value) VALUES (?, ?)');
    $stmt_audit_details->execute([$audit_log_id, $firstName . ' ' . $lastName . ' ' . $middleName . ' ' . $class . ' ' . $dob]);
}

function getStudentsByClass($class)
{
    global $pdo;
    $stmt = $pdo->prepare('SELECT students.* FROM students join classes on students.class_id = classes.id where classes.class = ?');
    $stmt->execute([$class]);
    return $stmt->fetchAll();
}

function deleteStudent($studentId)
{
    global $pdo;

    $deleted_student = getStudentById($studentId);

    $stmt = $pdo->prepare('DELETE FROM students WHERE id = ?');
    $stmt->execute([$studentId]);

    session_start();
    $stmt = $pdo->prepare('insert into audit (admin_id, action, timestamp) values ((select id from admins where username = ?), ?, ?)');
    $stmt->execute([$_SESSION['username'], Action::DeleteStudent->value, date('Y-m-d H:i:s')]);

    $audit_log_id = $pdo->lastInsertId();
    $stmt = $pdo->prepare('INSERT INTO audit_details (audit_log_id, old_value, new_value) VALUES (?, ?, ?)');
    $stmt->execute([$audit_log_id, $deleted_student, 'deleted student']);
}

function getStudentById($studentId)
{
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM students WHERE id = ?');
    $stmt->execute([$studentId]);
    $student =  $stmt->fetch();
    return $student['first_name'] . ' ' . $student['last_name'] . ' ' . $student['middle_name'] . ' ' . $student['class_id'] . ' ' . $student['dob'];
}

function youngestFirstGrader()
{
    global $pdo;
    $stmt = $pdo->query('SELECT * FROM students WHERE class_id = (SELECT id FROM classes WHERE class = 1) ORDER BY dob LIMIT 1');
    return $stmt->fetch();
}

function countSecondGraders()
{
    global $pdo;
    $stmt = $pdo->query('SELECT COUNT(*) AS count FROM students WHERE class_id = (select id from classes where class = 2)');
    return $stmt->fetch()['count'];
}

function countStudentsByTeacher()
{
    global $pdo;
    $stmt = $pdo->query('
                        SELECT t.name, count(s.id) as count 
                        from teachers t 
                        left join classes c on t.id  = c.teacher_id 
                        left join students s on c.id = s.class_id 
                        group by t.id
                        ');
    return $stmt->fetchAll();
}

function loginAccount($username, $password)
{
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM admins WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && $user['password'] === $password) {
        session_start();
        $_SESSION['username'] = $username;
        header('Location: ../index.php');
        exit();
    } else {
        echo "Неправильный логин или пароль";
    }
}

function getAudit()
{
    global $pdo;
    $stmt = $pdo->query('SELECT * FROM audit');
    return $stmt->fetchAll();
}

function getAuditDetailsByLogId($id)
{
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM audit_details WHERE audit_log_id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function getAdminById($id)
{
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM admins WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch();
}
?>