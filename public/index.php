<?php
require_once(__DIR__.'/../core/Application.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <html>
<head>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header {
            background: #1a1a2e;
            color: white;
            padding: 20px 30px;
            position: relative;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .user-name {
            position: absolute;
            top: 20px;
            right: 30px;
            font-size: 18px;
            font-weight: bold;
            background: #16213e;
            padding: 5px 15px;
            border-radius: 20px;
        }

        .header h1 {
            font-size: 24px;
        }

        .content {
            flex: 1;
            padding: 40px;
            background: #f0f0f0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📋 Тестовая страница</h1>
        <div class="user-name">
            <?= $name?>
        </div>
    </div>
</html>
</body>
</html>