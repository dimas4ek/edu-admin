<?php

namespace tests;

use Action;
use PDO;

require_once '../admin_actions.php';

class AppTest
{

    private $pdo;

    public function setUp(): void
    {
        $data = parse_ini_file('../data.ini');

        $host = $data['host'];
        $db = $data['db'];
        $user = $data['user'];
        $pass = $data['pass'];
        $charset = $data['charset'];

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
            echo "Database connected successfully\n";
        } catch (\PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public function testAddStudent(): void
    {
        echo "ADD\n";

        $_POST['firstName'] = 'John';
        $_POST['lastName'] = 'Doe';
        $_POST['middleName'] = 'Smith';
        $_POST['dob'] = '2000-01-01';
        $_POST['class'] = 1;

        $this->addStudent($_POST['firstName'], $_POST['lastName'], $_POST['middleName'], $_POST['class'], $_POST['dob']);

        $stmt = $this->pdo->prepare('SELECT * FROM students WHERE first_name = ? AND last_name = ? AND middle_name = ? AND dob = ?');
        $stmt->execute([$_POST['firstName'], $_POST['lastName'], $_POST['middleName'], $_POST['dob']]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);
        assert($student !== false);
        echo "Student added successfully\n";

        $stmt = $this->pdo->prepare('SELECT * FROM audit WHERE admin_id = (SELECT id FROM admins WHERE username = ?) AND action = ?');
        $stmt->execute(['admin', 'add_student']);
        $auditLog = $stmt->fetch(PDO::FETCH_ASSOC);
        assert($auditLog !== false);
        echo "Audit log added successfully\n";

        $stmt = $this->pdo->prepare('SELECT * FROM audit_details WHERE audit_log_id = ? AND new_value = ?');
        $stmt->execute([$auditLog['id'], $_POST['firstName'] . ' ' . $_POST['lastName'] . ' ' . $_POST['middleName'] . ' ' . $_POST['class'] . ' ' . $_POST['dob']]);
        $auditDetails = $stmt->fetch(PDO::FETCH_ASSOC);
        assert($auditDetails !== false);
        echo "Audit details added successfully\n";
    }

    public function tearDown(): void
    {
        echo "DELETE\n";

        $stmt = $this->pdo->prepare('DELETE FROM students WHERE first_name = ? AND last_name = ? AND middle_name = ? AND dob = ?');
        $stmt->execute(['John', 'Doe', 'Smith', '2000-01-01']);
        echo "Student deleted successfully\n";

        $stmt = $this->pdo->prepare('DELETE FROM audit WHERE action = ?');
        $stmt->execute(['add_student']);
        echo "Audit log deleted successfully\n";

        $stmt = $this->pdo->prepare('DELETE FROM audit_details WHERE new_value = ?');
        $stmt->execute(['John Doe Smith 2000-01-01']);
        echo "Audit details deleted successfully\n";
    }

    function addStudent($firstName, $lastName, $middleName, $class, $dob): void
    {
        $stmt = $this->pdo->prepare('INSERT INTO students (first_name, last_name, middle_name, class_id, dob) VALUES (?, ?, ?, (SELECT id FROM classes WHERE class = ?), ?)');
        $stmt->execute([$firstName, $lastName, $middleName, $class, $dob]);

        $stmt = $this->pdo->prepare('insert into audit (admin_id, action, timestamp) values ((select id from admins where username = ?), ?, ?)');
        $stmt->execute(['admin', Action::AddStudent->value, date('Y-m-d H:i:s')]);

        $audit_log_id = $this->pdo->lastInsertId();

        $stmt_audit_details = $this->pdo->prepare('INSERT INTO audit_details (audit_log_id, new_value) VALUES (?, ?)');
        $stmt_audit_details->execute([$audit_log_id, $firstName . ' ' . $lastName . ' ' . $middleName . ' ' . $class . ' ' . $dob]);
    }
}

