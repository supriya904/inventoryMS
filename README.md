# Inventory Management System

A comprehensive web-based inventory management system built with PHP and MySQL, designed to help businesses track and manage their product inventory efficiently.

## Features (Current & Planned)

### Current Features
- Secure Login System
  - Default admin login credentials (to be changed after first login)
- Modern Dashboard Interface
  - Clean and intuitive user interface
  - Sidebar navigation with quick access to all features
  - User profile integration
  - Main dashboard overview with system title

### Planned Features
- Dashboard with inventory overview
- Product Management
  - Add/Edit/Delete products
  - Track product quantities
  - Set price information
- Category Management
  - Organize products by categories
- Supplier Management
  - Maintain supplier information
  - Track supplier transactions
- Inventory Transactions
  - Record purchases and sales
  - Transaction history
- User Management
  - Role-based access control
  - User authentication and authorization

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

## Installation

1. Clone this repository to your XAMPP's htdocs folder
2. Import the `schema.sql` file into your MySQL database
3. Configure your database connection settings
4. Access the application through your web browser

## Project Structure

```
inventoryMS/
├── index.php          # Entry point
├── login.html         # Login page
├── schema.sql         # Database schema
├── README.md          # Project documentation
└── [more files to be added as development progresses]
```

## Development Roadmap

1. ✅ Basic Project Setup
2. ✅ Database Schema Design
3. ✅ Login System Implementation
4. 🔄 Dashboard Development
5. 📅 Product Management
6. 📅 Category Management
7. 📅 Supplier Management
8. 📅 Transaction Management
9. 📅 User Management
10. 📅 Reports and Analytics

## Security Considerations

- Password hashing implementation
- SQL injection prevention
- Session management
- Input validation and sanitization
- Access control implementation

## Contributing

This is a development project. More contribution guidelines will be added as the project progresses.

## License

[License information to be added]
