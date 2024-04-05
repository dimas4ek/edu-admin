<?php

use tests\AppTest;

require_once 'AppTest.php';

$test = new AppTest();
$test->setUp();
$test->testAddStudent();
$test->tearDown();
echo "\nTests passed successfully.";