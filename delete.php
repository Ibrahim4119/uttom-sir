<?php
include 'dbconnect.php';

if (isset($_GET['userid'])) {
    $userid = $_GET['userid'];

    $stmt = $conn->prepare("DELETE FROM user1 WHERE userid = ?");
    $stmt->bind_param("i", $userid);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Delete failed: " . $stmt->error;
    }
} else {
    echo "Invalid request.";
}
?>
