<?php
include 'dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $dob = $_POST['dob'];

    $stmt = $conn->prepare("INSERT INTO user1 (firstname, lastname, dob) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $firstname, $lastname, $dob);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
