<?php
require_once 'check_admin.php';
// Include database connection file
include_once("../includes/dbconnect.php");

// Check database connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get ID from URL
if (isset($_GET['anime_id']) && is_numeric($_GET['anime_id'])) {
    $anime_id = $_GET['anime_id'];

    // Fetch anime data based on ID
    $result = mysqli_query($conn, "SELECT * FROM anime WHERE anime_id=$anime_id");

    if ($result && mysqli_num_rows($result) > 0) {
        $anime = mysqli_fetch_array($result);
        $anime_name = $anime['anime_name'];
        $anime_image = $anime['anime_image'];
    } else {
        echo "<script>alert('Anime not found.');</script>";
        echo "<script>window.location.href = 'anime.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('Invalid anime ID.');</script>";
    echo "<script>window.location.href = 'anime.php';</script>";
    exit();
}

if (isset($_POST['update'])) {
    $anime_id = $_POST['anime_id'];
    $anime_name = $_POST['anime_name'];
    $anime_image = $_FILES['anime_image']['name'];
    $target_dir = "../assets/thumbnails/";

    if (!empty($anime_image)) {
        $target_file = $target_dir . basename($anime_image);
        if (move_uploaded_file($_FILES['anime_image']['tmp_name'], $target_file)) {
            $sql = "UPDATE anime SET anime_name='$anime_name', anime_image='$anime_image' WHERE anime_id=$anime_id";
        } else {
            echo "<script>alert('Error uploading anime image.');</script>";
            exit();
        }
    } else {
        $sql = "UPDATE anime SET anime_name='$anime_name' WHERE anime_id=$anime_id";
    }

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Anime updated successfully!');</script>";
        echo "<script>window.location.href = 'anime.php';</script>";
    } else {
        echo "<script>alert('Error updating anime: " . mysqli_error($conn) . "');</script>";
    }
}
?>
<form name="update_anime" method="post" action="update_anime.php?anime_id=<?php echo $anime_id; ?>" enctype="multipart/form-data">
    <table border="0">
        <tr>
            <td>Name</td>
            <td><input type="text" name="anime_name" value="<?php echo $anime_name; ?>"></td>
        </tr>
        <tr>
            <td>Image</td>
            <td><input type="file" name="anime_image"></td>
        </tr>
        <tr>
            <td><input type="hidden" name="anime_id" value="<?php echo $anime_id; ?>"></td>
            <td><input type="submit" name="update" value="Update"></td>
        </tr>
    </table>
</form>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    table {
        margin: 50px auto;
        border-collapse: collapse;
        width: 50%;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    table td {
        padding: 10px;
        border: 1px solid #ddd;
    }

    table td input[type="text"],
    table td input[type="file"] {
        width: 100%;
        padding: 8px;
        box-sizing: border-box;
    }

    table td input[type="submit"] {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 10px 20px;
        cursor: pointer;
    }

    table td input[type="submit"]:hover {
        background-color: #45a049;
    }
</style>