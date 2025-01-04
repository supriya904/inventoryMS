# Inventory Management System

A modern web-based inventory management system built with PHP, MySQL, and JavaScript. This system helps businesses track their inventory, manage products, and generate detailed reports.

## Features

### 1. Dashboard
- Quick overview of inventory status
- Key metrics and statistics
- Real-time updates

### 2. Product Management
- Add, edit, and delete products
- Categorize products
- Track stock levels
- Set low stock alerts
- Upload product images
- Price management

### 3. Reports & Analytics
- Comprehensive reporting system
- Date range filtering
- Summary statistics:
  - Total transactions
  - Total purchase value
  - Total sale value
  - Low stock items count
- Visual Analytics:
  - Sales vs Purchase price analysis graph
- Detailed transaction history table
- All data exportable for further analysis

## Technical Stack

- Frontend:
  - HTML5, CSS3
  - JavaScript (ES6+)
  - jQuery
  - Chart.js for data visualization
  - Font Awesome icons
  - Responsive design

- Backend:
  - PHP
  - MySQL Database
  - PDO for database connections

## Database Structure

The system uses the following main tables:
- Categories: Store product categories
- Products: Store product information
- Users: Manage user authentication

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
- Open your browser
- Navigate to: http://localhost/inventoryMS/

## Usage

1. Login with your credentials
2. Navigate through the sidebar menu:
   - Dashboard: View overall statistics
   - Products: Manage your inventory
   - Reports: Generate detailed analysis and transaction history

3. Reports Section:
   - Use date filters to select specific periods
   - View transaction summaries
   - Analyze sales vs purchase trends
   - Export detailed reports

## Security Features

- Secure authentication system
- Password hashing
- SQL injection prevention
- XSS protection
- Session management

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a new Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Support

For support, please email [support-email] or create an issue in the repository.
