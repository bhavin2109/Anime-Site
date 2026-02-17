<?php
require_once 'check_admin.php';
include_once("../includes/dbconnect.php");
if (!$conn)
    die("Connection failed.");

if (isset($_GET['anime_id']) && is_numeric($_GET['anime_id'])) {
    $anime_id = $_GET['anime_id'];
    $result = mysqli_query($conn, "SELECT * FROM anime WHERE anime_id=$anime_id");
    if ($result && mysqli_num_rows($result) > 0) {
        $anime = mysqli_fetch_array($result);
        $anime_name = $anime['anime_name'];
        $anime_image = $anime['anime_image'];
    }
    else {
        echo "<script>alert('Anime not found.'); window.location.href = 'anime.php';</script>";
        exit();
    }
}
else {
    echo "<script>alert('Invalid ID.'); window.location.href = 'anime.php';</script>";
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
        }
        else {
            echo "<script>alert('Error uploading.');</script>";
            exit();
        }
    }
    else {
        $sql = "UPDATE anime SET anime_name='$anime_name' WHERE anime_id=$anime_id";
    }
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Updated!'); window.location.href = 'anime.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Anime</title>
    <link rel="stylesheet" href="../css/admin_styles.css">
    <style>
        body { display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
    </style>
</head>
<body>
    <div class="form-container" style="max-width:500px;">
        <h2>Update Anime</h2>
        <form method="post" action="update_anime.php?anime_id=<?php echo $anime_id; ?>" enctype="multipart/form-data">
            <input type="hidden" name="anime_id" value="<?php echo $anime_id; ?>">
            <input type="text" name="anime_name" value="<?php echo htmlspecialchars($anime_name); ?>" required>
            <p style="color:var(--gray-light);font-size:0.85rem;margin:8px 0 4px;">Current image: <strong style="color:var(--gold);"><?php echo htmlspecialchars($anime_image); ?></strong></p>
            <input type="file" name="anime_image">
            <button type="submit" name="update">Update Anime</button>
        </form>
    </div>
</body>
</html>