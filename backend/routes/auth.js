const express = require('express');
const router = express.Router();
const db = require('../config/db');
const multer = require('multer');
const path = require('path');
const fs = require('fs');

// Configure Multer for profile picture uploads
const storage = multer.diskStorage({
  destination: (req, file, cb) => {
    const uploadDir = path.join(__dirname, '../../assets/profile_pics');
    if (!fs.existsSync(uploadDir)) {
      fs.mkdirSync(uploadDir, { recursive: true });
    }
    cb(null, uploadDir);
  },
  filename: (req, file, cb) => {
    cb(null, Date.now() + '-' + file.originalname);
  }
});

const upload = multer({
  storage: storage,
  fileFilter: (req, file, cb) => {
    const filetypes = /jpeg|jpg|png|gif|webp/;
    const extname = filetypes.test(path.extname(file.originalname).toLowerCase());
    const mimetype = filetypes.test(file.mimetype);
    if (mimetype && extname) {
      return cb(null, true);
    }
    cb(new Error('Only images are allowed (jpeg, jpg, png, gif, webp)'));
  }
});

// Login route
router.post('/login', async (req, res, next) => {
  const { username, password } = req.body;
  if (!username || !password) {
    return res.status(400).json({ error: 'Please provide username and password' });
  }

  try {
    const result = await db.query('SELECT * FROM users WHERE username = $1', [username]);
    if (result.rows.length === 0) {
      return res.status(401).json({ error: 'Invalid username or password!' });
    }

    const user = result.rows[0];
    if (password !== user.password) {
      return res.status(401).json({ error: 'Invalid username or password!' });
    }

    // Success
    res.json({
      user_id: user.user_id,
      username: user.username,
      email: user.email,
      profile_picture: user.profile_picture,
      date_of_birth: user.date_of_birth
    });
  } catch (err) {
    next(err);
  }
});

// Signup route
router.post('/signup', async (req, res, next) => {
  const { username, email, password, confirm_password } = req.body;
  if (!username || !email || !password) {
    return res.status(400).json({ error: 'Please fill in all required fields' });
  }

  if (password !== confirm_password) {
    return res.status(400).json({ error: 'Passwords do not match!' });
  }

  try {
    // Check if user already exists
    const checkUser = await db.query('SELECT * FROM users WHERE username = $1 OR email = $2', [username, email]);
    if (checkUser.rows.length > 0) {
      return res.status(400).json({ error: 'Username or email already exists!' });
    }

    // Insert new user
    const result = await db.query(
      'INSERT INTO users (username, email, password) VALUES ($1, $2, $3) RETURNING user_id, username, email',
      [username, email, password]
    );

    res.status(201).json({
      message: 'Signup successful!',
      user: result.rows[0]
    });
  } catch (err) {
    next(err);
  }
});

// Admin login route
router.post('/admin-login', async (req, res, next) => {
  const { email, password } = req.body;
  if (!email || !password) {
    return res.status(400).json({ error: 'Please fill in all fields!' });
  }

  if (email !== 'admin@gmail.com') {
    return res.status(401).json({ error: 'Invalid email or password!' });
  }

  try {
    const result = await db.query('SELECT * FROM users WHERE email = $1', [email]);
    if (result.rows.length === 0) {
      return res.status(404).json({ error: 'Admin account not found.' });
    }

    const user = result.rows[0];
    if (password !== user.password) {
      return res.status(401).json({ error: 'Invalid email or password!' });
    }

    res.json({
      admin_id: user.user_id,
      username: user.username,
      email: user.email,
      isAdmin: true
    });
  } catch (err) {
    next(err);
  }
});

// Get user profile
router.get('/profile/:id', async (req, res, next) => {
  const userId = parseInt(req.params.id);
  try {
    const result = await db.query(
      'SELECT user_id, username, email, profile_picture, date_of_birth FROM users WHERE user_id = $1',
      [userId]
    );
    if (result.rows.length === 0) {
      return res.status(404).json({ error: 'User not found' });
    }
    res.json(result.rows[0]);
  } catch (err) {
    next(err);
  }
});

// Update profile route
router.put('/profile/:id', upload.single('profile_picture'), async (req, res, next) => {
  const userId = parseInt(req.params.id);
  const { username, email, date_of_birth } = req.body;

  try {
    // Get current profile
    const currentRes = await db.query('SELECT * FROM users WHERE user_id = $1', [userId]);
    if (currentRes.rows.length === 0) {
      return res.status(404).json({ error: 'User not found' });
    }

    const currentUser = currentRes.rows[0];
    const finalUsername = username !== undefined ? username : currentUser.username;
    const finalEmail = email !== undefined ? email : currentUser.email;
    const finalDob = date_of_birth !== undefined ? (date_of_birth === '' ? null : date_of_birth) : currentUser.date_of_birth;
    
    let finalProfilePic = currentUser.profile_picture;
    if (req.file) {
      finalProfilePic = req.file.filename;
    }

    // Update
    const result = await db.query(
      'UPDATE users SET username = $1, email = $2, profile_picture = $3, date_of_birth = $4 WHERE user_id = $5 RETURNING user_id, username, email, profile_picture, date_of_birth',
      [finalUsername, finalEmail, finalProfilePic, finalDob, userId]
    );

    res.json({
      message: 'Profile updated successfully.',
      user: result.rows[0]
    });
  } catch (err) {
    next(err);
  }
});

// Admin list users
router.get('/users', async (req, res, next) => {
  try {
    const result = await db.query('SELECT user_id, username, email, profile_picture, date_of_birth FROM users ORDER BY username');
    res.json(result.rows);
  } catch (err) {
    next(err);
  }
});

// Admin delete user
router.delete('/users/:id', async (req, res, next) => {
  const userId = parseInt(req.params.id);
  try {
    await db.query('DELETE FROM users WHERE user_id = $1', [userId]);
    res.json({ message: 'User removed successfully!' });
  } catch (err) {
    next(err);
  }
});

module.exports = router;
