<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'auth_system');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if(isset($_POST['btn']))
{
    $username=$_POST['un'];
    $pswd=$_POST['ps'];
    $qu="Select * from where uname='$username' and pass='$pswd'";
    $result=mysqli_query($con,$qu);
    if(mysqli_num_query($result)==1)
    {
        $user=mysqli_fetch_assoc($result);
        $_SESSION['uname']=$user[['uname']];
        header("Location:csdash.php");
    
    }
    else {
        echo"<script>alert('Invalid Credentials...')</script>";

    }

}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name+"viewport" content="width=device-width", initial-scale=1.0">
  <title>Document</title>
</head>
<body>
    <form action='' method='POST'>
        <input type='text' name='un' placeholder "User name" required >
        <input type='text' name='ps' placeholder "Password" required >
        <button name ="btn">Login</button>
</body>
</html>
