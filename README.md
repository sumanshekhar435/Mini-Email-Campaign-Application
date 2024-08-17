# Project Name
Mini Email Campaign Application
## Overview
The goal of this project is to create a mini email campaign application where users can sign up, log in, create email campaigns by uploading a CSV file with contact
details, and send emails using a predefined HTML template. The system should process campaigns in chunks using Laravel's queue system and notify the user upon
completion.

## Prerequisites

- PHP 7.4 or higher
- Composer
- Node.js and npm
- A web server (e.g., Apache)
- MySQL or another database

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/sumanshekhar435/Mini-Email-Campaign-Application.git
cd Mini-Email-Campaign-Application

```
### Install PHP Dependencies
composer install

### Install JavaScript Dependencies
npm install

### Set Up Environment Configuration
cp .env.example .env

Edit the .env file to configure your environment settings. Ensure it includes the following Mail settings:
MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=2525
MAIL_USERNAME=
MAIL_PASSWORD=

### Generate Application Key
php artisan key:generate

### Set Up the Database (Create a new database and configure it in .env. Then run:)

php artisan migrate

### Configure File Storage
php artisan storage:link

### Build the Frontend Assets
npm run dev

### Run the Application
php artisan serve
Use this url in browser http://127.0.0.1:8000/

### Set Up Queue Workers
php artisan queue:work

### run specific test
php artisan test --filter=CSVValidationTest
