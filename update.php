
<?php
session_start();

if (!isset($_SESSION['matric'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$error = "";
$matric = "";
$name = "";
$role = "";
$old_matric = "";

// Get existing user information
if ($_SERVER["REQUEST_METHOD"] == "GET") {

    if (!isset($_GET['matric'])) {
        header("Location: users.php");
        exit();
    }

    $old_matric = $_GET['matric'];

    $sql = "SELECT matric, name, role FROM users WHERE matric = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $old_matric);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $user = $result->fetch_assoc();

        $matric = $user['matric'];
        $name = $user['name'];
        $role = $user['role'];

    } else {
        header("Location: users.php");
        exit();
    }

    $stmt->close();
}

// Update user information
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $old_matric = $_POST['old_matric'];
    $matric = trim($_POST['matric']);
    $name = trim($_POST['name']);
    $role = trim($_POST['role']);

    if ($matric == "" || $name == "" || $role == "") {

        $error = "Please fill in all fields.";

    } else {

        $sql = "UPDATE users SET matric = ?, name = ?, role = ?
                WHERE matric = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $matric, $name, $role, $old_matric);

        if ($stmt->execute()) {

            header("Location: users.php?updated=1");
            exit();

        } else {

            $error = "Unable to update user. Check whether the matric number already exists.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Update User</h2>

<p style="color:red;">
    <?php echo htmlspecialchars($error); ?>
</p>

<form method="POST" action="">

    <input type="hidden" name="old_matric"
           value="<?php echo htmlspecialchars($old_matric); ?>">

    <label>Matric:</label>

    <input type="text" name="matric"
           value="<?php echo htmlspecialchars($matric); ?>" required>

    <br><br>

    <label>Name:</label>

    <input type="text" name="name"
           value="<?php echo htmlspecialchars($name); ?>" required>

    <br><br>

    <label>Access Level:</label>

    <input type="text" name="role"
           value="<?php echo htmlspecialchars($role); ?>" required>

    <br><br>

    <button type="submit">Update</button>

    <a href="users.php">Cancel</a>

</form>

</body>
</html>
