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

body {
    font-family: Arial, sans-serif;
    background: #f4f6f8;
    margin: 0;
    padding: 30px;
}

.container {
    max-width: 900px;
    margin: auto;
}

h1 {
    color: #333;
}

.card {
    background: white;
    padding: 25px;
    margin-bottom: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 8px #ccc;
}

.success {
    background: #d4edda;
    color: #155724;
    padding: 12px;
    border-radius: 6px;
    margin-bottom: 20px;
}

input {
    width: 100%;
    padding: 10px;
    margin: 8px 0 15px 0;
    box-sizing: border-box;
    border: 1px solid #ccc;
    border-radius: 5px;
}

button {
    background: #333;
    color: white;
    padding: 10px 20px;
    border: 0;
    border-radius: 5px;
    cursor: pointer;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    padding: 12px;
    border-bottom: 1px solid #ddd;
    text-align: left;
}

th {
    background: #333;
    color: white;
}

.info {
    line-height: 1.8;
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