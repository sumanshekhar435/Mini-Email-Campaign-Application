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

composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
npm run dev
php artisan serve
php artisan queue:work
php artisan test --filter=CSVValidationTest
