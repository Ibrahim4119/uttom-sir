<?php
include 'dbconnect.php';

if (isset($_GET['userid'])) {
    $userid = $_GET['userid'];

    // Get user details
    $stmt = $conn->prepare("SELECT * FROM user1 WHERE userid = ?");
    $stmt->bind_param("i", $userid);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        echo "User not found!";
        exit();
    }
} else {
    echo "Invalid request!";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <style>
        body {
            font-family: Arial;
            background: #f4f4f4;
            padding: 40px;
        }

        .form-container {
            background: white;
            padding: 30px;
            margin: auto;
            width: 400px;
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }

        input, button {
            width: 100%;
            padding: 10px;
            margin-top: 12px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        button {
            background-color: #3498db;
            color: white;
            border: none;
        }

        button:hover {
            background-color: #2980b9;
        }

        a {
            display: block;
            margin-top: 15px;
            text-align: center;
            text-decoration: none;
            color: #3498db;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Edit User</h2>
        <form method="post" action="edit.php">
            <input type="hidden" name="userid" value="<?= $user['userid'] ?>">

            <label>First Name</label>
            <input type="text" name="firstname" value="<?= $user['firstname'] ?>" required>

            <label>Last Name</label>
            <input type="text" name="lastname" value="<?= $user['lastname'] ?>" required>

            <label>Date of Birth</label>
            <input type="date" name="dob" value="<?= $user['dob'] ?>" required>

            <button type="submit" name="update">Update</button>
        </form>
        <a href="index.php">← Back</a>
    </div>
</body>
</html>

<?php
// Handle form submission
if (isset($_POST['update'])) {
    $userid = $_POST['userid'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $dob = $_POST['dob'];

    $stmt = $conn->prepare("UPDATE user1 SET firstname=?, lastname=?, dob=? WHERE userid=?");
    $stmt->bind_param("sssi", $firstname, $lastname, $dob, $userid);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Update failed: " . $stmt->error;
    }
}
?>
