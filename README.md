# Resource Hub

This is the repository for **Resource Hub**, a Laravel 11 project.

## Getting Started

These instructions will help you set up the project on your local machine for development purposes.

### Prerequisites

Before you begin, make sure you have the following installed on your machine:

- **PHP** (>= 8.2)
- **Composer** (latest version)
- **MySQL** (or any supported database for Laravel)
- **Node.js** and **npm** (if required for frontend assets)

### Cloning the Repository

1. Clone the repository from GitHub:
   ```bash
   git clone https://github.com/awhileago/resource-hub.git

2. Navigate into the project directory:
   ```bash
   cd resource-hub
   
3. Switch to the staging branch:
   ```bash
   git checkout staging

### Project Setup

1. Install the project dependencies:
   ```bash
   composer install

2. Copy the .env.example file to create your .env file:
   ```bash
   cp .env.example .env
   
3. Generate an application key:
   ```bash
   php artisan key:generate
   
4. Migrate the database and seed it with initial data:
   ```bash
   php artisan migrate --seed
   
5. Create a personal access client for Laravel Passport:
   ```bash
   php artisan passport:client --personal
   ```
   - When prompted to add the name of the client, type **resource-hub**. <br/><br/>

6. Run the PSGC parsing command, select the PSGC 2024 file when prompted, and wait for the process to complete:
   ```bash
   php artisan parse:psgc

### Running the Application

Once the setup is complete, you can serve the application locally using:
```bash
php artisan serve

