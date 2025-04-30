<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'auth_system';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $check->bind_param("s", $username);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $message = "Username already exists!";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $password);

        if ($stmt->execute()) {
            $message = "User registered successfully!";
        } else {
            $message = "Something went wrong. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <style>
        body {
            background: linear-gradient(135deg, #9b59b6, #74b9ff, #ffffff);
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            height: 100vh;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .signup-box {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            width: 350px;
        }
        .signup-box h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .signup-box input[type="text"],
        .signup-box input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
        }
        .signup-box button {
            width: 100%;
            padding: 12px;
            background-color: #6c5ce7;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        .signup-box button:hover {
            background-color: #5a4acb;
        }
        .signup-box p {
            text-align: center;
            color: green;
            font-weight: 500;
        }
        .signup-box a {
            display: block;
            text-align: center;
            margin-top: 10px;
            color: #0984e3;
            text-decoration: none;
        }
        .signup-box a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="signup-box">
        <h2>Create an Account</h2>
        <form method="POST" action="">
            <input type="text" name="username" required placeholder="Username">
            <input type="password" name="password" required placeholder="Password">
            <button type="submit">Sign Up</button>
        </form>
        <p><?php echo htmlspecialchars($message); ?></p>
        <a href="login.php">Already have an account? Login</a>
    </div>
</body>
</html>
