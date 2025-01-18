<?php
session_start();
include '../pages/dbconnect.php';
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
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Update Trending Anime</h2>
        <form id="updateForm">
            <select name="identifier_type" required>
                <option value="id">Anime ID</option>
                <option value="name">Anime Name</option>
            </select>
            <input type="text" name="anime_identifier" placeholder="Anime ID or Name" required>
            <button type="submit">Update Trending Anime</button>
        </form>
    </div>

    <script>
        document.getElementById('updateForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const identifierType = document.querySelector('select[name="identifier_type"]').value;
            const animeIdentifier = document.querySelector('input[name="anime_identifier"]').value;

            // Fetch anime details based on identifier type
            fetch(`fetch_anime.php?identifier_type=${identifierType}&anime_identifier=${animeIdentifier}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update PHP session with new trending anime details
                        fetch('update_trending_session.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(data)
                        })
                        .then(response => response.json())
                        .then(responseData => {
                            if (responseData.success) {
                                alert('Trending anime updated successfully!');
                                window.location.href = 'dashboard.php';
                            } else {
                                alert('Failed to update trending anime session.');
                            }
                        })
                        .catch(error => {
                            console.error('Error updating session:', error);
                            alert('An error occurred. Please try again.');
                        });
                    } else {
                        alert('Anime not found.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
        });
    </script>
</body>
</html>