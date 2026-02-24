# Inventory Management System

A Laravel-based inventory management system with accounting features.

## Requirements

- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL or MariaDB

## Project Setup

### 1. Clone the Repository

```bash
git clone <repository-url>
cd inventory_management_system
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Environment Configuration

```bash
# Copy the example environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Setup

1. Create a new database in MySQL/MariaDB
2. Update the `.env` file with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventory_management_system
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Run Migrations and Seeders

```bash
# Run database migrations
php artisan migrate

# Seed the database with initial data
php artisan db:seed
```

### 6. Build Assets

```bash
# For development
npm run dev

# For production
npm run build
```

### 7. Start the Application

```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

## Features

- Product Management
- Customer Management
- Sales Management
- Expense Tracking
- Journal Entries
- Chart of Accounts
- Financial Reports

## License

This project is open-sourced software.
