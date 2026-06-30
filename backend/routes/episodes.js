const express = require('express');
const router = express.Router();
const db = require('../config/db');

// GET all episodes for an anime
router.get('/by-anime/:anime_id', async (req, res, next) => {
  const animeId = parseInt(req.params.anime_id);
  try {
    const query = 'SELECT episode_id, anime_id, episode_url FROM episodes WHERE anime_id = $1 ORDER BY episode_id ASC';
    const result = await db.query(query, [animeId]);
    res.json(result.rows);
  } catch (err) {
    next(err);
  }
});

// GET specific episode details (with anime metadata)
router.get('/:episode_id', async (req, res, next) => {
  const episodeId = parseInt(req.params.episode_id);
  const animeId = parseInt(req.query.anime_id);
  try {
    const query = `
      SELECT e.episode_id, e.anime_id, a.anime_name, a.anime_image, e.episode_url 
      FROM episodes e 
      JOIN anime a ON e.anime_id = a.anime_id 
      WHERE e.episode_id = $1 AND e.anime_id = $2
    `;
    const result = await db.query(query, [episodeId, animeId]);
    if (result.rows.length === 0) {
      return res.status(404).json({ error: 'Episode not found' });
    }
    res.json(result.rows[0]);
  } catch (err) {
    next(err);
  }
});

// Admin Add Episode
router.post('/', async (req, res, next) => {
  const { anime_id, episode_url } = req.body;
  if (!anime_id || !episode_url) {
    return res.status(400).json({ error: 'Anime ID and Episode URL are required' });
  }

  try {
    const result = await db.query(
      'INSERT INTO episodes (anime_id, episode_url) VALUES ($1, $2) RETURNING *',
      [parseInt(anime_id), episode_url]
    );
    res.status(201).json({ message: 'Episode added successfully!', episode: result.rows[0] });
  } catch (err) {
    next(err);
  }
});

// Admin Update Episode
router.put('/:id', async (req, res, next) => {
  const episodeId = parseInt(req.params.id);
  const { episode_url } = req.body;
  if (!episode_url) {
    return res.status(400).json({ error: 'Episode URL is required' });
  }

  try {
    const result = await db.query(
      'UPDATE episodes SET episode_url = $1 WHERE episode_id = $2 RETURNING *',
      [episode_url, episodeId]
    );
    if (result.rows.length === 0) {
      return res.status(404).json({ error: 'Episode not found' });
    }
    res.json({ message: 'Episode updated successfully!', episode: result.rows[0] });
  } catch (err) {
    next(err);
  }
});

// Admin Delete Episode
router.delete('/:id', async (req, res, next) => {
  const episodeId = parseInt(req.params.id);
  try {
    const result = await db.query('DELETE FROM episodes WHERE episode_id = $1 RETURNING *', [episodeId]);
    if (result.rows.length === 0) {
      return res.status(404).json({ error: 'Episode not found' });
    }
    res.json({ message: 'Episode removed successfully!' });
  } catch (err) {
    next(err);
  }
});

module.exports = router;
