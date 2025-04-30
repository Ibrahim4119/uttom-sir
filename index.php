<?php include 'dbconnect.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Uttara University Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 40px;
        }
        .form-container, table {
            background: #fff;
            padding: 20px;
            margin: auto;
            width: 80%;
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }
        table {
            margin-top: 30px;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }
        th {
            background-color: #3498db;
            color: white;
        }
        input, button {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        button {
            background-color: #3498db;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #2980b9;
        }

        .btn {
    padding: 6px 12px;
    text-decoration: none;
    border-radius: 4px;
    font-size: 14px;
    margin: 2px;
    display: inline-block;
}

.btn.edit {
    background-color: #3498db;
    color: white;
}

.btn.delete {
    background-color: #e74c3c;
    color: white;
}

.btn.edit:hover {
    background-color: #2980b9;
}

.btn.delete:hover {
    background-color: #c0392b;
}

.btn {
    padding: 8px 14px;
    border-radius: 4px;
    text-decoration: none;
    font-size: 14px;
    margin-left: 10px;
    display: inline-block;
}

.btn.login {
    background-color: #2ecc71;
    color: white;
}

.btn.register {
    background-color: #e67e22;
    color: white;
}

.btn.login:hover {
    background-color: #27ae60;
}

.btn.register:hover {
    background-color: #d35400;
}

.btn {
    display: inline-block;
    padding: 10px 16px;
    border-radius: 4px;
    text-decoration: none;
    font-size: 14px;
    margin-top: 10px;
    color: white;
    text-align: center;
}

.btn.register {
    background-color: #e67e22;
}

.btn.register:hover {
    background-color: #d35400;
}


    </style>
</head>
<body>

<div style="text-align: right; margin-bottom: 20px;">
    <a href="login.php" class="btn login">Login</a>
    <a href="register.php" class="btn register">Register</a>
</div>


<div class="form-container">
    <h2>Add New User</h2>
    <form action="add.php" method="post">
        <label>First Name</label>
        <input type="text" name="firstname" required>

        <label>Last Name</label>
        <input type="text" name="lastname" required>

        <label>Date of Birth</label>
        <input type="date" name="dob" required>

        <button type="submit">Add User</button>
    </form>

    <?php if (!isset($_SESSION['username'])): ?>
<div class="container">
    <h2>Login</h2>
    <?php if ($loginError): ?>
        <p style="color: red;"><?= $loginError ?></p>
    <?php endif; ?>
    <form method="post">
        <input type="text" name="username" placeholder="Username" required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <button type="submit" name="login">Login</button>
    </form>
    <a href="register.php" class="btn register">Register</a>
</div>
<?php else: ?>
    <p style="text-align: right;">Welcome, <?= $_SESSION['username'] ?> | <a href="logout.php">Logout</a></p>
<?php endif; ?>

</div>

<?php

if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search = "%" . $_GET['search'] . "%";
    $stmt = $conn->prepare("SELECT * FROM user1 WHERE firstname LIKE ? OR lastname LIKE ? ORDER BY userid DESC");
    $stmt->bind_param("ss", $search, $search);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM user1 ORDER BY userid DESC");
}


if ($result->num_rows > 0): ?>
    <table>
        <tr>
            <th>User ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Date of Birth</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['userid'] ?></td>
            <td><?= $row['firstname'] ?></td>
            <td><?= $row['lastname'] ?></td>
            <td><?= $row['dob'] ?></td>
            <td>
                <a class="btn edit" href="edit.php?userid=<?= $row['userid'] ?>">Edit</a>
                <a class="btn delete" href="delete.php?userid=<?= $row['userid'] ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
            </td>
        </tr>

        <th>Actions</th>
        <?php endwhile; ?>
    </table>

    <form method="get" action="index.php" style="margin-top: 20px; text-align: center;">
        <input type="text" name="search" placeholder="Search by name..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" style="padding: 8px; width: 250px;">
        <button type="submit" style="padding: 8px 12px;">Search</button>
        <a href="index.php" style="margin-left: 10px; color: #3498db; text-decoration: none;">Clear</a>
    </form>

<?php else: ?>
    <p style="text-align: center;">No users found.</p>

    <?php
session_start();
include 'dbconnect.php';

$loginError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            header("Location: index.php");
            exit();
        } else {
            $loginError = "Incorrect password.";
        }
    } else {
        $loginError = "User not found.";
    }
}
?>


    <?php
session_start();
if (!isset($_SESSION['username'])) {
    echo "<p style='text-align:center;'>Please <a href='login.php'>login</a> to access the user system.</p>";
    exit();
}
?>

<?php
session_start();
session_destroy();
header("Location: index.php");
exit();
?>

<?php endif; ?>

</body>
</html>
