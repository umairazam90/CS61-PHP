<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'auth_system';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $username;
            header("Location: welcome.php");
            exit;
        } else {
            $message = "Invalid password.";
        }
    } else {
        $message = "User not found.";
    }
}
}
?>

<h2>Login</h2>
<form method="POST" action="">
    <input type="text" name="username" required placeholder="Username"><br>
    <input type="password" name="password" required placeholder="Password"><br>
    <button type="submit">Login</button>
</form>
<a href="index.php">Don't have an account? Sign Up</a>
