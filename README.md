# Thidasna Craft - Complete Business Management System

## 📋 Overview

Thidasna Craft is a comprehensive, modern business management system designed specifically for handicraft stores and retail businesses. This system provides a complete solution for inventory management, billing, invoicing, and business analytics with a stunning, modern interface.

**Developer:** Vexel IT by Kavizz  
**Contact:** +94 74 089 0730  
**Version:** 4.0 (Aurora Suite)  
**Year:** 2026-2027

---

## ✨ Features

### 🏠 Dashboard Module
- **Real-time Statistics:** Total items, total bills, today's sales
- **Interactive Charts:** Sales overview (last 7 days) and revenue distribution pie chart
- **Recent Invoices:** Quick access to latest 10 transactions
- **Search Functionality:** Search and filter invoices instantly
- **Auto-refresh:** Dashboard updates every 30 seconds

### 📦 Inventory Management
- **CRUD Operations:** Create, Read, Update, Delete items
- **Auto-generated Item Codes:** Unique codes with date and random numbers
- **Search & Filter:** Real-time search by code or description
- **Bulk Management:** Easy item addition with validation
- **Price Management:** Support for decimal prices

### 🧾 Billing System
- **Item Search:** Autocomplete search with instant suggestions
- **Dynamic Cart:** Add items with quantity and item-level discounts
- **Bill-level Discounts:** Apply percentage discounts to entire bill
- **Tax Management:** Configurable tax percentage
- **Real-time Calculations:** Instant subtotal, discount, tax, and total updates
- **Hold Bills:** Save incomplete bills for later processing
- **Clear Bill:** Reset entire cart with confirmation

### 📄 Invoice Management
- **Print & Save:** Generate professional invoices with automatic saving
- **Reprint Functionality:** Search and reprint any past invoice
- **Multiple Search Methods:**
  - By invoice number
  - By date range (custom or preset: today, yesterday, last 7/30 days, this month)
  - Show all invoices
- **Invoice Details View:** Modal view with all item details
- **Professional Print Layout:** Customer and cashier signature sections

### 📊 Data Visualization
- **Sales Chart:** Bar chart showing last 7 days performance
- **Pie Chart:** Revenue distribution by top 5 selling items
- **Real-time Updates:** Charts update automatically with new data

---

## 🛠️ Technology Stack

### Frontend
- **HTML5** - Structure
- **Tailwind CSS** - Styling and responsive design
- **JavaScript (ES6+)** - Core functionality
- **GSAP** - Smooth animations
- **Chart.js** - Data visualization
- **Font Awesome 6** - Icons
- **SweetAlert2** - Beautiful modals and alerts

### Backend (Required PHP Files)
- **PHP 7.4+** - Server-side logic
- **MySQL** - Database
- **REST API** - JSON communication

### Database Structure (Required Tables)

```sql
-- Items table
CREATE TABLE items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    item_code VARCHAR(50) UNIQUE NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Invoices table
CREATE TABLE invoices (
    id INT PRIMARY KEY AUTO_INCREMENT,
    invoice_no VARCHAR(50) UNIQUE NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    bill_discount_percent DECIMAL(5,2) DEFAULT 0,
    bill_discount_amount DECIMAL(10,2) DEFAULT 0,
    tax_percent DECIMAL(5,2) DEFAULT 0,
    tax_amount DECIMAL(10,2) DEFAULT 0,
    final_total DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Invoice items table
CREATE TABLE invoice_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    invoice_no VARCHAR(50) NOT NULL,
    item_code VARCHAR(50) NOT NULL,
    description TEXT NOT NULL,
    quantity DECIMAL(10,2) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    discount_percent DECIMAL(5,2) DEFAULT 0,
    total DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (invoice_no) REFERENCES invoices(invoice_no)
);
###📁 Required PHP Files
The system requires the following backend API endpoints:

File	Purpose
db.php	Database connection configuration
dashboard_data.php	Fetch dashboard statistics and chart data
get_items.php	Retrieve all items
get_item.php	Search single item by code/description
search_items.php	Autocomplete search for items
add_item.php	Add new item to inventory
update_item.php	Update existing item
delete_item.php	Remove item from inventory
get_item_by_id.php	Fetch item by ID for editing
save_invoice.php	Save invoice and items to database
get_invoice.php	Retrieve invoice details by number
search_invoices.php	Search invoices by number or get recent
search_invoices_by_date.php	Filter invoices by date range
🚀 Installation Guide
Step 1: Server Requirements
PHP 7.4 or higher

MySQL 5.7 or higher

Apache/Nginx web server

XAMPP/WAMP/LAMP recommended for local development

Step 2: Database Setup
Create a new MySQL database named thidasna_craft

Import the provided database.sql file (create tables as shown above)

Update db.php with your database credentials:

php
<?php
$host = 'localhost';
$dbname = 'thidasna_craft';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
Step 3: File Structure
text
thidasna-craft/
├── index.html (or index.php - Dashboard)
├── index1.php (Billing System)
├── db.php
├── dashboard_data.php
├── get_items.php
├── get_item.php
├── search_items.php
├── add_item.php
├── update_item.php
├── delete_item.php
├── get_item_by_id.php
├── save_invoice.php
├── get_invoice.php
├── search_invoices.php
├── search_invoices_by_date.php
└── README.md
Step 4: Configuration
Place all files in your web server directory (e.g., htdocs for XAMPP)

Ensure proper file permissions (755 for directories, 644 for files)

Configure your web server to handle PHP files

Access the system via http://localhost/thidasna-craft/

🎯 Usage Guide
Dashboard (index.html/php)
View Statistics: Cards show total items, bills, and today's sales

Navigate: Click on stat cards to jump to Items or Invoices

Search Invoices: Use search bar in Recent Invoices section

Quick Actions: Click "Create New Bill" card to start billing

Inventory Management
Add Item: Click "Add New Item" button

Fill Details: Item code (auto-generated), description, price

Edit Item: Click edit icon, modify details, and update

Delete Item: Click delete icon and confirm

Search: Type in search box to filter items

Billing Process (index1.php)
Search Item: Type item code or description in search box

Select Item: Choose from autocomplete suggestions

Set Quantity & Discount: Enter quantity and item-level discount

Add to Cart: Click "Add" button

Adjust Cart: Use +/- buttons or edit discount directly

Apply Bill Discount: Enter percentage discount if needed

Apply Tax: Enter tax percentage if applicable

Hold Bill: Save incomplete bill for later

Print & Save: Generate invoice and print

Reprint: Use "Reprint Bill" for any past invoice

Invoice Reprinting
Click "Reprint Bill" button

Search by invoice number or date range

Select invoice from results

Click "Print Now" or use individual print buttons

🎨 Design Features
Visual Highlights
Dark Theme: Modern dark background with vibrant accents

Glassmorphism: Frosted glass effects with backdrop blur

Gradients: Beautiful gradient buttons and stat cards

Animations: GSAP-powered smooth transitions and hover effects

Responsive: Fully responsive design for all devices

Floating Orbs: Animated background elements for depth

Color Scheme
Primary: Indigo/Purple gradients

Success: Emerald/Green

Warning: Amber/Yellow

Danger: Red/Rose

Info: Blue/Sky

Background: Dark slate to deep navy

🔧 API Endpoints Documentation
GET Endpoints
Endpoint	Parameters	Response
get_items.php	none	[{id, item_code, description, price}]
get_item.php?search={term}	search term	{item_code, description, price}
search_items.php?search={term}	search term	[{item_code, description, price}]
delete_item.php?id={id}	item ID	{success: true/false}
get_item_by_id.php?id={id}	item ID	{id, item_code, description, price}
get_invoice.php?invoice_no={no}	invoice number	{success, invoice, items}
search_invoices.php?search={term}&limit={n}	search term, limit	{success, invoices}
search_invoices_by_date.php?start_date={date}&end_date={date}	date range	{success, invoices}
dashboard_data.php	none	{total_items, total_bills, today_sales, last_7_days, top_items, recent_invoices}
POST Endpoints
Endpoint	Body	Response
add_item.php	item_code, description, price	{success, message}
update_item.php	id, description, price	{success, message}
save_invoice.php	JSON with items, discount, tax	{success, invoice}
🛡️ Security Considerations
SQL Injection Protection: Use prepared statements in all PHP files

Input Validation: Validate all user inputs on server side

XSS Prevention: Escape output using htmlspecialchars()

Session Management: Implement user authentication if needed

HTTPS: Use HTTPS in production environments

Backup: Regular database backups recommended

📱 Browser Support
Chrome (latest) - Fully supported

Firefox (latest) - Fully supported

Safari (latest) - Fully supported

Edge (latest) - Fully supported

Opera (latest) - Fully supported

🐛 Troubleshooting
Common Issues
Issue: Autocomplete not working

Solution: Ensure search_items.php is properly configured and returning JSON

Issue: Invoice not saving

Solution: Check database connection and table structures

Issue: Charts not displaying

Solution: Verify dashboard_data.php is returning valid data format

Issue: Print layout broken

Solution: Check browser print settings and CSS media queries

Issue: Hold bills not persisting

Solution: Check localStorage permissions in browser

🔄 Future Enhancements
User authentication and roles (Admin, Cashier)

Customer management module

Daily sales reports PDF export

Barcode scanner integration

Email invoice sending

Multiple store/branch support

Stock alerts and low inventory notifications

Expense tracking module

Employee attendance and payroll

QR code payment integration

Mobile app version

Cloud backup and sync

📞 Support & Contact
For technical support, customization, or inquiries:

Developer: Vexel IT by Kavizz
Phone: +94 74 089 0730
Email: kavizz@vexelit.com
Location: Sri Lanka

📄 License
This software is proprietary and confidential. Unauthorized copying, distribution, or modification is strictly prohibited.

© 2026-2027 Thidasna Craft - All Rights Reserved

🙏 Acknowledgments
Tailwind CSS for the utility-first framework

Chart.js for beautiful charts

GSAP for smooth animations

SweetAlert2 for elegant modals

Font Awesome for premium icons

📝 Version History
Version	Date	Changes
4.0	2026	Aurora Suite - Complete redesign with dark theme and animations
3.0	2025	Added reprint functionality and advanced search
2.0	2024	Introduced hold bills and dashboard analytics
1.0	2023	Initial release with basic billing

Thank you for choosing Thidasna Craft Management System!
Empowering handicraft businesses with modern technology ✨
