<?php
session_start();
if (!isset($_SESSION['admin_username'])) {
  header("Location: login.php");
  exit();
}
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "luxury_car_website";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);


if (!isset($_SESSION['admin_username']) || !isset($_SESSION['admin_email'])) {
    header("Location: login.php");
    exit();
}

$admin_username = $_SESSION['admin_username'];
$admin_email = $_SESSION['admin_email'];


$check_admin = $conn->prepare("SELECT * FROM admin_info WHERE admin_username = ?");
$check_admin->bind_param("s", $admin_username);
$check_admin->execute();
$result = $check_admin->get_result();
if ($result->num_rows === 0) {
    $insert_admin = $conn->prepare("INSERT INTO admin_info (admin_username, admin_email) VALUES (?, ?)");
    $insert_admin->bind_param("ss", $admin_username, $admin_email);
    $insert_admin->execute();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['customer_id']) && isset($_POST['checked'])) {
    $customer_id = intval($_POST['customer_id']);
    $checked = $_POST['checked'] === 'on' ? 1 : 0;
    $checked_by = $checked ? $admin_username : null;
    $stmt = $conn->prepare("UPDATE customer_registration SET checked_by = ? WHERE id = ?");
    $stmt->bind_param("si", $checked_by, $customer_id);
    $stmt->execute();
    echo $checked_by;
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["admin_image"])) {
    $upload_dir = "Upload Photo/";
    $image_name = basename($_FILES["admin_image"]["name"]);
    $target_file = $upload_dir . $image_name;

    if (getimagesize($_FILES["admin_image"]["tmp_name"])) {
        if ($_FILES["admin_image"]["size"] <= 5000000) {
            if (move_uploaded_file($_FILES["admin_image"]["tmp_name"], $target_file)) {
                $stmt = $conn->prepare("UPDATE admin_info SET admin_image_path = ? WHERE admin_username = ?");
                $stmt->bind_param("ss", $target_file, $admin_username);
                $stmt->execute();
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } else {
                echo "<script>alert('Upload failed.');</script>";
            }
        } else {
            echo "<script>alert('File too large.');</script>";
        }
    } else {
        echo "<script>alert('Invalid image.');</script>";
    }
}


$admin_data = $conn->query("SELECT * FROM admin_info WHERE admin_username = '$admin_username'")->fetch_assoc();

$customers = $conn->query("SELECT * FROM customer_registration");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <style>
    /* FS */
    body { margin: 0; font-family: Arial, sans-serif; background-color: #111; color: #fff; padding: 20px; }
    .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
    .signout-btn { background-color: red; border: none; padding: 10px 20px; border-radius: 8px; color: white; font-weight: bold; cursor: pointer; transition: background-color 0.3s ease, transform 0.2s ease; }
    .signout-btn:hover { background-color: #cc0000; transform: scale(1.05); }
    .admin-info { display: flex; flex-direction: column; align-items: center; }
    .admin-pic { width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 3px solid red; cursor: pointer; margin-bottom: 10px; }
    .admin-details h2 { margin: 5px 0; color: red; }
    .admin-details p { margin: 0; font-size: 14px; color: #ccc; }
    #imageUpload { display: none; }
    table { width: 100%; border-collapse: collapse; margin-top: 30px; }
    th, td { padding: 12px; border: 1px solid #444; text-align: center; }
    th { background-color: #222; color: red; }
    tr:nth-child(even) { background-color: #1c1c1c; }
    tr:nth-child(odd) { background-color: #2a2a2a; }
    .checked-admin { color: lightgreen; font-weight: bold; }
    @media (min-width: 600px) { .admin-info { flex-direction: row; align-items: center; } .admin-details { margin-left: 20px; } }
    @media (max-width: 599px) { table, th, td { font-size: 14px; } .admin-pic { width: 100px; height: 100px; } .top-bar { flex-direction: column; align-items: flex-start; gap: 10px; } }
  </style>
</head>
<body>

<div class="top-bar">
  <div class="admin-info">
    <form method="POST" enctype="multipart/form-data" id="uploadForm">
      <label for="imageUpload">
        <img src="<?php echo $admin_data['admin_image_path'] ?? 'default-admin.jpg'; ?>" alt="Admin" class="admin-pic" id="adminPic" title="Click to change image" />
      </label>
      <input type="file" name="admin_image" id="imageUpload" accept="image/*" onchange="document.getElementById('uploadForm').submit();" />
    </form>
    <div class="admin-details">
      <h2><?php echo htmlspecialchars($admin_username); ?></h2>
      <p><?php echo htmlspecialchars($admin_email); ?></p>
    </div>
  </div>
  <button class="signout-btn" onclick="signOut()">Sign Out</button>
</div>

<table>
  <thead>
    <tr>
      <th>Full Name</th>
      <th>Phone</th>
      <th>Car Name</th>
      <th>Email</th>
      <th>Location</th>
      <th>Budget</th>
      <th>Checked</th>
      <th>Checked By</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($row = $customers->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($row['full_name']); ?></td>
        <td><?= htmlspecialchars($row['phone_number']); ?></td>
        <td><?= htmlspecialchars($row['car_name']); ?></td>
        <td><?= htmlspecialchars($row['email']); ?></td>
        <td><?= htmlspecialchars($row['location']); ?></td>
        <td><?= htmlspecialchars($row['budget']); ?></td>
        <td>
          <input type="checkbox" onchange="markChecked(this, <?= $row['id']; ?>)" <?= $row['checked_by'] ? 'checked' : '' ?>>
        </td>
        <td class="checked-by"><?= htmlspecialchars($row['checked_by']); ?></td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<script>
function signOut() {
  alert("Signed out successfully!");
  window.location.href = "logout.php";
}

function markChecked(checkbox, customerId) {
  const checked = checkbox.checked ? 'on' : 'off';
  const formData = new FormData();
  formData.append("customer_id", customerId);
  formData.append("checked", checked);

  fetch("dash.php", {
    method: "POST",
    body: formData
  })
  .then(response => response.text())
  .then(data => {
    const cell = checkbox.parentElement.nextElementSibling;
    cell.textContent = checkbox.checked ? data : '';
  });
}
</script>

</body>
</html>
