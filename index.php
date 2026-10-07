<?php

// =========================
// เชื่อมต่อ MySQL
// =========================

$host = getenv("MYSQLHOST");
$port = getenv("MYSQLPORT");
$user = getenv("MYSQLUSER");
$pass = getenv("MYSQLPASSWORD");
$db   = getenv("MYSQLDATABASE");

if (!$port) {
    $port = 3306;
}

$conn = mysqli_connect(
    $host,
    $user,
    $pass,
    $db,
    $port
);

if (!$conn) {
    die("MySQL Connection Failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8");

// =========================
// สร้างตาราง users ถ้ายังไม่มี
// =========================

$sql = "
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    mobile VARCHAR(30) NOT NULL
)";

mysqli_query($conn, $sql);


// =========================
// เพิ่มข้อมูล
// =========================

if (isset($_POST["add"])) {

    $name = "";
    $email = "";
    $mobile = "";

    if (isset($_POST["name"])) {
        $name = trim($_POST["name"]);
    }

    if (isset($_POST["email"])) {
        $email = trim($_POST["email"]);
    }

    if (isset($_POST["mobile"])) {
        $mobile = trim($_POST["mobile"]);
    }

    if ($name != "" && $email != "" && $mobile != "") {

        $name = mysqli_real_escape_string($conn, $name);
        $email = mysqli_real_escape_string($conn, $email);
        $mobile = mysqli_real_escape_string($conn, $mobile);

        $sql = "
        INSERT INTO users (name, email, mobile)
        VALUES ('$name', '$email', '$mobile')
        ";

        mysqli_query($conn, $sql);
    }
}


// =========================
// ดึงข้อมูล Users
// =========================

$result = mysqli_query(
    $conn,
    "SELECT * FROM users ORDER BY id DESC"
);

?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">

<title>Nginx Web Server</title>

<style>

* {
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Arial, sans-serif;
    background: linear-gradient(135deg, #0f0c29, #1a1446 50%, #24243e);
    background-attachment: fixed;
    color: #e6e9ff;
    margin: 0;
    padding: 30px;
    min-height: 100vh;
}

.container {
    max-width: 900px;
    margin: auto;
}

h1 {
    margin-top: 0;
    background: linear-gradient(90deg, #00f5d4, #7b61ff, #ff4ecd);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    color: transparent;
}

h2 {
    color: #00f5d4;
    margin-top: 0;
}

.card {
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(123, 97, 255, 0.35);
    padding: 25px;
    margin-bottom: 20px;
    border-radius: 16px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.45), 0 0 18px rgba(123, 97, 255, 0.15);
    backdrop-filter: blur(8px);
}

.success {
    background: rgba(0, 245, 212, 0.12);
    border: 1px solid #00f5d4;
    color: #00f5d4;
    padding: 12px;
    border-radius: 10px;
    margin-bottom: 20px;
    font-weight: bold;
    box-shadow: 0 0 14px rgba(0, 245, 212, 0.25);
}

label {
    color: #b9b4ff;
    font-size: 14px;
}

input {
    width: 100%;
    padding: 12px;
    margin: 8px 0 15px 0;
    background: rgba(15, 12, 41, 0.7);
    color: #ffffff;
    border: 1px solid rgba(123, 97, 255, 0.5);
    border-radius: 10px;
    outline: none;
    transition: all 0.2s;
}

input::placeholder {
    color: #6f6aa8;
}

input:focus {
    border-color: #00f5d4;
    box-shadow: 0 0 12px rgba(0, 245, 212, 0.5);
}

button {
    background: linear-gradient(90deg, #7b61ff, #ff4ecd);
    color: white;
    padding: 12px 28px;
    border: 0;
    border-radius: 10px;
    font-weight: bold;
    font-size: 15px;
    cursor: pointer;
    box-shadow: 0 0 18px rgba(255, 78, 205, 0.45);
    transition: all 0.2s;
}

button:hover {
    transform: translateY(-2px);
    background: linear-gradient(90deg, #00f5d4, #7b61ff);
    box-shadow: 0 0 24px rgba(0, 245, 212, 0.6);
}

table {
    width: 100%;
    border-collapse: collapse;
    overflow: hidden;
    border-radius: 10px;
}

th, td {
    padding: 12px;
    border-bottom: 1px solid rgba(123, 97, 255, 0.25);
    text-align: left;
}

th {
    background: linear-gradient(90deg, #7b61ff, #ff4ecd);
    color: white;
}

tr:hover td {
    background: rgba(0, 245, 212, 0.07);
}

.info {
    line-height: 1.8;
}

.info strong {
    color: #00f5d4;
}

</style>

</head>

<body>

<div class="container">

    <div class="card">

        <h1>Nginx Web Server + PHP-FPM + MySQL</h1>

        <div class="success">
            ✓ MySQL Connection Successful
        </div>

        <div class="info">
            <strong>ชื่อ:</strong> นายยูจิโร ไซโต<br>
            <strong>รหัสนักศึกษา:</strong> 6740214126
        </div>

    </div>


    <div class="card">

        <h2>Add New Contact</h2>

        <form method="post">

            <label>Name</label>
            <input
                type="text"
                name="name"
                placeholder="Enter name"
            >

            <label>Email</label>
            <input
                type="text"
                name="email"
                placeholder="Enter email"
            >

            <label>Mobile</label>
            <input
                type="text"
                name="mobile"
                placeholder="Enter mobile"
            >

            <button type="submit" name="add">
                Add Contact
            </button>

        </form>

    </div>


    <div class="card">

        <h2>Users List</h2>

        <table>

            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Mobile</th>
            </tr>

            <?php

            if ($result) {

                while ($row = mysqli_fetch_assoc($result)) {

                    echo "<tr>";

                    echo "<td>";
                    echo $row["id"];
                    echo "</td>";

                    echo "<td>";
                    echo htmlspecialchars($row["name"]);
                    echo "</td>";

                    echo "<td>";
                    echo htmlspecialchars($row["email"]);
                    echo "</td>";

                    echo "<td>";
                    echo htmlspecialchars($row["mobile"]);
                    echo "</td>";

                    echo "</tr>";
                }
            }

            ?>

        </table>

    </div>

</div>

</body>
</html>