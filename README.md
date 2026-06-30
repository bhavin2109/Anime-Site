# Anime Site (Modern React + Express + PostgreSQL Stack)

This is a modernized version of the Anime and Movie streaming site, fully migrated to a React + Node/Express + PostgreSQL stack.

## Folder Structure
- `/frontend`: Vite + React SPA (formerly `/client`)
- `/backend`: Node.js + Express API server (formerly `/server`)
- `/database`: Schema files and database seeding scripts
- `/assets`: Shared static assets served by the backend server

---

## Prerequisites

Before starting, make sure you have the following installed on your machine:
1. **Node.js** (v16.0.0 or higher)
2. **PostgreSQL** (installed and running locally)

---

## Installation & Setup

Follow these steps to set up and run the project locally:

### 1. Install Dependencies
In the root directory, run the command to install all packages for the root, frontend, and backend folders:
```bash
npm run install:all
```

### 2. Configure Environment Variables
Create a `.env` file inside the `backend` directory (you can copy the format from `backend/.env.example`):
```env
PORT=5000
PGUSER=postgres
PGPASSWORD=your_postgres_password
PGHOST=localhost
PGPORT=5432
PGDATABASE=anime_site
```
Ensure that the PostgreSQL credentials match your local setup.

### 3. Initialize & Migrate the Database
Run the migration script to automatically check/create the target PostgreSQL database, build the tables, and seed the initial dataset:
```bash
npm run db:migrate
```

---

## Running the Application

To run both the frontend and backend servers concurrently, execute the dev command from the root directory:
```bash
npm run dev
```

### Accessing the Apps
- **Frontend App**: Navigate to [http://localhost:3000](http://localhost:3000)
- **Backend API**: The backend server runs at [http://localhost:5000](http://localhost:5000)

---

## Admin Credentials
To access the admin panel:
- **Email**: `admin@gmail.com`
- **Password**: `admin`
