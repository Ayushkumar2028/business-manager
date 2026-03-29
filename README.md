## [Live Link ](https://business-manager-app.onrender.com)
## About Business Manager App

Business Manager is a Laravel-based database management application designed to handle bulk business data efficiently. It allows users to import large datasets, detect duplicates, merge records, and generate detailed reports.

The application simplifies data management tasks commonly required in business directories and listing systems, making it ideal for learning and real-world usage.

---

## Features

- Bulk data import from Excel files (XLSX, CSV)
- Two import modes:
  - Work on only the latest file
  - Work on all files (combined data)
- Automatic duplicate detection based on:
  - Business Name + Area + City + Address
- Duplicate management:
  - View duplicate groups
  - Merge individual duplicates
  - Merge all duplicates at once
- Reports:
  - Total records
  - City-wise data
  - Category + City-wise data
  - Category + Area-wise data
  - Unique listings
  - Duplicate listings
  - Incomplete listings
- Download reports in CSV format
- Clear all records option from dashboard

---

## Tech Stack

- Laravel (PHP Framework)
- MySQL (Local Development)
- PostgreSQL (Production - Render)
- Bootstrap (Frontend UI)
- Laravel Excel (maatwebsite/excel)

---

## Installation (Local Setup)

Follow these steps to run the project locally:

1. Clone the repository:

```bash
git clone https://github.com/your-username/business-manager.git
cd business-manager
```
2.Install dependencies:
```
composer install
npm install
```
3. Copy environment file:
```
cp .env.example .env
```
4. Generate app key:
```
php artisan key:generate
```
5. Setup database in phpMyAdmin and update .env:
```
DB_DATABASE=business_manager
DB_USERNAME=root
DB_PASSWORD=
```
6. Run migrations:
```
php artisan migrate
```
7. Start server:
```
php artisan serve
```

##Deployment (Render)

This project is deployed on Render using Docker.

##Project Structure

app/Models/Business.php → Business data model
app/Http/Controllers/BusinessController.php → Core logic
app/Imports/BusinessesImport.php → Excel import logic
resources/views/ → UI (Blade templates)
routes/web.php → Application routes

##Duplicate Detection Logic

Duplicates are identified using a combined key:
business_name + area + city + address
