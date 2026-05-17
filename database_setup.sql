
CREATE DATABASE IF NOT EXISTS queuebank CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE queuebank;

CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(20) NOT NULL UNIQUE,
    service_name VARCHAR(100) NOT NULL,
    service_icon VARCHAR(20) NOT NULL,
    duration INT NOT NULL,
    date DATE NOT NULL,
    slot VARCHAR(10) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    notes TEXT,
    status ENUM('Confirmed','Completed','Cancelled','No-show') DEFAULT 'Confirmed',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS staff (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id VARCHAR(20) NOT NULL UNIQUE,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('Teller','Senior Teller','Loans Officer','Supervisor') DEFAULT 'Teller',
    status ENUM('Active','Inactive') DEFAULT 'Active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    icon VARCHAR(20) NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    duration INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS support_tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ticket_id VARCHAR(20) NOT NULL UNIQUE,
    customer_name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('Open','In Progress','Resolved') DEFAULT 'Open',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

INSERT INTO staff (employee_id, name, email, password, role, status) VALUES
('EMP001', 'Arta Hoxha', 'arta@queuebank.al', 'staff123', 'Teller', 'Active'),
('EMP002', 'Besi Kola', 'besi@queuebank.al', 'pass456', 'Senior Teller', 'Active'),
('EMP003', 'Drita Leka', 'drita@queuebank.al', 'loans789', 'Loans Officer', 'Active'),
('EMP004', 'Gent Muja', 'gent@queuebank.al', 'super123', 'Supervisor', 'Active');

INSERT INTO services (icon, name, description, duration) VALUES
('bank', 'Account Opening', 'Open a current or savings account', 30),
('loan', 'Loan Application', 'Personal and business loan requests', 45),
('card', 'Card Services', 'Debit and credit card management', 20),
('transfer', 'Wire Transfer', 'Send money to another account', 25),
('invest', 'Investment Advice', 'Financial planning and investments', 60),
('support', 'General Support', 'Any other banking assistance', 20);