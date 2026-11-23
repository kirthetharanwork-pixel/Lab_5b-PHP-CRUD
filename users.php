
<?php
session_start();

if (!isset($_SESSION['matric'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

// Retrieve users from database
$sql = "SELECT matric, name, role FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Users List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Registered Users</h2>

<p>
    Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!
</p>

<p>
    <a href="logout.php">Logout</a>
</p>

<?php
if (isset($_GET['updated'])) {
    echo "<p style='color:green;'>User updated successfully!</p>";
}

if (isset($_GET['deleted'])) {
    echo "<p style='color:green;'>User deleted successfully!</p>";
}
?>

<table border="1" cellpadding="8">

    <tr>
        <th>Matric</th>
        <th>Name</th>
        <th>Level</th>
        <th>Action</th>
    </tr>

    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
    ?>

    <tr>
        <td>
            <?php echo htmlspecialchars($row['matric']); ?>
        </td>

        <td>
            <?php echo htmlspecialchars($row['name']); ?>
        </td>

        <td>
            <?php echo htmlspecialchars($row['role']); ?>
        </td>

        <td>
            <a href="update.php?matric=<?php echo urlencode($row['matric']); ?>">
                Update
            </a>

            |

            <form method="POST" action="delete.php" style="display:inline;"
                  onsubmit="return confirm('Are you sure you want to delete this user?');">

                <input type="hidden" name="matric"
                       value="<?php echo htmlspecialchars($row['matric']); ?>">

                <button type="submit">Delete</button>

            </form>
        </td>
    </tr>

    <?php
        }
    } else {
        echo "<tr><td colspan='4'>No users found</td></tr>";
    }
    ?>

</table>

</body>
</html>
