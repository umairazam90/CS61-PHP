-<?php
echo'moavia is B and C';
$a=5;
$b=6;
$c=$a+$b;
echo'sum of two numbers:'.$c;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple PHP Website</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 50px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to My Simple PHP Website</h1>
        <p>The current server time is: <strong><?php echo date('Y-m-d H:i:s'); ?></strong></p>
    </div>
</body>
</html>
