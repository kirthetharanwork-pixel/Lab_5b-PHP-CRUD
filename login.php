
<?php
session_start();
include 'db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $matric = trim($_POST['matric']);
    $password = $_POST['password'];

    // Find user using matric number
    $sql = "SELECT * FROM users WHERE matric = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $matric);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $user = $result->fetch_assoc();

        // Verify hashed password
        if (password_verify($password, $user['password'])) {

            // Store user information in session
            session_regenerate_id(true);

            $_SESSION['matric'] = $user['matric'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            // Redirect to users.php
            header("Location: users.php");
            exit();

        } else {
            $error = "Invalid username or password, try login again.";
        }

    } else {
        $error = "Invalid username or password, try login again.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Login</h2>

<form method="POST" action="">

    <label>Matric:</label>
    <input type="text" name="matric" required>
    <br><br>

    <label>Password:</label>
    <input type="password" name="password" required>
    <br><br>

    <button type="submit">Login</button>

</form>

<p style="color:red;">
    <?php echo htmlspecialchars($error); ?>
</p>

<p>
    <a href="register.php">Register</a> here if you have not.
</p>

</body>
</html>
