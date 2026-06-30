const express = require('express');
const router = express.Router();
const db = require('../config/db');

// GET user watchlist
router.get('/:user_id', async (req, res, next) => {
  const userId = parseInt(req.params.user_id);
  try {
    const query = `
      SELECT w.anime_id, w.status, a.anime_name, a.anime_image, a.anime_type
      FROM watchlist w 
      JOIN anime a ON w.anime_id = a.anime_id 
      WHERE w.user_id = $1 
      ORDER BY w.id DESC
    `;
    const result = await db.query(query, [userId]);
    res.json(result.rows);
  } catch (err) {
    next(err);
  }
});

// POST check/add/update status (from player or watchlist page)
router.post('/', async (req, res, next) => {
  const { user_id, anime_id, status } = req.body;
  if (!user_id || !anime_id || !status) {
    return res.status(400).json({ error: 'User ID, Anime ID, and Status are required' });
  }

  const userId = parseInt(user_id);
  const animeId = parseInt(anime_id);

  try {
    // Check if already in watchlist
    const checkQuery = 'SELECT status FROM watchlist WHERE user_id = $1 AND anime_id = $2';
    const checkRes = await db.query(checkQuery, [userId, animeId]);

    if (checkRes.rows.length > 0) {
      const currentStatus = checkRes.rows[0].status;
      if (currentStatus !== status) {
        // Update status
        const updateQuery = 'UPDATE watchlist SET status = $1 WHERE user_id = $2 AND anime_id = $3 RETURNING *';
        const updateRes = await db.query(updateQuery, [status, userId, animeId]);
        return res.json({ 
          message: 'Status updated in your watchlist!', 
          type: 'updated',
          watchlist: updateRes.rows[0] 
        });
      } else {
        return res.json({ 
          message: 'Already in your watchlist with this status!', 
          type: 'exists' 
        });
      }
    } else {
      // Insert new entry
      const insertQuery = 'INSERT INTO watchlist (user_id, anime_id, status, added_at) VALUES ($1, $2, $3, NOW()) RETURNING *';
      const insertRes = await db.query(insertQuery, [userId, animeId, status]);
      return res.json({ 
        message: 'Added to your watchlist!', 
        type: 'added',
        watchlist: insertRes.rows[0] 
      });
    }
  } catch (err) {
    next(err);
  }
});

// POST remove from watchlist
router.post('/remove', async (req, res, next) => {
  const { user_id, anime_id } = req.body;
  if (!user_id || !anime_id) {
    return res.status(400).json({ error: 'User ID and Anime ID are required' });
  }

  const userId = parseInt(user_id);
  const animeId = parseInt(anime_id);

  try {
    const query = 'DELETE FROM watchlist WHERE user_id = $1 AND anime_id = $2 RETURNING *';
    const result = await db.query(query, [userId, animeId]);
    if (result.rows.length === 0) {
      return res.status(404).json({ error: 'Watchlist entry not found' });
    }
    res.json({ message: 'Removed from watchlist successfully!' });
  } catch (err) {
    next(err);
  }
});

module.exports = router;
