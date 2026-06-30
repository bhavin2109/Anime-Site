const express = require('express');
const router = express.Router();
const db = require('../config/db');
const multer = require('multer');
const path = require('path');
const fs = require('fs');

// Configure Multer for anime thumbnail uploads
const storage = multer.diskStorage({
  destination: (req, file, cb) => {
    const uploadDir = path.join(__dirname, '../../assets/thumbnails');
    if (!fs.existsSync(uploadDir)) {
      fs.mkdirSync(uploadDir, { recursive: true });
    }
    cb(null, uploadDir);
  },
  filename: (req, file, cb) => {
    cb(null, file.originalname); // Keep original name like in PHP
  }
});

const upload = multer({ storage: storage });

// GET Featured anime
router.get('/featured', async (req, res, next) => {
  try {
    const query = `
      SELECT a.*
      FROM anime a
      WHERE (SELECT COUNT(*) FROM episodes e WHERE e.anime_id = a.anime_id) > 0
      ORDER BY RANDOM() LIMIT 1
    `;
    const result = await db.query(query);
    res.json(result.rows[0] || null);
  } catch (err) {
    next(err);
  }
});

// GET Trending anime
router.get('/trending', async (req, res, next) => {
  try {
    const query = 'SELECT * FROM anime ORDER BY RANDOM() LIMIT 10';
    const result = await db.query(query);
    res.json(result.rows);
  } catch (err) {
    next(err);
  }
});

// GET Anime by genre and type TV
router.get('/by-genre', async (req, res, next) => {
  const { genre } = req.query;
  if (!genre) {
    return res.status(400).json({ error: 'Genre query parameter required' });
  }
  try {
    const query = `
      SELECT a.anime_id, a.anime_name, a.anime_image, a.anime_type, COUNT(e.episode_id) AS episode_count
      FROM anime a 
      LEFT JOIN episodes e ON a.anime_id = e.anime_id
      WHERE a.genre = $1 AND a.anime_type = 'TV' AND e.episode_id IS NOT NULL
      GROUP BY a.anime_id, a.anime_name, a.anime_image, a.anime_type
      ORDER BY RANDOM() LIMIT 10
    `;
    const result = await db.query(query, [genre]);
    res.json(result.rows);
  } catch (err) {
    next(err);
  }
});

// GET Movies by genre
router.get('/movies-by-genre', async (req, res, next) => {
  const { genre } = req.query;
  if (!genre) {
    return res.status(400).json({ error: 'Genre query parameter required' });
  }
  try {
    const query = `
      SELECT a.anime_id, a.anime_name, a.anime_image, a.anime_type, COUNT(e.episode_id) AS episode_count
      FROM anime a 
      LEFT JOIN episodes e ON a.anime_id = e.anime_id
      WHERE a.genre = $1 AND a.anime_type = 'Movie'
      GROUP BY a.anime_id, a.anime_name, a.anime_image, a.anime_type
      HAVING COUNT(e.episode_id) > 0 
      ORDER BY RANDOM() LIMIT 10
    `;
    const result = await db.query(query, [genre]);
    res.json(result.rows);
  } catch (err) {
    next(err);
  }
});

// GET Upcoming Movies (0 episodes)
router.get('/upcoming-movies', async (req, res, next) => {
  try {
    const query = `
      SELECT a.anime_id, a.anime_name, a.anime_image, a.anime_type
      FROM anime a 
      LEFT JOIN episodes e ON a.anime_id = e.anime_id
      WHERE a.anime_type = 'Movie' 
      GROUP BY a.anime_id, a.anime_name, a.anime_image, a.anime_type
      HAVING COUNT(e.episode_id) = 0 
      ORDER BY RANDOM() LIMIT 10
    `;
    const result = await db.query(query);
    res.json(result.rows);
  } catch (err) {
    next(err);
  }
});

// GET Trending Movies (with episodes, limit 10)
router.get('/movies', async (req, res, next) => {
  try {
    const query = `
      SELECT a.* FROM anime a
      WHERE a.anime_type = 'Movie' AND EXISTS (SELECT 1 FROM episodes e WHERE e.anime_id = a.anime_id)
      ORDER BY RANDOM() LIMIT 10
    `;
    const result = await db.query(query);
    res.json(result.rows);
  } catch (err) {
    next(err);
  }
});

// GET Upcoming Anime (0 episodes)
router.get('/upcoming', async (req, res, next) => {
  try {
    const query = `
      SELECT * FROM anime 
      WHERE anime_id NOT IN (SELECT anime_id FROM episodes) 
      ORDER BY RANDOM() LIMIT 10
    `;
    const result = await db.query(query);
    res.json(result.rows);
  } catch (err) {
    next(err);
  }
});

// GET Search anime
router.get('/search', async (req, res, next) => {
  const { query } = req.query;
  if (!query) {
    return res.status(400).json({ error: 'Search query is empty' });
  }
  try {
    const sql = 'SELECT * FROM anime WHERE anime_name ILIKE $1 ORDER BY anime_name';
    const result = await db.query(sql, [`%${query}%`]);
    res.json(result.rows);
  } catch (err) {
    next(err);
  }
});

// GET All anime
router.get('/all', async (req, res, next) => {
  try {
    const query = `
      SELECT a.anime_id, a.anime_name, a.anime_type, a.anime_image, a.genre,
             (SELECT COUNT(*) FROM episodes e WHERE e.anime_id = a.anime_id) as episode_count
      FROM anime a 
      ORDER BY a.anime_name
    `;
    const result = await db.query(query);
    res.json(result.rows);
  } catch (err) {
    next(err);
  }
});

// GET single anime details
router.get('/:id', async (req, res, next) => {
  const animeId = parseInt(req.params.id);
  try {
    const result = await db.query('SELECT * FROM anime WHERE anime_id = $1', [animeId]);
    if (result.rows.length === 0) {
      return res.status(404).json({ error: 'Anime not found' });
    }
    res.json(result.rows[0]);
  } catch (err) {
    next(err);
  }
});

// Admin count dashboard stats
router.get('/stats/count', async (req, res, next) => {
  try {
    const animeRes = await db.query('SELECT COUNT(*) as count FROM anime');
    const moviesRes = await db.query("SELECT COUNT(*) as count FROM anime WHERE anime_type = 'Movie'");
    const usersRes = await db.query('SELECT COUNT(*) as count FROM users');
    
    res.json({
      anime: parseInt(animeRes.rows[0].count),
      movies: parseInt(moviesRes.rows[0].count),
      users: parseInt(usersRes.rows[0].count)
    });
  } catch (err) {
    next(err);
  }
});

// Admin Add Anime
router.post('/', upload.single('anime_image'), async (req, res, next) => {
  const { anime_name, anime_type, genre } = req.body;
  if (!anime_name || !anime_type || !genre) {
    return res.status(400).json({ error: 'All fields are required!' });
  }

  let anime_image = '';
  if (req.file) {
    anime_image = req.file.filename;
  } else if (req.body.anime_image) {
    anime_image = req.body.anime_image;
  } else {
    return res.status(400).json({ error: 'Please upload an image!' });
  }

  try {
    const result = await db.query(
      'INSERT INTO anime (anime_name, anime_image, anime_type, genre) VALUES ($1, $2, $3, $4) RETURNING *',
      [anime_name, anime_image, anime_type, genre]
    );
    res.status(201).json({ message: 'Anime added successfully!', anime: result.rows[0] });
  } catch (err) {
    next(err);
  }
});

// Admin Update Anime
router.put('/:id', upload.single('anime_image'), async (req, res, next) => {
  const animeId = parseInt(req.params.id);
  const { anime_name, anime_type, genre } = req.body;

  try {
    const checkRes = await db.query('SELECT * FROM anime WHERE anime_id = $1', [animeId]);
    if (checkRes.rows.length === 0) {
      return res.status(404).json({ error: 'Anime not found' });
    }

    const currentAnime = checkRes.rows[0];
    const name = anime_name !== undefined ? anime_name : currentAnime.anime_name;
    const type = anime_type !== undefined ? anime_type : currentAnime.anime_type;
    const gen = genre !== undefined ? genre : currentAnime.genre;
    
    let image = currentAnime.anime_image;
    if (req.file) {
      image = req.file.filename;
    }

    const result = await db.query(
      'UPDATE anime SET anime_name = $1, anime_image = $2, anime_type = $3, genre = $4 WHERE anime_id = $5 RETURNING *',
      [name, image, type, gen, animeId]
    );

    res.json({ message: 'Anime updated successfully!', anime: result.rows[0] });
  } catch (err) {
    next(err);
  }
});

// Admin Delete Anime
router.delete('/:id', async (req, res, next) => {
  const animeId = parseInt(req.params.id);
  try {
    const result = await db.query('DELETE FROM anime WHERE anime_id = $1 RETURNING *', [animeId]);
    if (result.rows.length === 0) {
      return res.status(404).json({ error: 'Anime not found' });
    }
    res.json({ message: 'Anime removed successfully!' });
  } catch (err) {
    next(err);
  }
});

module.exports = router;
