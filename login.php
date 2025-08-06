<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>JIMACA Crew Login</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Arial', sans-serif;
    }

    body {
      background-image: url('bg.png');
      background-size: cover;
      background-position: center;
      background-color: #111;
      color: white;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .overlay {
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 100%;
      z-index: 1;
    }

    .login-container {
      position: relative;
      z-index: 2;
      background: rgba(34, 34, 34, 0.8);
      padding: 40px 30px;
      border-radius: 15px;
      box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
      max-width: 400px;
      width: 100%;
      text-align: center;
    }

    h2 {
      margin-bottom: 20px;
      font-size: 2em;
    }

    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border: none;
      border-radius: 5px;
      background: #222;
      color: white;
    }

    .button-link,
    button {
      margin-top: 15px;
      padding: 10px 20px;
      background-color: #333;
      color: white;
      border: none;
      border-radius: 10px;
      font-weight: bold;
      cursor: pointer;
      text-decoration: none;
      display: inline-block;
      transition: background-color 0.3s ease;
    }

    .button-link:hover,
    button:hover {
      background-color: #555;
    }

    .back-home {
      display: block;
      margin-top: 20px;
      font-size: 0.95em;
    }
  </style>
</head>
<body>
  <div class="overlay"></div>

  <div class="login-container">
    <img src="logo2-removebg-preview.png" alt="JIMACA Logo" style="width: 80px; margin-bottom: 15px;">
    <h2>JIMACA Crew Login</h2>

    <form action="login_process.php" method="POST">
      <input type="text" name="username" placeholder="Username" required />
      <input type="password" name="password" placeholder="Password" required />
      <button type="submit">Login</button>
    </form>

    <a href="Home.html" class="button-link back-home">← Back to Homepage</a>
  </div>
</body>
</html>
