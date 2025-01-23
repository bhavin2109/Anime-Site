import os
import time
import subprocess
from datetime import datetime

# Configuration
DB_NAME = "anime_site"
DB_USER = "root"
DB_PASSWORD = ""
SQL_FILE = "C:/xampp/htdocs/Projects/Anime-Site/database/anime_site.sql"
MYSQL_BIN_PATH = "C:/xampp/mysql/bin"  # Path to MySQL bin directory
EXPORT_CMD = f'"{MYSQL_BIN_PATH}/mysqldump" -u {DB_USER} --password={DB_PASSWORD} {DB_NAME} > "{SQL_FILE}"'
IMPORT_CMD = f'"{MYSQL_BIN_PATH}/mysql" -u {DB_USER} --password={DB_PASSWORD} {DB_NAME} < "{SQL_FILE}"'
GIT_REPO_PATH = "C:/xampp/htdocs/projects/anime-site/"  # Path to the GitHub repository

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


def get_database_checksum():
    """Returns a checksum of the current database state."""
    checksum_cmd = f'"{MYSQL_BIN_PATH}/mysqldump" -u {DB_USER} --password={DB_PASSWORD} {DB_NAME} | md5sum'
    result = subprocess.run(checksum_cmd, shell=True, capture_output=True, text=True)
    return result.stdout.strip()


def monitor_changes():
    global db_last_export_time, file_last_modified_time

    last_db_checksum = get_database_checksum()

    while True:
        # Monitor for changes in the database
        current_db_checksum = get_database_checksum()
        if current_db_checksum != last_db_checksum:
            print("Changes detected in the database. Exporting to sync changes...")
            export_database()
            last_db_checksum = current_db_checksum

            # Automatically push the updated file to GitHub
            push_to_github()

        # Monitor for changes in the .sql file
        current_file_modified_time = os.path.getmtime(SQL_FILE)
        if current_file_modified_time != file_last_modified_time:
            print("Changes detected in the .sql file. Updating the database...")
            import_database()
            file_last_modified_time = current_file_modified_time

        time.sleep(1)  # Check for changes every 5 seconds


if __name__ == "__main__":
    monitor_changes()
