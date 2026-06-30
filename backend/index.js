const express = require('express');
const cors = require('cors');
const path = require('path');
require('dotenv').config();

const app = express();
const PORT = process.env.PORT || 5000;

// Middleware
app.use(cors());
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// Serve static assets from original assets folder directly (saving copy overhead)
app.use('/assets', express.static(path.join(__dirname, '../assets')));

// API routes
app.use('/api/auth', require('./routes/auth'));
app.use('/api/anime', require('./routes/anime'));
app.use('/api/episodes', require('./routes/episodes'));
app.use('/api/watchlist', require('./routes/watchlist'));
app.use('/api/history', require('./routes/history'));
app.use('/api/genres', require('./routes/genres'));

// Default route
app.get('/', (req, res) => {
  res.json({ message: 'Anime site streaming API is online!' });
});

// Global error handler
app.use((err, req, res, next) => {
  console.error(err.stack);
  res.status(500).json({ error: 'Internal Server Error', message: err.message });
});

app.listen(PORT, () => {
  console.log(`Server is running on port ${PORT}`);
});
