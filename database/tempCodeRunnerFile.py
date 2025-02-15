import os
import time
import subprocess
from datetime import datetime

# Configuration
DB_NAME = "anime_site"  # Replace with your actual database name
DB_USER = "root"
DB_PASSWORD = ""
SQL_FILE = "C:/xampp/htdocs/Projects/Anime-Site/database/anime_site.sql"  # Replace with your actual path
EXPORT_CMD = f'mysqldump -u {DB_USER} --password={DB_PASSWORD} {DB_NAME} > "{SQL_FILE}"'
IMPORT_CMD = f'mysql -u {DB_USER} --password={DB_PASSWORD} {DB_NAME} < "{SQL_FILE}"'
GIT_REPO_PATH = "C:/xampp/htdocs/Projects/Anime-Site/"  # Replace with your actual GitHub repository path

# Track timestamps
db_last_export_time = datetime.min  # Tracks last export to the file
file_last_modified_time = os.path.getmtime(SQL_FILE)  # Tracks last modification of .sql file


def export_database():
    """Exports the MySQL database to the .sql file."""
    print("Exporting database to .sql file...")
    subprocess.run(EXPORT_CMD, shell=True, check=True)
    print("Database exported successfully!")


def import_database():
    """Imports the .sql file to the MySQL database."""
    print("Importing .sql file to the database...")
    subprocess.run(IMPORT_CMD, shell=True, check=True)
    print("Database updated successfully!")


def push_to_github():
    """Pushes the updated .sql file to the GitHub repository."""
    print("Pushing changes to GitHub...")
    try:
        os.chdir(GIT_REPO_PATH)
        subprocess.run(["git", "add", "."], check=True)
        subprocess.run(["git", "commit", "-m", "Update database_backup.sql"], check=True)
        subprocess.run(["git", "push", "origin", "main"], check=True)
        print("Changes pushed to GitHub successfully!")
    except subprocess.CalledProcessError as e:
        print(f"Git operation failed: {e}")


while True:
    # Monitor for changes in the database
    current_time = datetime.now()
    if (current_time - db_last_export_time).seconds >= 10:  # Check every 10 seconds
        print("Exporting database to sync changes...")
        export_database()
        db_last_export_time = current_time

        # Automatically push the updated file to GitHub
        push_to_github()

    # Monitor for changes in the .sql file
    current_file_modified_time = os.path.getmtime(SQL_FILE)
    if current_file_modified_time != file_last_modified_time:
        print("Changes detected in the .sql file. Updating the database...")
        import_database()
        file_last_modified_time = current_file_modified_time

    time.sleep(300)  # Check for changes every 5 seconds
