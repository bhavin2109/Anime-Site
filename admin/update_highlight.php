<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database connection
    include '../pages/dbconnect.php';

    // Handle file upload
    if (!empty($_FILES['video_file']['name'])) {
        $targetDir = "../assets/videos/";
        $fileName = basename($_FILES['video_file']['name']);
        $targetFilePath = $targetDir . $fileName;

        if (move_uploaded_file($_FILES['video_file']['tmp_name'], $targetFilePath)) {
            $videoName = $_POST['video_name'];
            
            // Update database
            $stmt = $conn->prepare("UPDATE highlight_videos SET video_file = ?, video_name = ? WHERE video_id = 1");
            $stmt->bind_param("ss", $fileName, $videoName);
            $stmt->execute();
            
            if ($stmt->affected_rows > 0) {
                echo "Highlight video updated successfully!";
            } else {
                echo "Failed to update the highlight video.";
            }
        } else {
            echo "Failed to upload the video file.";
        }
    } else {
        echo "Please select a video file to upload.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Highlight Video</title>
    <style>
        /* General Page Styles */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f9;
    color: #333;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

h1 {
    text-align: center;
    color: #444;
    margin-bottom: 20px;
}

/* Form Container */
form {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 20px 30px;
    width: 100%;
    max-width: 400px;
}

/* Input and Label Styles */
label {
    font-size: 14px;
    color: #555;
    margin-bottom: 8px;
    display: block;
}

input[type="text"],
input[type="file"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
    transition: border-color 0.3s ease;
}

input[type="text"]:focus,
input[type="file"]:focus {
    border-color: #007bff;
    outline: none;
}

/* Submit Button Styles */
button[type="submit"] {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 12px 16px;
    font-size: 16px;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    width: 100%;
}

button[type="submit"]:hover {
    background-color: #0056b3;
}

/* Responsive Design */
@media (max-width: 600px) {
    form {
        padding: 20px;
    }

    button[type="submit"] {
        font-size: 14px;
        padding: 10px;
    }
}

    </style>
</head>
<body>
    <h1>Update Highlight Video</h1>
    <form action="update_highlight.php" method="POST" enctype="multipart/form-data">
        <label for="video_name">Video Name:</label>
        <input type="text" name="video_name" id="video_name" required>
        <br><br>
        <label for="video_file">Upload Video:</label>
        <input type="file" name="video_file" id="video_file" accept="video/*" required>
        <br><br>
        <button type="submit">Update Video</button>
    </form>
</body>
</html>
