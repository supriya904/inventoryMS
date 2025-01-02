# Inventory Management System

A comprehensive web-based inventory management system built with PHP and MySQL, designed to help businesses track and manage their product inventory efficiently.

## Features

### Current Features
- Secure Login System
  - User authentication with password hashing
  - Role-based access (Admin)

- Modern Dashboard Interface
  - Clean and intuitive user interface
  - Sidebar navigation with quick access to all features
  - User profile integration
  - Main dashboard overview with system title

- Product Management
  - View all products in a responsive grid layout
  - Filter products by categories
  - Add new products with images
  - Update existing products
  - Track product quantities
  - Set price information
  - Image management for products
  - Automatic transaction logging for stock changes

- Category-based Organization
  - Products organized by categories
  - Category-wise product filtering
  - Category-specific image storage

- Inventory Transactions
  - Automatic transaction logging
  - Track stock changes
  - Record purchase and sale transactions
  - Transaction history with remarks

### Planned Features
- Reports and Analytics
  - Stock level reports
  - Transaction history reports
  - Sales analytics
- User Management
  - Multiple user roles
  - User permissions
- Advanced Search
  - Search by product name
  - Filter by price range
  - Stock level filters

## Database Schema

The system uses MySQL with the following main tables:

1. **Categories**
   - Category_ID (Primary Key)
   - Category_Name

2. **Products**
   - Product_ID (Primary Key)
   - Product_Name
   - Category_ID (Foreign Key)
   - Quantity_In_Stock
   - Price_Per_Unit
   - imageAddress

3. **Inventory_Transactions**
   - Transaction_ID (Primary Key)
   - Product_ID (Foreign Key)
   - Transaction_Type (Purchase/Sale)
   - Quantity
   - Transaction_Date
   - Remarks

4. **Users**
   - User_ID (Primary Key)
   - Username
   - Password_Hash
   - Role

## Technical Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache Web Server
- XAMPP (recommended for local development)
- Modern web browser with JavaScript enabled

## Project Structure

```
inventoryMS/
├── api/                 # API endpoints
│   ├── config.php      # Database configuration
│   ├── login.php       # Login authentication
│   ├── add_product.php # Product creation
│   └── update_product.php # Product updates
├── assets/             # Image storage
│   ├── Laptops/       # Category-wise images
│   ├── Smartphones/
│   ├── Accessories/
│   ├── Tablets/
│   └── Wearables/
├── dashboard/          # Dashboard interface
│   ├── dashboard.html  # Main dashboard
│   ├── dashboard.css   # Dashboard styles
│   └── templates/      # Page templates
├── index.php          # Entry point
├── login.html         # Login page
├── schema.sql         # Database schema
└── README.md          # Documentation
```

## Installation

1. Clone this repository to your XAMPP's htdocs folder
2. Import the `schema.sql` file into your MySQL database
3. Configure database connection in `api/config.php`
4. Access the application through your web browser
5. Default login credentials:
   - Username: admin1
   - Password: password (change this in production)

## Development Status

1. ✅ Basic Project Setup
2. ✅ Database Schema Design
3. ✅ Login System Implementation
4. ✅ Dashboard Development
5. ✅ Product Management
6. ✅ Category Management
7. ✅ Transaction Management
8. 🔄 User Management
9. 📅 Reports and Analytics

## Security Features

- Password hashing for user credentials
- Input sanitization
- Prepared SQL statements to prevent SQL injection
- File upload validation
- Session-based authentication
