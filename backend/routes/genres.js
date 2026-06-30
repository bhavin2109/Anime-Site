const express = require('express');
const router = express.Router();
const db = require('../config/db');

// GET all genres
router.get('/', async (req, res, next) => {
  try {
    const result = await db.query('SELECT genre_id, genre_name FROM genres ORDER BY genre_name');
    res.json(result.rows);
  } catch (err) {
    next(err);
  }
});

// Admin Add Genre
router.post('/', async (req, res, next) => {
  const { genre_name } = req.body;
  if (!genre_name) {
    return res.status(400).json({ error: 'Genre name is required' });
  }

  try {
    const checkGenre = await db.query('SELECT * FROM genres WHERE genre_name = $1', [genre_name]);
    if (checkGenre.rows.length > 0) {
      return res.status(400).json({ error: 'Genre already exists!' });
    }

    const result = await db.query(
      'INSERT INTO genres (genre_name) VALUES ($1) RETURNING *',
      [genre_name]
    );
    res.status(201).json({ message: 'Genre added successfully!', genre: result.rows[0] });
  } catch (err) {
    next(err);
  }
});

// Admin Delete Genre
router.delete('/:id', async (req, res, next) => {
  const genreId = parseInt(req.params.id);
  try {
    const result = await db.query('DELETE FROM genres WHERE genre_id = $1 RETURNING *', [genreId]);
    if (result.rows.length === 0) {
      return res.status(404).json({ error: 'Genre not found' });
    }
    res.json({ message: 'Genre removed successfully!' });
  } catch (err) {
    next(err);
  }
});

module.exports = router;
