<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'auth_system');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['btn'])) {
    $username = $conn->real_escape_string($_POST['un']);
    $pswd = $conn->real_escape_string($_POST['ps']);
    
    $qu = "SELECT * FROM users WHERE uname='$username' AND pass='$pswd'";
    $result = mysqli_query($conn, $qu);
    
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['uname'] = $user['uname'];
        header("Location: csdash.php");
        exit();
    } else {
        echo "<script>alert('Invalid Credentials...')</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            background: linear-gradient(135deg, #2980b9, #6dd5fa, #ffffff);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            height: 100vh;
            justify-content: center;
            align-items: center;
            margin: 0;
        }
        .login-box {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 350px;
        }
        .login-box h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }
        .login-box input[type="text"],
        .login-box input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
        }
        .login-box button {
            width: 100%;
            padding: 12px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .login-box button:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Login</h2>
        <form action="" method="POST">
            <input type="text" name="un" placeholder="Username" required>
            <input type="password" name="ps" placeholder="Password" required>
            <button name="btn">Login</button>
        </form>
    </div>
</body>
</html>
