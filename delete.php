
<?php
session_start();

if (!isset($_SESSION['matric'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

// Delete selected user
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $matric = $_POST['matric'];

    $sql = "DELETE FROM users WHERE matric = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $matric);

    if ($stmt->execute()) {

        header("Location: users.php?deleted=1");
        exit();

    } else {

        echo "Error deleting user.";
    }

    $stmt->close();

} else {

    header("Location: users.php");
    exit();
}
?>
