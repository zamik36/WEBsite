<?php
// Настройки подключения к базе данных
$host = 'localhost';
$db = 'register';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Обработка редактирования пользователя
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit'])) {
    $id = $_POST['id'];
    $login = $_POST['login'];
    $name = $_POST['name'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("UPDATE users SET login = ?, password = ?, name = ? WHERE id = ?");
    $stmt->execute([$login, $password, $name, $id]);
}

// Очистка базы данных
if (isset($_POST['clear'])) {
    $pdo->exec("DELETE FROM users");
}

if (isset($_POST['clearUser'])) {
    $id = $_POST['id'];
    $pdo -> exec("DELETE FROM users WHERE id = $id");
}

// Получение всех пользователей
$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Пользователи</title>
    <link rel = "stylesheet" href = "styleForAdmin.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <header>
        <span><a href = "index.html">Kollinas</a></span>
    </header>

<form method="post">    
    <button type="submit" name="clear">Очистить базу данных</button>
</form>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Логин</th>
        <th>Пароль</th>
        <th>Имя</th>
        <th>Действия</th>
    </tr>
    <?php foreach ($users as $user): ?>
        <tr>
            <form method="post">
                <td><?php echo htmlspecialchars($user['id']); ?></td>
                <td><input type="text" name="login" value="<?php echo htmlspecialchars($user['login']); ?>" required></td>
                <td><input type="text" name="password"  ></td>
                <td><input type = "name" name = "name" value = "<?php echo htmlspecialchars($user['name']); ?>" required></td>
                <td>
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">
                    <div class = "submitbutton">
                        <button type="submit" name="edit">Сохранить</button>
                        <button type="submit" name="clearUser">Удалить пользователя</button>
                    </div>
                </td>
            </form>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
