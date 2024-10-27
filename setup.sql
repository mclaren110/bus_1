-- Create database
CREATE DATABASE IF NOT EXISTS bus_inventory_db;

-- Use the created database
USE bus_inventory_db;

-- Create bus_parts table with additional fields
CREATE TABLE IF NOT EXISTS bus_parts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    part_number VARCHAR(255) NOT NULL,
    description VARCHAR(255) NOT NULL,
    quantity INT NOT NULL,
    min_reorder_qty INT NOT NULL,
    last_reordered_date DATE,
    supplier_info VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create users table for login
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- Password should be hashed
    first_name VARCHAR(50) NOT NULL, -- New field for first name
    last_name VARCHAR(50) NOT NULL, -- New field for last name
    is_active TINYINT(1) NOT NULL DEFAULT 1, -- Active status defaults to 1 (active)
    is_approved TINYINT(1) DEFAULT 0, -- Approval status
    role ENUM('admin', 'user') NOT NULL DEFAULT 'admin', -- User role
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create suppliers table
CREATE TABLE IF NOT EXISTS suppliers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    contact_info VARCHAR(255) NOT NULL,
    reliability_rating INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


