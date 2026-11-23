
<?php

include 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $matric = trim($_POST['matric']);
    $name = trim($_POST['name']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Check empty fields
    if (empty($matric) || empty($name) ||
        empty($password) || empty($role)) {

        $message = "Please fill in all fields.";

    } else {

        // Hash the password
        $hashedPassword = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        // Insert data into database
        $sql = "INSERT INTO users
                (matric, name, password, role)
                VALUES (?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "ssss",
            $matric,
            $name,
            $hashedPassword,
            $role
        );

        if ($stmt->execute()) {
            $message = "Registration successful!";
        } else {
            $message = "Registration failed. Matric may already exist.";
        }

        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Page</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<h2>User Registration</h2>

<form method="POST" action="">

    Matric:
    <input type="text" name="matric"
           maxlength="10" required>
    <br><br>

    Name:
    <input type="text" name="name"
           maxlength="100" required>
    <br><br>

    Password:
    <input type="password" name="password"
           required>
    <br><br>

    Role:
    <select name="role" required>
        <option value="">Please select</option>
        <option value="student">Student</option>
        <option value="lecturer">Lecturer</option>
    </select>
    <br><br>

    <button type="submit">Submit</button>

</form>

<p><?php echo htmlspecialchars($message); ?></p>

</body>
</html>

