-- Create database
CREATE DATABASE IF NOT EXISTS blacklist_alliance;
USE blacklist_alliance;

-- Table for storing BLA lookup results
CREATE TABLE IF NOT EXISTS bla_lookups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    phone_number VARCHAR(10) NOT NULL UNIQUE,
    response_data TEXT,
    lookup_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table for CRM data (example structure - modify according to your CRM)
CREATE TABLE IF NOT EXISTS crm_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    First_Name VARCHAR(50),
    Last_Name VARCHAR(50),
    Phone VARCHAR(10) NOT NULL UNIQUE,
    State VARCHAR(2),
    Disposition VARCHAR(50),
    Line VARCHAR(50),
    `Group` VARCHAR(50)
);

-- Insert some sample CRM data
INSERT INTO crm_data (First_Name, Last_Name, Phone, State, Disposition, Line, `Group`) VALUES
('John', 'Doe', '1234567890', 'CA', 'Interested', 'Line1', 'GroupA'),
('Jane', 'Smith', '0987654321', 'NY', 'Not Interested', 'Line2', 'GroupB');
