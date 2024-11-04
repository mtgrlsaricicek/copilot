<?php
session_start();

// Veritabanı bağlantısı
$servername = "localhost:3306";
$username = "root";
$password = "1234";
$dbname = "aigenerated_analysis";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

// Kullanıcı giriş kontrolü
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userCode = $_POST["user_code"];
    $password = $_POST["password"];

    // SQL injection önlemi
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_code = ? AND password = ?");
    $stmt->bind_param("ss", $userCode, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION["loggedin"] = true;
        $_SESSION["user_code"] = $userCode;
        echo "Başarıyla giriş yaptınız!";
    } else {
        echo "Geçersiz kullanıcı kodu veya şifre.";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Giriş Yap</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="user_code">Kullanıcı Kodu:</label>
        <input type="text" id="user_code" name="user_code" required><br><br>
        <label for="password">Şifre:</label>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="Giriş Yap">
    </form>
</body>
</html>
