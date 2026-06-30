-- PostgreSQL Schema initialization for Anime site

-- 1. Users Table
CREATE TABLE IF NOT EXISTS users (
    user_id SERIAL PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(50) NOT NULL,
    profile_picture VARCHAR(255) DEFAULT NULL,
    date_of_birth DATE DEFAULT NULL
);

-- 2. Anime Table
CREATE TABLE IF NOT EXISTS anime (
    anime_id SERIAL PRIMARY KEY,
    anime_name VARCHAR(255) NOT NULL,
    anime_image VARCHAR(255) NOT NULL,
    anime_type VARCHAR(100) NOT NULL,
    genre VARCHAR(100) NOT NULL
);

-- 3. Episodes Table
CREATE TABLE IF NOT EXISTS episodes (
    episode_id SERIAL PRIMARY KEY,
    anime_id INT NOT NULL REFERENCES anime(anime_id) ON DELETE CASCADE ON UPDATE CASCADE,
    episode_url TEXT
);

-- 4. Genres Table
CREATE TABLE IF NOT EXISTS genres (
    genre_id SERIAL PRIMARY KEY,
    genre_name VARCHAR(50) NOT NULL UNIQUE
);

-- 5. Type Table
CREATE TABLE IF NOT EXISTS type (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
);

-- 6. History Table
CREATE TABLE IF NOT EXISTS history (
    id SERIAL PRIMARY KEY,
    user_id INT REFERENCES users(user_id) ON DELETE CASCADE,
    anime_id INT REFERENCES anime(anime_id) ON DELETE CASCADE,
    episode_id INT REFERENCES episodes(episode_id) ON DELETE CASCADE,
    watched_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 7. Watchlist Table
CREATE TABLE IF NOT EXISTS watchlist (
    id SERIAL PRIMARY KEY,
    user_id INT REFERENCES users(user_id) ON DELETE CASCADE,
    anime_id INT REFERENCES anime(anime_id) ON DELETE CASCADE,
    status VARCHAR(50) NOT NULL,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
