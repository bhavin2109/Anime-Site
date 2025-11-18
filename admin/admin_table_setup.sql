-- Admin user setup SQL
-- Run this SQL in phpMyAdmin to create/update the admin user in the users table
-- This will insert the admin user if it doesn't exist, or update it if it does

-- Insert admin user (if not exists)
INSERT IGNORE INTO `users` (`username`, `email`, `password`) 
VALUES ('admin', 'admin@gmail.com', 'admin');

-- Update admin user if it already exists
UPDATE `users` 
SET `password` = 'admin', `username` = 'admin' 
WHERE `email` = 'admin@gmail.com';

-- Default admin credentials:
-- Email: admin@gmail.com
-- Password: admin
