-- Create database
CREATE DATABASE IF NOT EXISTS student_notes;
USE student_notes;

-- Create Administrateur table
CREATE TABLE IF NOT EXISTS Administrateur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Create Student table
CREATE TABLE IF NOT EXISTS Student (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    admin_id INT,
    FOREIGN KEY (admin_id) REFERENCES Administrateur(id) ON DELETE SET NULL
);

-- Create Modules table
CREATE TABLE IF NOT EXISTS Modules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    student_id INT,
    FOREIGN KEY (student_id) REFERENCES Student(id) ON DELETE CASCADE
);

-- Create Notes table
CREATE TABLE IF NOT EXISTS Notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    commentaire TEXT NOT NULL,
    student_id INT,
    module_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES Student(id) ON DELETE CASCADE,
    FOREIGN KEY (module_id) REFERENCES Modules(id) ON DELETE CASCADE
);

-- Insert default admin
INSERT INTO Administrateur (username, password) VALUES ('admin', '$2y$10$8Ux8PVQu.Lw1mFA3QZQGk.XpwZep/gKLpVMhU1P.j6YSGfYdBPnw2');
-- Default password is 'admin123'
