<?php
//require_once '../admin.php';

function login()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        loginAccount($username, $password);
    }

    echo '<form method="post">
    <label for="username">Логин:</label>
    <input type="text" id="username" name="username">
    <br>
    <label for="password">Пароль:</label>
    <input type="password" id="password" name="password">
    <br>
    <button type="submit">Войти</button>
</form>';
}
?>
