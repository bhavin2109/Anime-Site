<?php
session_start();
include '../pages/dbconnect.php';

// Initialize variables to store form data
$identifierType = '';
$animeIdentifier = '';
$successMessage = '';
$errorMessage = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifierType = $_POST['identifier_type'];
    $animeIdentifier = $_POST['anime_identifier'];

    // Prepare the SQL query based on the identifier type
    if ($identifierType === 'id') {
        $query = "SELECT * FROM anime WHERE anime_id = ?";
    } else {
        $query = "SELECT * FROM anime WHERE anime_name = ?";
    }

    // Prepare and execute the query
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $animeIdentifier);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the anime details
        $animeDetails = $result->fetch_assoc();

        // Update the session with the new trending anime details
        $_SESSION['trending_anime'] = $animeDetails;

        // Set success message
        $successMessage = 'Trending anime updated successfully!';
        // Redirect to dashboard
        header("Location: dashboard.php");
        exit();
    } else {
        // Set error message if anime not found
        $errorMessage = 'Anime not found.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Trending Anime</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 300px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .form-container h2 {
            margin-bottom: 20px;
            text-align: center;
        }

        .form-container input[type="text"],
        .form-container select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-container button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color: #0056b3;
        }

        .form-container .message {
            margin-top: 10px;
            text-align: center;
        }

        .form-container .success {
            color: green;
        }

        .form-container .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Update Trending Anime</h2>
        <?php if (!empty($successMessage)): ?>
            <div class="message success"><?php echo htmlspecialchars($successMessage); ?></div>
        <?php endif; ?>
        <?php if (!empty($errorMessage)): ?>
            <div class="message error"><?php echo htmlspecialchars($errorMessage); ?></div>
        <?php endif; ?>
        <form method="post">
            <select name="identifier_type" required>
                <option value="id" <?php if ($identifierType === 'id') echo 'selected'; ?>>Anime ID</option>
                <option value="name" <?php if ($identifierType === 'name') echo 'selected'; ?>>Anime Name</option>
            </select>
            <input type="text" name="anime_identifier" placeholder="Anime ID or Name" value="<?php echo htmlspecialchars($animeIdentifier); ?>" required>
            <button type="submit">Update Trending Anime</button>
        </form>
    </div>
</body>
</html>