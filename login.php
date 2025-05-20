<?php

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "luxury_car_website"; 


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);

    $sql = "SELECT * FROM admin_signup_info WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            
            session_start();
            $_SESSION['admin_username'] = $username;
            $_SESSION['admin_email'] = $row['email'];
            header("Location: dash.php");
            exit(); 
        } else {
            
            echo "<script>alert('Incorrect password. Please try again.');</script>";
        }
    } else {
        //FS
        echo "<script>alert('Username not found. Please try again.');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Car Admin Login</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      background-color: #0d0d0d;
      color: white;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .container {
      display: flex;
      width: 90%;
      max-width: 1000px;
      height: 600px;
      background-color: #1a1a1a;
      box-shadow: 0 0 15px rgba(255, 0, 0, 0.2);
      border-radius: 12px;
      overflow: hidden;
    }

    .left-panel {
      flex: 1;
      background-color: #111;
      display: flex;
      justify-content: center;
      align-items: center;
      animation: slideInLeft 1.2s ease forwards;
    }

    .left-panel img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .right-panel {
      flex: 1;
      padding: 40px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      background-color: #1a1a1a;
    }

    .login-box h2 {
      font-size: 2rem;
      color: red;
    }

    .login-box p {
      margin-bottom: 20px;
      color: #ccc;
    }

    .login-box form {
      display: flex;
      flex-direction: column;
    }

    .login-box label {
      margin-bottom: 5px;
      font-weight: bold;
    }

    .login-box input {
      margin-bottom: 20px;
      padding: 10px;
      border: none;
      border-radius: 5px;
      background-color: #333;
      color: white;
    }

    .forgot {
      text-align: right;
      margin-bottom: 15px;
    }

    .forgot a {
      color: #ff4d4d;
      font-size: 0.9rem;
    }

    button[type="submit"] {
      padding: 10px;
      background-color: red;
      color: white;
      border: none;
      font-weight: bold;
      border-radius: 5px;
      cursor: pointer;
      transition: background 0.3s;
    }

    button[type="submit"]:hover {
      background-color: #cc0000;
    }

    .signup {
      margin-top: 20px;
    }

    .signup span a {
      color: red;
      text-decoration: underline;
    }

    @keyframes slideInLeft {
      from {
        transform: translateX(-100%);
        opacity: 0;
      }
      to {
        transform: translateX(0);
        opacity: 1;
      }
    }

    @media screen and (max-width: 768px) {
      .container {
        flex-direction: column;
        height: auto;
      }

      .left-panel, .right-panel {
        flex: none;
        width: 100%;
        height: auto;
      }

      .left-panel img {
        height: 250px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="left-panel">
      <img src="back car1.avif" alt="Car Illustration" />
    </div>
    <div class="right-panel">
      <div class="login-box">
        <h2>Welcome back</h2>
        <p>Please login to your account</p>
        <form method="POST">
          <label>Username</label>
          <input type="text" name="username" placeholder="Enter your username" required />

          <label>Password</label>
          <input type="password" name="password" placeholder="Enter your password" required />

          <div class="forgot">
            <a href="#">Forgot?</a>
          </div>
          <button type="submit">Login</button>
        </form>
        <div class="signup">
          <span>Don't have an account? <a href="signup.php">Sign up</a></span>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
