<?php

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "luxury_car_website";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {
    
    $full_name = mysqli_real_escape_string($conn, $_POST["full_name"]);
    $phone_number = mysqli_real_escape_string($conn, $_POST["phone_number"]);
    $car_name = mysqli_real_escape_string($conn, $_POST["car_name"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $location = mysqli_real_escape_string($conn, $_POST["location"]);
    $budget = mysqli_real_escape_string($conn, $_POST["budget"]);

   
    $sql = "INSERT INTO customer_registration (full_name, phone_number, car_name, email, location, budget)
            VALUES ('$full_name', '$phone_number', '$car_name', '$email', '$location', '$budget')";

    if ($conn->query($sql) === TRUE) {
        
        echo "<script>
                alert('Registration successful! Welcome aboard.');
                window.location.href = 'homepage.html'; // Redirect to homepage after success
              </script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["check"])) {
    $user_id = mysqli_real_escape_string($conn, $_POST["user_id"]);
    $admin_username = mysqli_real_escape_string($conn, $_POST["admin_username"]);
    
    // FS
    $sql = "UPDATE customer_registration SET checked_by = '$admin_username' WHERE id = $user_id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('You have successfully checked the action!');</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Car Registration</title>
  <link rel="stylesheet" href="style500.css">
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Arial', sans-serif;
      background-color: #111;
      color: #fff;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      animation: fadeIn 1s ease-in-out;
      flex-direction: column;
      position: relative;
    }

    .homepage-btn {
      background-color: red;
      color: white;
      padding: 10px 20px;
      text-decoration: none;
      font-weight: bold;
      border-radius: 5px;
      position: fixed;
      top: 20px;
      left: 20px;
      z-index: 10;
      transition: background-color 0.3s;
    }

    .homepage-btn:hover {
      background-color: #cc0000;
    }

    .registration-container {
      background-color: rgba(0, 0, 0, 0.5);
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 0 20px rgba(255, 0, 0, 0.5);
      max-width: 500px;
      width: 100%;
      animation: slideUp 1s ease-out;
      position: relative;
      margin-top: 100px; 
    }

    .registration-form h2 {
      color: red;
      text-align: center;
      margin-bottom: 30px;
    }

    .registration-form input {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: none;
      border-radius: 8px;
      background-color: #333;
      color: #fff;
      font-size: 1rem;
      transition: background-color 0.3s ease;
    }

    .registration-form input:focus {
      background-color: #444;
      outline: none;
    }

    .registration-form button {
      width: 100%;
      padding: 12px;
      background-color: red;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 1rem;
      font-weight: bold;
      cursor: pointer;
      margin-top: 20px;
      transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .registration-form button:hover {
      background-color: #cc0000;
      transform: scale(1.05);
    }

    .welcome-msg {
      text-align: center;
      margin-top: 20px;
      color: lightgreen;
      font-size: 1.1rem;
      opacity: 0;
      transition: opacity 0.5s ease-in-out;
    }

    @keyframes fadeIn {
      0% { opacity: 0; }
      100% { opacity: 1; }
    }

    @keyframes slideUp {
      0% { transform: translateY(40px); opacity: 0; }
      100% { transform: translateY(0); opacity: 1; }
    }

   
    @media screen and (max-width: 600px) {
      .homepage-btn {
        top: 10px;
        left: 10px;
        padding: 8px 16px;
        font-size: 14px;
      }

      .registration-container {
        margin-top: 80px; 
        padding: 20px;
      }
    }

    @media screen and (max-width: 400px) {
      .homepage-btn {
        padding: 6px 14px;
        font-size: 12px;
      }

      .registration-container {
        margin-top: 60px; 
        padding: 15px;
      }

      .registration-form input, .registration-form button {
        font-size: 0.9rem;
        padding: 10px;
      }
    }
  </style>
</head>
<body>
  <a href="homepage.html" class="homepage-btn">Homepage</a>

  <div class="registration-container">
    <form class="registration-form" method="POST" action="reg.php">
      <h2>Ready to drive your dream car?<br>Register now</h2>
      <input type="text" name="full_name" placeholder="Full Name" required>
      <input type="tel" name="phone_number" placeholder="Phone Number" required>
      <input type="text" name="car_name" placeholder="Car Name" required>
      <input type="email" name="email" placeholder="Email Address" required>
      <input type="text" name="location" placeholder="Location" required>
      <input type="text" name="budget" placeholder="Budget" required>
      <button type="submit" name="register">Register</button>
      <p class="welcome-msg" id="welcomeMsg">Registration successful! Welcome aboard.</p>
    </form>
  </div>
</body>
</html>
