-- Digital Portfolio Management System
-- Database schema
-- Run this file in phpMyAdmin or MySQL CLI before using the system

CREATE DATABASE IF NOT EXISTS portfolio_db;
USE portfolio_db;

-- User management module
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Portfolio items table
CREATE TABLE IF NOT EXISTS portfolio_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    category VARCHAR(50) NOT NULL,
    description TEXT,
    tools_used VARCHAR(150),
    image_path VARCHAR(255),
    date_created DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Sample category values to use in the form: Animation, Graphic Design, Video Editing, 3D Modeling, Photography, Web/Multimedia
