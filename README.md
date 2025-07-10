# Pet Adoption System

A robust pet adoption platform developed using PHP, MySQL, and Bootstrap. Tailored for animal welfare organizations, it streamlines the adoption process, enabling users to easily browse and request to adopt pets. The system includes secure authentication, role-based dashboards, protected request handling, and a responsive user interface.

## Features

### Core Functionality

- **User Authentication** – Secure login system with session management
- **Role-Based Access** – Separate dashboards for NGO and Adopter roles
- **Pet Management** – NGOs can create, update, and delete pet listings
- **Adoption Requests** – Adopters can view and request to adopt pets
- **Duplicate Request Prevention** – Prevents multiple requests for the same pet

### Technical Features

- **MVC Architecture** – Clean separation of Controllers, Views, and Models
- **Database Security** – Uses prepared statements to guard against SQL injection
- **Responsive UI** – Mobile-friendly design powered by Bootstrap
- **File Uploads** – Supports image uploads for pet profiles
- **Session Management** – Ensures proper login sessions and access control

## Prerequisites

- XAMPP (Apache, MySQL, PHP)
- PHP 7.4 or newer
- MySQL 5.7 or newer
- Composer (for managing dependencies)

## Installation Guide

### Clone the Repository

```bash
git clone <repository-url>
cd pet-adoption-system
```

### Move to XAMPP Directory

Place the project folder inside the `htdocs` directory.

### Database Setup

- Create a database in phpMyAdmin (e.g., `pet_adoption_system`)
- Import the provided SQL file
- Update database credentials in the configuration file

### Environment Configuration

```bash
cp .env.example .env
```

Edit `.env` with:

```
DB_HOST=localhost
DB_NAME=pet_adoption_system
DB_USER=root
DB_PASS=
APP_URL=http://localhost/PHP-pet-adoption-system
```

### Install Dependencies

```bash
composer install
```

## Run the App

Open in your browser:

```
http://localhost/PHP-pet-adoption-system
```

## User Roles

### Admin (NGO)

- Manage all pet listings
- View and handle adoption requests
- Manage own profile

### Adopter

- Browse available pets
- Send adoption requests
- View request status

## Security Highlights

- Prepared Statements to prevent SQL Injection
- File Validation for secure image uploads
- Session-Based Authentication
- Input Validation (Client & Server-side)
- XSS Protection through output escaping

## License

This project is open-source and available under the **MIT License**.

Made with ❤️ to help every pet find a home.
