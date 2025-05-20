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
   
    $full_name = mysqli_real_escape_string($conn, $_POST["full_name"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);
    $admin_code = mysqli_real_escape_string($conn, $_POST["admin_code"]);

    
    $valid_code = "ADMIN2025"; 
    if ($admin_code != $valid_code) {
        echo "<script>alert('Invalid Admin Code. Please try again.');</script>";
    } else {
        
        $sql_check = "SELECT * FROM admin_signup_info WHERE username='$username'";
        $result = $conn->query($sql_check);
        if ($result->num_rows > 0) {
            echo "<script>alert('Username already taken. Please choose a different username.');</script>";
        } else {
            
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            
            $sql = "INSERT INTO admin_signup_info (full_name, email, username, password, admin_code)
                    VALUES ('$full_name', '$email', '$username', '$hashed_password', '$admin_code')";

            if ($conn->query($sql) === TRUE) {
                //FS
                echo "<script>alert('Signup successful! You can now login.'); window.location.href='login.php';</script>";
            } else {
                echo "<script>alert('Error: " . $conn->error . "');</script>";
            }
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sign Up</title>
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
      height: auto;
      background-color: #1a1a1a;
      border-radius: 12px;
      box-shadow: 0 0 15px rgba(255, 0, 0, 0.2);
      overflow: hidden;
    }

    .left-panel {
      flex: 1;
      background-color: #111;
      display: flex;
      justify-content: center;
      align-items: center;
      animation: zoomIn 1.2s ease forwards;
    }

    .left-panel img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .right-panel {
      flex: 1;
      padding: 40px;
      background-color: #1a1a1a;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .signup-box h2 {
      font-size: 2rem;
      color: red;
    }

    .signup-box p {
      margin-bottom: 20px;
      color: #ccc;
    }

    .signup-box form {
      display: flex;
      flex-direction: column;
    }

    .signup-box label {
      margin-bottom: 5px;
      font-weight: bold;
    }

    .signup-box input {
      margin-bottom: 20px;
      padding: 10px;
      border: none;
      border-radius: 5px;
      background-color: #333;
      color: white;
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

    .login-link {
      margin-top: 20px;
    }

    .login-link span a {
      color: red;
      text-decoration: underline;
    }

    @keyframes zoomIn {
      from {
        transform: scale(0.8);
        opacity: 0;
      }
      to {
        transform: scale(1);
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
      <img src="sign up.jpeg" alt="Car Image">
    </div>
    <div class="right-panel">
      <div class="signup-box">
        <h2>Create Account</h2>
        <p>Join us for the ride!</p>
        <form method="POST" action="signup.php">
          <label>Full Name</label>
          <input type="text" name="full_name" placeholder="Enter your full name" required />
          
          <label>Email</label>
          <input type="email" name="email" placeholder="Enter your email" required />
          
          <label>Username</label>
          <input type="text" name="username" placeholder="Create a username" required />
          
          <label>Password</label>
          <input type="password" name="password" placeholder="Create a password" required />

          <label>Admin Code</label>
          <input type="text" name="admin_code" placeholder="Enter the admin code" required />

          <button type="submit">Sign Up</button>
        </form>
        <div class="login-link">
          <span>Already have an account? <a href="login.php">Login</a></span>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
