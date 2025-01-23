from watchdog.observers import Observer
from watchdog.events import FileSystemEventHandler
import time
import subprocess
import os

SQL_FILE = "C:/xampp/htdocs/Projects/Anime-Site/database/anime_site.sql"
GIT_REPO_PATH = "C:/xampp/htdocs/projects/anime-site/"  # Path to the GitHub repository

class DatabaseHandler(FileSystemEventHandler):
    def on_modified(self, event):
        if event.src_path == SQL_FILE:
            print(f"Detected changes in {SQL_FILE}. Pushing to GitHub...")
            push_to_github()

def push_to_github():
    """Pushes changes to the GitHub repository."""
    print("Pushing changes to GitHub...")
    try:
        os.chdir(GIT_REPO_PATH)
        subprocess.run(["git", "add", "."], check=True)
        subprocess.run(["git", "commit", "-m", "Auto-update database_backup.sql"], check=True)
        subprocess.run(["git", "push", "origin", "main"], check=True)
        print("Changes pushed to GitHub successfully!")
    except subprocess.CalledProcessError as e:
        print(f"Git operation failed: {e}")

if __name__ == "__main__":
    event_handler = DatabaseHandler()
    observer = Observer()
    observer.schedule(event_handler, path=os.path.dirname(SQL_FILE), recursive=False)
    observer.start()
    print(f"Monitoring changes to {SQL_FILE}...")
    try:
        while True:
            time.sleep(1)
    except KeyboardInterrupt:
        observer.stop()
    observer.join()
