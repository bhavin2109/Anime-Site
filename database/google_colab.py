from google.colab import drive
import os

# Mount Google Drive
drive.mount('/content/drive')

# Change this to your anime folder path in Drive
anime_folder = "/content/drive/My Drive/AnimeFolder"

# List files and extract IDs
files = os.listdir(anime_folder)

for file in files:
    print(f"File Name: {file}")
