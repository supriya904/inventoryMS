# Inventory Management System

A modern web-based inventory management system built with PHP, MySQL, and JavaScript. This system helps businesses efficiently manage their inventory, track product movements, and generate comprehensive reports.

## Features

### 1. Dashboard
- Real-time inventory overview
- Total products count
- Current inventory value
- Recent product updates tracker
- Interactive category distribution chart

### 2. Product Management
- Add and update products
- Organize products by categories
- Track stock movements (purchases and sales)
- Add remarks for each transaction
- Bulk update capabilities
- Product history tracking

### 3. Reports & Analytics
#### Transaction Reports
- Detailed transaction history with filtering options
- Filter by:
  - Date range
  - Transaction type (Purchase/Sale)
  - Category
  - Product
- Transaction details include:
  - Transaction date and time
  - Product information
  - Quantity moved
  - Transaction type
  - User who performed the transaction
  - Remarks/Notes

#### Inventory Reports
- Current stock levels for all products
- Category-wise inventory distribution
- Product valuation reports
- Stock movement patterns
- Export capabilities:
  - CSV format
  - PDF format (for printing)
  - Customizable date ranges

#### Financial Reports
- Total inventory value
- Product-wise value distribution
- Category-wise value analysis
- Transaction value reports:
  - Purchase values
  - Sale values
  - Value movement trends

#### Custom Reports
- Build custom reports based on specific needs
- Select specific fields to include
- Multiple format support
- Save report templates for future use

## Technical Stack

### Frontend
- HTML5, CSS3 with modern responsive design
- JavaScript (ES6+)
- jQuery for DOM manipulation
- Chart.js for interactive visualizations
- Font Awesome icons
- Bootstrap for responsive layouts

### Backend
- PHP 7.4+
- MySQL Database
- PDO for secure database connections
- RESTful API architecture

### Security Features
- Secure authentication system
- Password hashing using modern algorithms
- SQL injection prevention
- XSS protection
- CSRF token implementation
- Session management
- Input validation and sanitization

## Database Structure

The system uses the following main tables:
- Categories
  - Category_ID (Primary Key)
  - Category_Name
  - Description
  - Created_At
  - Updated_At

- Products
  - Product_ID (Primary Key)
  - Category_ID (Foreign Key)
  - Product_Name
  - Description
  - Quantity_In_Stock
  - Unit_Price
  - Created_At
  - Updated_At

- Transactions
  - Transaction_ID (Primary Key)
  - Product_ID (Foreign Key)
  - Transaction_Type (Purchase/Sale)
  - Quantity
  - Transaction_Date
  - Remarks
  - User_ID (Foreign Key)

- Users
  - User_ID (Primary Key)
  - Username
  - Password (Hashed)
  - Email
  - Role
  - Last_Login
  - Created_At

## Installation

1. Clone the repository to your XAMPP's htdocs folder:
```bash
git clone [repository-url] /path/to/xampp/htdocs/inventoryMS
```

2. Import the database:
- Open phpMyAdmin
- Create a new database named 'inventory'
- Import the schema.sql file

3. Configure the database connection:
- Update api/config.php with your database credentials if different from defaults

4. Access the system:
- Start XAMPP (Apache and MySQL)
- Open your browser
- Navigate to: http://localhost/inventoryMS/

## Usage Guide

### 1. Dashboard Navigation
- View total products and inventory value
- Monitor recent product updates
- Analyze category distribution

### 2. Product Management
- Add new products through the product form
- Update existing product stock using the update form
- View and manage categories
- Track product history

### 3. Generating Reports
1. Navigate to the Reports section
2. Select the desired report type:
   - Transaction History
   - Inventory Status
   - Financial Reports
   - Custom Reports
3. Apply filters as needed:
   - Set date range
   - Choose categories
   - Select specific products
4. Export or view the report:
   - Use the export button for downloading
   - Print directly from the browser
   - Save report templates for future use

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## Support

For support, please email [support-email] or create an issue in the repository.

## License

This project is licensed under the MIT License - see the LICENSE file for details.
