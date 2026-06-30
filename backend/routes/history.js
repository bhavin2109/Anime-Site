const express = require('express');
const router = express.Router();
const db = require('../config/db');

// GET user continue watching history
router.get('/continue/:user_id', async (req, res, next) => {
  const userId = parseInt(req.params.user_id);
  try {
    const query = `
      SELECT h.anime_id, h.episode_id, h.watched_at, a.anime_name, a.anime_image, a.anime_type
      FROM history h
      JOIN anime a ON h.anime_id = a.anime_id
      INNER JOIN (
        SELECT anime_id, MAX(watched_at) AS max_watched_at 
        FROM history 
        WHERE user_id = $1 
        GROUP BY anime_id
      ) latest ON h.anime_id = latest.anime_id AND h.watched_at = latest.max_watched_at
      WHERE h.user_id = $2
      ORDER BY h.watched_at DESC
      LIMIT 10
    `;
    const result = await db.query(query, [userId, userId]);
    const historyList = result.rows;

    // For each item, resolve the episode number relative to the anime's episodes list
    const enrichedList = [];
    for (const item of historyList) {
      const episodesRes = await db.query(
        'SELECT episode_id FROM episodes WHERE anime_id = $1 ORDER BY episode_id ASC',
        [item.anime_id]
      );
      const episodes = episodesRes.rows;
      let lastEpisodeNumber = 1;
      
      for (let i = 0; i < episodes.length; i++) {
        if (episodes[i].episode_id === item.episode_id) {
          lastEpisodeNumber = i + 1;
          break;
        }
      }

      enrichedList.push({
        anime_id: item.anime_id,
        anime_name: item.anime_name,
        anime_image: item.anime_image,
        anime_type: item.anime_type,
        last_episode_id: item.episode_id,
        last_episode_number: lastEpisodeNumber,
        watched_at: item.watched_at
      });
    }

    res.json(enrichedList);
  } catch (err) {
    next(err);
  }
});

// POST log history (invoked when a user watches an episode)
router.post('/', async (req, res, next) => {
  const { user_id, anime_id, episode_id } = req.body;
  if (!user_id || !anime_id || !episode_id) {
    return res.status(400).json({ error: 'User ID, Anime ID, and Episode ID are required' });
  }

  const userId = parseInt(user_id);
  const animeId = parseInt(anime_id);
  const episodeId = parseInt(episode_id);

  try {
    // 1. Log or update history
    const checkHistory = await db.query(
      'SELECT id FROM history WHERE user_id = $1 AND anime_id = $2 AND episode_id = $3',
      [userId, animeId, episodeId]
    );

    if (checkHistory.rows.length > 0) {
      await db.query(
        'UPDATE history SET watched_at = NOW() WHERE user_id = $1 AND anime_id = $2 AND episode_id = $3',
        [userId, animeId, episodeId]
      );
    } else {
      await db.query(
        'INSERT INTO history (user_id, anime_id, episode_id, watched_at) VALUES ($1, $2, $3, NOW())',
        [userId, animeId, episodeId]
      );
    }

    // 2. Auto watchlist sync (set status to 'watching' if not completed/dropped or if not in watchlist)
    const checkWatchlist = await db.query(
      'SELECT status FROM watchlist WHERE user_id = $1 AND anime_id = $2',
      [userId, animeId]
    );

    const targetStatus = 'Watching'; // Store capitalized as used in watchlist page
    if (checkWatchlist.rows.length > 0) {
      const currentStatus = checkWatchlist.rows[0].status;
      // If they are watching/planning, update/set to 'Watching'
      if (currentStatus.toLowerCase() !== 'watching' && currentStatus.toLowerCase() !== 'completed') {
        await db.query(
          'UPDATE watchlist SET status = $1 WHERE user_id = $2 AND anime_id = $3',
          [targetStatus, userId, animeId]
        );
      }
    } else {
      // Add to watchlist as 'Watching'
      await db.query(
        'INSERT INTO watchlist (user_id, anime_id, status, added_at) VALUES ($1, $2, $3, NOW())',
        [userId, animeId, targetStatus]
      );
    }

    res.json({ message: 'History and Watchlist synchronized successfully.' });
  } catch (err) {
    next(err);
  }
});

module.exports = router;
