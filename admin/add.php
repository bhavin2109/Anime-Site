<?php
require_once 'check_admin.php';
include '../includes/dbconnect.php';
$anime_list = [];
$result = mysqli_query($conn, "SELECT anime_id, anime_name FROM anime");
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result))
        $anime_list[] = $row;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["anime_name"])) {
        $anime_name = $_POST["anime_name"];
        $anime_image = $_FILES["anime_image"]["name"];
        $anime_type = $_POST["anime_type"];
        $genre = $_POST["genre"];
        $target_dir = "../assets/thumbnails/";
        $target_file = $target_dir . basename($anime_image);
        $sql = "SELECT * FROM anime WHERE anime_name = '$anime_name'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            echo "<script>alert('Anime already exists!');</script>";
        }
        else {
            if (move_uploaded_file($_FILES["anime_image"]["tmp_name"], $target_file)) {
                $sql = "INSERT INTO anime (anime_name, anime_image, anime_type, genre) VALUES ('$anime_name', '$anime_image', '$anime_type', '$genre')";
                if (mysqli_query($conn, $sql)) {
                    echo "<script>alert('Anime added successfully!'); window.location.href = 'add.php';</script>";
                }
                else {
                    echo "<script>alert('Error adding anime.');</script>";
                }
            }
            else {
                echo "<script>alert('Error uploading image.');</script>";
            }
        }
    }
    elseif (isset($_POST["slider_anime_id"])) {
        $slider_anime_id = $_POST["slider_anime_id"];
        $slider_image = $_FILES["slider_image"]["name"];
        $target_file = "../assets/slider/" . basename($slider_image);
        if (move_uploaded_file($_FILES["slider_image"]["tmp_name"], $target_file)) {
            $sql = "INSERT INTO slider (anime_id, slider_image) VALUES ('$slider_anime_id', '$slider_image')";
            if (mysqli_query($conn, $sql)) {
                echo "<script>alert('Slider added!'); window.location.href = 'dashboard.php';</script>";
            }
        }
    }
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Anime</title>
    <link rel="stylesheet" href="../css/admin_styles.css">
    <style>
        body { display: flex; justify-content: center; align-items: center; min-height: 100vh; gap: 30px; flex-wrap: wrap; padding: 20px; }
        @media (max-width: 768px) { body { flex-direction: column; } }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Add Anime</h2>
        <form action="add.php" method="post" enctype="multipart/form-data">
            <input type="text" name="anime_name" placeholder="Anime Name" class="input-box" required>
            <input type="file" name="anime_image" class="input-box" required>
            <?php
include '../includes/dbconnect.php';
$typeResult = mysqli_query($conn, "SELECT * FROM type");
?>
            <select name="anime_type" class="input-box" required>
                <option value="">Select Type</option>
                <?php while ($t = mysqli_fetch_assoc($typeResult)): ?>
                    <option value="<?php echo htmlspecialchars($t['name']); ?>"><?php echo htmlspecialchars($t['name']); ?></option>
                <?php
endwhile; ?>
            </select>
            <?php $genreResult = mysqli_query($conn, "SELECT * FROM genres"); ?>
            <select name="genre" class="input-box" required>
                <option value="">Select Genre</option>
                <?php while ($g = mysqli_fetch_assoc($genreResult)): ?>
                    <option value="<?php echo htmlspecialchars($g['genre_name']); ?>"><?php echo htmlspecialchars($g['genre_name']); ?></option>
                <?php
endwhile; ?>
            </select>
            <button type="submit">Add Anime</button>
        </form>
    </div>
</body>
</html>
