-- Table: Categories
CREATE TABLE Categories (
    Category_ID INT AUTO_INCREMENT PRIMARY KEY,
    Category_Name VARCHAR(255) NOT NULL
);

-- Insert synthetic data for Categories
INSERT INTO Categories (Category_Name) VALUES
('Laptops'),
('Smartphones'),
('Accessories'),
('Tablets'),
('Wearables');

-- Table: Products
CREATE TABLE Products (
    Product_ID INT AUTO_INCREMENT PRIMARY KEY,
    Product_Name VARCHAR(255) NOT NULL,
    Category_ID INT NOT NULL,
    Quantity_In_Stock INT NOT NULL,
    Price_Per_Unit DECIMAL(10, 2) NOT NULL,
    imageAddress VARCHAR(255),
    FOREIGN KEY (Category_ID) REFERENCES Categories(Category_ID)
);

-- Insert synthetic data for Products
INSERT INTO Products (Product_Name, Category_ID, Quantity_In_Stock, Price_Per_Unit) VALUES
('Dell Inspiron 15', 1, 50, 55000.00),
('HP Pavilion 14', 1, 30, 60000.00),
('iPhone 14', 2, 20, 79999.00),
('Samsung Galaxy S22', 2, 25, 69999.00),
('Logitech Mouse', 3, 100, 1500.00),
('Apple iPad Air', 4, 15, 55000.00),
('Samsung Galaxy Watch 5', 5, 10, 19999.00);

-- Table: Inventory_Transactions
CREATE TABLE Inventory_Transactions (
    Transaction_ID INT AUTO_INCREMENT PRIMARY KEY,
    Product_ID INT NOT NULL,
    Transaction_Type ENUM('Purchase', 'Sale') NOT NULL,
    Quantity INT NOT NULL,
    Transaction_Date DATE NOT NULL,
    Remarks VARCHAR(255),
    FOREIGN KEY (Product_ID) REFERENCES Products(Product_ID)
);

-- Insert test transactions
INSERT INTO Inventory_Transactions (Product_ID, Transaction_Type, Quantity, Transaction_Date, Remarks) VALUES
(1, 'Purchase', 10, '2024-01-04', 'Initial stock purchase'),
(2, 'Sale', 5, '2024-01-04', 'Bulk order'),
(3, 'Purchase', 15, '2024-01-04', 'Restocking');

-- Table: Users
CREATE TABLE Users (
    User_ID INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(255) NOT NULL,
    Password_Hash VARCHAR(255) NOT NULL,
    Role ENUM('Admin') DEFAULT 'Admin'
);

-- Insert synthetic data for Users
INSERT INTO Users (Username, Password_Hash) VALUES
('admin1', 'hashed_password1'),
('admin2', 'hashed_password2');