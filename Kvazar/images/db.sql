-- Create the database with proper encoding
CREATE DATABASE IF NOT EXISTS Kvazar
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

-- Use the database
USE Kvazar;

-- Create Clients table
CREATE TABLE IF NOT EXISTS Clients (
    Client_id INT PRIMARY KEY AUTO_INCREMENT,
    Company_type VARCHAR(50) NOT NULL,
    Full_Company_name TEXT NOT NULL,
    Short_Company_name VARCHAR(255) NOT NULL,
    INN VARCHAR(12) NOT NULL,
    KPP VARCHAR(9),
    OGRN VARCHAR(15),
    Physical_address TEXT,
    Legal_address TEXT,
    Bank_name VARCHAR(255),
    BIK VARCHAR(9),
    Settlement_account VARCHAR(20),
    Correspondent_account VARCHAR(20),
    Contact_person VARCHAR(255),
    Contact_person_position VARCHAR(100),
    Contact_person_phone VARCHAR(20),
    Contact_person_email VARCHAR(255),
    Head_position VARCHAR(100),
    Head_name VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_inn (INN)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create Users table
CREATE TABLE IF NOT EXISTS Users (
    User_id INT PRIMARY KEY AUTO_INCREMENT,
    Full_name VARCHAR(255) NOT NULL,
    Client_id INT,
    Position VARCHAR(100),
    Phone VARCHAR(20),
    Email VARCHAR(255),
    Login VARCHAR(50) NOT NULL UNIQUE,
    Password VARCHAR(255) NOT NULL,
    Role ENUM('admin', 'client', 'courier', 'agent', 'ceo') NOT NULL DEFAULT 'client',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (Client_id) REFERENCES Clients(Client_id) ON DELETE SET NULL,
    UNIQUE KEY unique_login (Login),
    UNIQUE KEY unique_email (Email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create indexes for better performance
CREATE INDEX idx_client ON Users(Client_id);
CREATE INDEX idx_inn ON Clients(INN);
CREATE INDEX idx_login ON Users(Login);

-- Insert admin user (make sure to replace the password with a secure hashed password in production)
INSERT INTO Users (
    Full_name,
    Login,
    Password,
    Role,
    Email
) VALUES (
    'System Administrator',
    'admin',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: 'password'
    'admin',
    'admin@example.com'
);

CREATE TABLE auth_logs (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(20),
    ip_address VARCHAR(45),
    user_agent VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL
);

CREATE TABLE action_logs (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action_type VARCHAR(50),
    table_name VARCHAR(100),
    record_id INT,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    description TEXT,
    ip_address VARCHAR(45),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL
);

ALTER TABLE Users
MODIFY COLUMN Role ENUM('admin', 'client', 'courier', 'agent', 'ceo') NOT NULL DEFAULT 'client';

-- Create Couriers table
CREATE TABLE IF NOT EXISTS Couriers (
    Courier_id INT PRIMARY KEY AUTO_INCREMENT,
    Company_type VARCHAR(50) NOT NULL,
    Full_Company_name TEXT NOT NULL,
    Short_Company_name VARCHAR(255) NOT NULL,
    INN VARCHAR(12) NOT NULL,
    KPP VARCHAR(9),
    OGRN VARCHAR(15),
    Physical_address TEXT,
    Legal_address TEXT,
    Bank_name VARCHAR(255),
    BIK VARCHAR(9),
    Settlement_account VARCHAR(20),
    Correspondent_account VARCHAR(20),
    Contact_person VARCHAR(255),
    Contact_person_position VARCHAR(100),
    Contact_person_phone VARCHAR(20),
    Contact_person_email VARCHAR(255),
    Head_position VARCHAR(100),
    Head_name VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_inn (INN)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create Agents table
CREATE TABLE IF NOT EXISTS Agents (
    Agent_id INT PRIMARY KEY AUTO_INCREMENT,
    Company_type VARCHAR(50) NOT NULL,
    Full_Company_name TEXT NOT NULL,
    Short_Company_name VARCHAR(255) NOT NULL,
    INN VARCHAR(12) NOT NULL,
    KPP VARCHAR(9),
    OGRN VARCHAR(15),
    Physical_address TEXT,
    Legal_address TEXT,
    Bank_name VARCHAR(255),
    BIK VARCHAR(9),
    Settlement_account VARCHAR(20),
    Correspondent_account VARCHAR(20),
    Contact_person VARCHAR(255),
    Contact_person_position VARCHAR(100),
    Contact_person_phone VARCHAR(20),
    Contact_person_email VARCHAR(255),
    Head_position VARCHAR(100),
    Head_name VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_inn (INN)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Drop the existing foreign key constraint
ALTER TABLE Users
DROP FOREIGN KEY users_ibfk_1;

-- Modify the Client_id column to allow any integer value
ALTER TABLE Users
MODIFY COLUMN Client_id INT NULL;



-- Create new tables
CREATE TABLE Orders (
    Order_id INT AUTO_INCREMENT PRIMARY KEY,
    User_id INT NOT NULL,
    Contractor VARCHAR(255),
    Courier_id INT,
    Order_date DATE,
    Shipping_type VARCHAR(255),
    Vehicle_type VARCHAR(255),
    Weight DECIMAL(20,4),
    Weight_unit VARCHAR(50),
    Volume DECIMAL(20,4),
    Cargo_type VARCHAR(255),
    Temperature DECIMAL(20,4),
    Temperature_record TEXT,
    Loading_type VARCHAR(255),
    Packing_type VARCHAR(255),
    Quantity INT,
    Size VARCHAR(255),
    Cargo_price DECIMAL(20,4),
    Rate DECIMAL(20,4),
    Insurance_price DECIMAL(20,4),
    Rate_2 DECIMAL(20,4),
    Hours INT,
    Extra_hours INT,
    Quantity_contractor INT,
    Total_price_vehicle DECIMAL(20,4),
    Total_price_extra_service DECIMAL(20,4),
    Comment TEXT,
    Created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (User_id) REFERENCES Users(User_id),
    FOREIGN KEY (Courier_id) REFERENCES Couriers(Courier_id)
);

CREATE TABLE Vehicles (
    Vehicle_id INT AUTO_INCREMENT PRIMARY KEY,
    Courier_id INT NOT NULL,
    Brand VARCHAR(255),
    Plate_number VARCHAR(50),
    FOREIGN KEY (Courier_id) REFERENCES Couriers(Courier_id)
);

CREATE TABLE Drivers (
    Driver_id INT AUTO_INCREMENT PRIMARY KEY,
    Courier_id INT NOT NULL,
    Name VARCHAR(255),
    Phone_number VARCHAR(50),
    Passport VARCHAR(255),
    FOREIGN KEY (Courier_id) REFERENCES Couriers(Courier_id)
);

CREATE TABLE Drivers_list (
    Order_id INT,
    Driver_id INT,
    Created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    Created_by INT,
    PRIMARY KEY (Order_id, Driver_id),
    FOREIGN KEY (Order_id) REFERENCES Orders(Order_id),
    FOREIGN KEY (Driver_id) REFERENCES Drivers(Driver_id),
    FOREIGN KEY (Created_by) REFERENCES Users(User_id)
);

CREATE TABLE Extra_service (
    Extra_service_id INT AUTO_INCREMENT PRIMARY KEY,
    Order_id INT,
    Service_name VARCHAR(255),
    Service_price DECIMAL(20,4),
    Quantity INT,
    Total DECIMAL(20,4),
    FOREIGN KEY (Order_id) REFERENCES Orders(Order_id)
);

CREATE TABLE Points (
    Point_id INT AUTO_INCREMENT PRIMARY KEY,
    Order_id INT,
    Position INT,
    Action_type VARCHAR(255),
    Date DATE,
    Time TIME,
    Timestamp TIMESTAMP,
    Address_Loading TEXT,
    Company_name VARCHAR(255),
    FOREIGN KEY (Order_id) REFERENCES Orders(Order_id)
);

CREATE TABLE Contact_list (
    Contact_list_id INT AUTO_INCREMENT PRIMARY KEY,
    Point_id INT,
    Name VARCHAR(255),
    Phone_number VARCHAR(50),
    FOREIGN KEY (Point_id) REFERENCES Points(Point_id)
);

CREATE TABLE Attached_files (
    Attached_file_id INT AUTO_INCREMENT PRIMARY KEY,
    Order_id INT,
    File_name VARCHAR(255),
    File_type VARCHAR(100),
    File_size INT,
    File_data MEDIUMBLOB,
    Uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    User_id INT,
    FOREIGN KEY (Order_id) REFERENCES Orders(Order_id),
    FOREIGN KEY (User_id) REFERENCES Users(User_id)
);

ALTER TABLE Orders 
ADD COLUMN Client_id INT AFTER User_id;

-- Add foreign key constraint
ALTER TABLE Orders
ADD CONSTRAINT fk_orders_client
FOREIGN KEY (Client_id) REFERENCES Clients(Client_id);

-- Add index for better performance
ALTER TABLE Orders
ADD INDEX idx_client_id (Client_id);

CREATE TABLE IF NOT EXISTS Contractors (
    Contractors_id INT PRIMARY KEY AUTO_INCREMENT,
    Company_type VARCHAR(50) NOT NULL,
    Full_Company_name TEXT NOT NULL,
    Short_Company_name VARCHAR(255) NOT NULL,
    INN INT,
    KPP VARCHAR(9),
    OGRN VARCHAR(15),
    Physical_address TEXT,
    Legal_address TEXT,
    Bank_name VARCHAR(255),
    BIK VARCHAR(9),
    Settlement_account VARCHAR(20),
    Correspondent_account VARCHAR(20),
    Contact_person VARCHAR(255),
    Contact_person_position VARCHAR(100),
    Contact_person_phone VARCHAR(20),
    Contact_person_email VARCHAR(255),
    Head_position VARCHAR(100),
    Head_name VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create Client Contracts table
CREATE TABLE Client_contracts (
    Contract_id INT PRIMARY KEY AUTO_INCREMENT,
    Client_id INT NOT NULL,
    Contract_type VARCHAR(100),
    Contract_number VARCHAR(50),
    Contract_date DATE,
    Created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    Created_by INT,
    Contract_status VARCHAR(50) NOT NULL DEFAULT 'pending',
    FOREIGN KEY (Client_id) REFERENCES Clients(Client_id),
    FOREIGN KEY (Created_by) REFERENCES Users(User_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create Courier Contracts table
CREATE TABLE Courier_contracts (
    Contract_id INT PRIMARY KEY AUTO_INCREMENT,
    Courier_id INT NOT NULL,
    Contract_type VARCHAR(100),
    Contract_number VARCHAR(50),
    Contract_date DATE,
    Created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    Created_by INT,
    Contract_status VARCHAR(50) NOT NULL DEFAULT 'pending',
    FOREIGN KEY (Courier_id) REFERENCES Couriers(Courier_id),
    FOREIGN KEY (Created_by) REFERENCES Users(User_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create Agent Contracts table
CREATE TABLE Agent_contracts (
    Contract_id INT PRIMARY KEY AUTO_INCREMENT,
    Agent_id INT NOT NULL,
    Contract_type VARCHAR(100),
    Contract_number VARCHAR(50),
    Contract_date DATE,
    Created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    Created_by INT,
    Contract_status VARCHAR(50) NOT NULL DEFAULT 'pending',
    FOREIGN KEY (Agent_id) REFERENCES Agents(Agent_id),
    FOREIGN KEY (Created_by) REFERENCES Users(User_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Drop old columns from Clients table
ALTER TABLE Clients
    DROP COLUMN Contract_type,
    DROP COLUMN Contract_number,
    DROP COLUMN Contract_date;

-- Drop old columns from Couriers table
ALTER TABLE Couriers
    DROP COLUMN Contract_type,
    DROP COLUMN Contract_number,
    DROP COLUMN Contract_date;

-- Drop old columns from Agents table
ALTER TABLE Agents
    DROP COLUMN Contract_type,
    DROP COLUMN Contract_number,
    DROP COLUMN Contract_date;

CREATE TABLE list_contract_type (
    Type_id INT PRIMARY KEY AUTO_INCREMENT,
    Type_name VARCHAR(100) NOT NULL,
    Created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Created_by INT,
    FOREIGN KEY (Created_by) REFERENCES Users(User_id)
);

-- Create table for contract statuses
CREATE TABLE list_contract_status (
    Status_id INT PRIMARY KEY AUTO_INCREMENT,
    Status_name VARCHAR(100) NOT NULL,
    Status_color VARCHAR(50) NOT NULL,  -- For storing color codes like #FF0000 or rgb values
    Created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Created_by INT,
    FOREIGN KEY (Created_by) REFERENCES Users(User_id)
);

CREATE TABLE list_actions_passwords (
    Action_id INT PRIMARY KEY AUTO_INCREMENT,
    Action_name VARCHAR(50) NOT NULL,
    Action_password CHAR(4) NOT NULL,
    Created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Created_by INT,
    FOREIGN KEY (Created_by) REFERENCES Users(User_id)
);

INSERT INTO list_contract_status (Status_name, Status_color) VALUES
('Active', '#2ecc71'),     -- Green
('Pending', '#f1c40f'),    -- Yellow
('Expired', '#e74c3c'),    -- Red
('Terminated', '#95a5a6'); -- Gray

ALTER TABLE Orders 
ADD COLUMN Status INT AFTER Comment;

-- Create list_partners_status table
CREATE TABLE list_partners_status (
    Status_id INT PRIMARY KEY AUTO_INCREMENT,
    Status_name VARCHAR(50) NOT NULL,
    Status_color VARCHAR(7) NOT NULL,
    Created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default statuses
INSERT INTO list_partners_status (Status_name, Status_color) VALUES
('Active', '#2ecc71'),    -- Green
('Pending', '#f1c40f'),   -- Yellow (default)
('Inactive', '#e74c3c'),  -- Red
('Archived', '#95a5a6');  -- Gray

-- Add Status column to Clients table
ALTER TABLE Clients 
ADD COLUMN Status INT DEFAULT 2,  -- 2 is 'Pending'
ADD FOREIGN KEY (Status) REFERENCES list_partners_status(Status_id);

-- Add Status column to Couriers table
ALTER TABLE Couriers 
ADD COLUMN Status INT DEFAULT 2,
ADD FOREIGN KEY (Status) REFERENCES list_partners_status(Status_id);

-- Add Status column to Agents table
ALTER TABLE Agents 
ADD COLUMN Status INT DEFAULT 2,
ADD FOREIGN KEY (Status) REFERENCES list_partners_status(Status_id);

-- Add performance indexes
ALTER TABLE Clients 
ADD INDEX idx_status (Status),
ADD INDEX idx_created_at (created_at),
ADD INDEX idx_company_name (Full_Company_name),
ADD INDEX idx_bank (Bank_name);

ALTER TABLE Couriers 
ADD INDEX idx_status (Status),
ADD INDEX idx_created_at (created_at),
ADD INDEX idx_company_name (Full_Company_name),
ADD INDEX idx_bank (Bank_name);

ALTER TABLE Agents 
ADD INDEX idx_status (Status),
ADD INDEX idx_created_at (created_at),
ADD INDEX idx_company_name (Full_Company_name),
ADD INDEX idx_bank (Bank_name);

-- Add Created_by column to Clients table
ALTER TABLE Clients 
ADD COLUMN Created_by INT,
ADD FOREIGN KEY (Created_by) REFERENCES Users(User_id);

-- Add Created_by column to Couriers table
ALTER TABLE Couriers 
ADD COLUMN Created_by INT,
ADD FOREIGN KEY (Created_by) REFERENCES Users(User_id);

-- Add Created_by column to Agents table
ALTER TABLE Agents 
ADD COLUMN Created_by INT,
ADD FOREIGN KEY (Created_by) REFERENCES Users(User_id);

-- Update existing records to set Created_by to an admin user (replace 1 with actual admin User_id)
UPDATE Clients SET Created_by = 1 WHERE Created_by IS NULL;
UPDATE Couriers SET Created_by = 1 WHERE Created_by IS NULL;
UPDATE Agents SET Created_by = 1 WHERE Created_by IS NULL;

