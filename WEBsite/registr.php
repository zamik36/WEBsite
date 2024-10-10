<?php
    session_start();


    $login = filter_var(trim($_POST['login']), FILTER_SANITIZE_STRING);
    $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
    $pass = $_POST['password'];


    // Проверка длины логина и имени
    if(mb_strlen($login) < 5 || mb_strlen($login) > 90) {
        echo "Недопустимая длина логина!";
        exit();
    } else if(mb_strlen($name) < 3 || mb_strlen($name) > 50) {
        echo "Недопустимая длина имени!";
        exit();
    }

    $pass = password_hash($pass, PASSWORD_DEFAULT);

    // Подключение к базе данных
    $mysql = new mysqli('localhost', 'root', '', 'register');

    // Проверяем подключение
    if ($mysql->connect_error) {
        die("Ошибка подключения: " . $mysql->connect_error);
    }

    // Подготовленный запрос для безопасного сохранения данных
    $stmt = $mysql->prepare("INSERT INTO Users (login, password, name) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $login, $pass, $name);
    $stmt->execute();

    setcookie('user', $user['name'], time() + 3600, "/");

    // Закрываем запрос и соединение
    $stmt->close();
    $mysql->close();

    echo "Регистрация успешна!";

    header('Location: index.html');
?>