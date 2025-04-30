<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'auth_system';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);

    if ($stmt->execute()) {
        $message = "User registered successfully!";
    } else {
        $message = "Username already exists!";
    }
}
?>

<h2>Sign Up</h2>
<form method="POST" action="">
    <input type="text" name="username" required placeholder="Username"><br>
    <input type="password" name="password" required placeholder="Password"><br>
    <button type="submit">Sign Up</button>
</form>
<p><?php echo $message; ?></p>
<a href="login.php">Already have an account? Login</a>