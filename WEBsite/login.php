<?php
 

    $login = filter_var(trim($_POST['login']), FILTER_SANITIZE_STRING);
    $pass = $_POST['password'];


    // Подключение к базе данных
    $mysql = new mysqli('localhost', 'root', '', 'register');

    // Проверяем подключение
    if ($mysql->connect_error) {
        die("Ошибка подключения: " . $mysql->connect_error);
    }

    // Подготовленный запрос для поиска пользователя по логинуE
    $stmt = $mysql->prepare("SELECT * FROM Users WHERE login = ?");
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    echo "$pass";

    if (!$user || !password_verify($pass, $user['password'])) {
        echo "Неверный логин или пароль!";
        exit();
    }

    // Устанавливаем cookie для авторизации
    setcookie('user', $user['name'], time() + 3600, "/");

    $stmt->close();
    $mysql->close();

    // Редирект на главную страницу
    header('Location: index.html');
    exit();
?>