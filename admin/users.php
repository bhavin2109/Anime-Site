<?php
require_once 'check_admin.php';
include_once('../includes/dbconnect.php');
$result = mysqli_query($conn, "SELECT * FROM users");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Users</title>
    <link rel="stylesheet" href="../css/admin_styles.css">
</head>
<body>
    <div class="admin-navbar">
        <h2>Registered Users</h2>
    </div>
    <div class="admin-container" style="max-width:900px;">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['user_id']) . "</td>";
        echo "<td style='text-align:left;'>" . htmlspecialchars($row['username']) . "</td>";
        echo "<td style='text-align:left;'>" . htmlspecialchars($row['email']) . "</td>";
        echo "</tr>";
    }
}
else {
    echo "<tr><td colspan='3' style='text-align:center;color:var(--gray-light);'>No users found</td></tr>";
}
?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php mysqli_close($conn); ?>