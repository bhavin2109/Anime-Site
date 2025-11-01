import os
import time
import subprocess
from datetime import datetime

# Configuration
DB_NAME = "anime_site"  # Replace with your actual database name
DB_USER = "root"
DB_PASSWORD = ""
SQL_FILE = "C:/xampp/htdocs/Anime-Site/database/anime_site.sql"  # Replace with your actual path
GIT_REPO_PATH = "C:/xampp/htdocs/Anime-Site/"  # Replace with your actual GitHub repository path

# Helper to build MySQL commands safely (avoid empty password issues)
def build_export_cmd():
    if DB_PASSWORD:
        return ["mysqldump", "-u", DB_USER, f"--password={DB_PASSWORD}", DB_NAME]
    else:
        return ["mysqldump", "-u", DB_USER, DB_NAME]

def build_import_cmd():
    if DB_PASSWORD:
        return ["mysql", "-u", DB_USER, f"--password={DB_PASSWORD}", DB_NAME]
    else:
        return ["mysql", "-u", DB_USER, DB_NAME]

# Track timestamps
db_last_export_time = datetime.min  # Tracks last export to the file

try:
    file_last_modified_time = os.path.getmtime(SQL_FILE)  # Tracks last modification of .sql file
except FileNotFoundError:
    file_last_modified_time = 0  # If file doesn't exist yet

def export_database():
    """Exports the MySQL database to the .sql file."""
    print("Exporting database to .sql file...")
    try:
        with open(SQL_FILE, "w", encoding="utf-8") as f:
            subprocess.run(build_export_cmd(), stdout=f, check=True)
        print("Database exported successfully!")
    except FileNotFoundError:
        print("Error: 'mysqldump' command not found. Make sure MySQL is installed and 'mysqldump' is in your PATH.")
    except subprocess.CalledProcessError as e:
        print(f"Database export failed: {e}")
        print("Make sure 'mysqldump' is installed and available in your PATH.")

def import_database():
    """Imports the .sql file to the MySQL database."""
    print("Importing .sql file to the database...")
    try:
        with open(SQL_FILE, "r", encoding="utf-8") as f:
            subprocess.run(build_import_cmd(), stdin=f, check=True)
        print("Database updated successfully!")
    except FileNotFoundError as e:
        if "mysql" in str(e):
            print("Error: 'mysql' command not found. Make sure MySQL is installed and 'mysql' is in your PATH.")
        else:
            print(f"Error: SQL file not found: {SQL_FILE}")
    except subprocess.CalledProcessError as e:
        print(f"Database import failed: {e}")
        print("Make sure 'mysql' is installed and available in your PATH.")

def push_to_github():
    """Pushes the updated .sql file to the GitHub repository."""
    print("Pushing changes to GitHub...")
    try:
        os.chdir(GIT_REPO_PATH)
        subprocess.run(["git", "add", SQL_FILE], check=True)
        # Only commit if there are staged changes
        result = subprocess.run(["git", "diff", "--cached", "--quiet"])
        if result.returncode != 0:
            subprocess.run(["git", "commit", "-m", "Update database_backup.sql"], check=True)
            subprocess.run(["git", "push", "origin", "main"], check=True)
            print("Changes pushed to GitHub successfully!")
        else:
            print("No changes to commit to GitHub.")
    except FileNotFoundError:
        print("Error: 'git' command not found. Make sure Git is installed and in your PATH.")
    except subprocess.CalledProcessError as e:
        print(f"Git operation failed: {e}")
        print("Make sure Git is installed and the repository is properly configured.")

def main_loop():
    global db_last_export_time, file_last_modified_time

    while True:
        # Monitor for changes in the database
        current_time = datetime.now()
        if (current_time - db_last_export_time).total_seconds() >= 300:  # Check every 5 minutes
            print("Exporting database to sync changes...")
            export_database()
            db_last_export_time = current_time

            # Automatically push the updated file to GitHub
            push_to_github()

            # Update file_last_modified_time after export
            try:
                file_last_modified_time = os.path.getmtime(SQL_FILE)
            except FileNotFoundError:
                file_last_modified_time = 0

        # Monitor for changes in the .sql file
        try:
            current_file_modified_time = os.path.getmtime(SQL_FILE)
        except FileNotFoundError:
            current_file_modified_time = 0

        if current_file_modified_time != file_last_modified_time:
            print("Changes detected in the .sql file. Updating the database...")
            import_database()
            file_last_modified_time = current_file_modified_time

        time.sleep(10)  # Check for changes every 10 seconds

if __name__ == "__main__":
    main_loop()
