
CREATE DATABASE IF NOT EXISTS school_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE school_management;

CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_name VARCHAR(100) NOT NULL,
    roll_no VARCHAR(30) UNIQUE NOT NULL,
    email VARCHAR(100) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    class_name VARCHAR(20) NOT NULL,
    section VARCHAR(10) NOT NULL,
    dob DATE NOT NULL,
    gender VARCHAR(20) NOT NULL,
    parent_name VARCHAR(100) NOT NULL,
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;


INSERT INTO admin_users (username, password_hash, full_name) 
VALUES ('admin', 'admin123', 'Principal Administrator')
ON DUPLICATE KEY UPDATE username=username;


CREATE TABLE IF NOT EXISTS admissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    applicant_name VARCHAR(100) NOT NULL,
    applied_class VARCHAR(20) NOT NULL,
    guardian_name VARCHAR(100) NOT NULL,
    contact_phone VARCHAR(15) NOT NULL,
    fee_amount DECIMAL(10,2) NOT NULL DEFAULT 500.00,
    payment_status ENUM('Paid', 'Pending', 'Failed') DEFAULT 'Pending',
    transaction_id VARCHAR(50) DEFAULT NULL,
    admission_status ENUM('Under Review', 'Approved', 'Rejected') DEFAULT 'Under Review',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    class_name VARCHAR(20) NOT NULL,
    attendance_date DATE NOT NULL,
    status ENUM('Present', 'Absent', 'Late') DEFAULT 'Present',
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
) ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS marks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    exam_name VARCHAR(50) NOT NULL,
    subject VARCHAR(50) NOT NULL,
    marks_obtained DECIMAL(5,2) NOT NULL,
    total_marks DECIMAL(5,2) NOT NULL DEFAULT 100.00,
    grade VARCHAR(5) NOT NULL,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
) ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS study_materials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    class_name VARCHAR(20) NOT NULL,
    subject VARCHAR(50) NOT NULL,
    file_type VARCHAR(50) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    uploaded_by VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
INSERT INTO students (student_name, roll_no, email, password_hash, phone, class_name, section, dob, gender, parent_name, address) VALUES
('Aarav Sharma', 'BFS-101', 'aarav.sharma@email.com', 'xyz12345', '9876543210', '10th', 'A', '2010-05-14', 'Male', 'Rajesh Sharma', 'Pote Estate, Amravati'),
('Ananya O\'Reilly', 'BFS-102', 'ananya.oreilly@email.com', 'abc12345', '9876543211', '10th', 'B', '2010-08-22', 'Female', 'Patrick O\'Reilly', 'Kathora Road, Amravati'),
('Rohan Verma', 'BFS-103', 'rohan.verma@email.com', 'adcdefgh', '9876543212', '9th', 'A', '2011-01-10', 'Male', 'Suresh Verma', 'Camp Road, Amravati'),
('Priya Nair', 'BFS-104', 'priya.nair@email.com', 'abc98765', '9876543213', '9th', 'B', '2011-11-05', 'Female', 'Venkat Nair', 'Rukmini Nagar, Amravati'),
('Kabir Khan', 'BFS-105', 'kabir.khan@email.com', '123456789', '9876543214', '8th', 'A', '2012-03-19', 'Male', 'Imran Khan', 'Friends Colony, Amravati')
ON DUPLICATE KEY UPDATE student_name=student_name;

INSERT INTO admissions (applicant_name, applied_class, guardian_name, contact_phone, fee_amount, payment_status, transaction_id, admission_status) VALUES
('Siddharth Rao', '10th', 'Manoj Rao', '9876500111', 500.00, 'Paid', 'TXN9843211', 'Approved'),
('Neha Joshi', '9th', 'Sunil Joshi', '9876500222', 500.00, 'Pending', NULL, 'Under Review');

INSERT INTO study_materials (title, class_name, subject, file_type, file_path, uploaded_by) VALUES
('Algebra Quick Revision Notes', '10th', 'Mathematics', 'PDF', '#', 'Prof. Kulkarni'),
('Chemical Reactions & Equations', '10th', 'Science', 'PDF', '#', 'Dr. Deshmukh'),
('Grammar Practice Worksheets', '9th', 'English', 'DOCX', '#', 'Mrs. Patil');
