# Bincom Election Results — Laravel

This project was built for the Bincom software developer assessment. It uses Laravel and PostgreSQL to display polling unit election results, calculate total votes for an LGA, and add new polling units with their results.

## Live Demo

**https://bincom-laravel.onrender.com**

The app is hosted on Render's free tier. If it hasn't been used for a while, it may take around 30 seconds to load on the first visit.

## Tech Stack

| Area | Technology |
|---|---|
| Backend | Laravel 11, PHP 8.2 |
| Frontend | Blade, Tailwind CSS v4, Vite |
| Database | PostgreSQL (Supabase) |
| Hosting | Render with Docker |

## What the App Does

| Task | Route | Description |
|---|---|---|
| Q1 | `/polling-unit` | View results for a selected polling unit |
| Q2 | `/lga-sum` | Calculate total votes across polling units in an LGA |
| Q3 | `/add-polling-unit` | Add a polling unit and its party results |

### Q1: View Polling Unit Results

The page uses linked dropdowns to select an LGA, ward, and polling unit. Once a polling unit is selected, the app retrieves its results from `announced_pu_results` and displays the votes recorded for each party, including each party's share of the total votes.

### Q2: Calculate LGA Results

This page adds up the results from individual polling units in the selected LGA. It uses `announced_pu_results` rather than `announced_lga_results` to calculate the totals.

The page also displays the announced LGA figures for comparison, along with the difference between the two totals.

The supplied dataset only contains results for a limited number of polling units, mainly in Ughelli North. LGAs without polling unit results will show an appropriate message.

### Q3: Add a Polling Unit

This page allows users to enter the details of a new polling unit and record its results for all parties.

The party list is loaded from the `party` table, so the form does not need a hardcoded list of party names. The polling unit and its results are saved inside a database transaction to prevent incomplete records if something goes wrong during the save.

## Project Structure

```text
bincom-laravel/
├── app/Http/Controllers/
│   ├── HomeController.php
│   ├── PollingUnitController.php
│   ├── LgaSumController.php
│   └── AddPollingUnitController.php
├── resources/
│   ├── css/app.css
│   ├── js/app.js
│   └── views/
│       ├── layouts/app.blade.php
│       ├── partials/
│       │   ├── sidebar.blade.php
│       │   ├── topbar.blade.php
│       │   ├── stat-tile.blade.php
│       │   └── empty-state.blade.php
│       ├── home.blade.php
│       ├── polling-unit/index.blade.php
│       ├── lga-sum/index.blade.php
│       └── add-polling-unit/index.blade.php
├── routes/web.php
├── Dockerfile
└── vite.config.js
```

## Routes and Data Endpoints

The Blade pages use these endpoints to load data and submit forms.

| Method | Endpoint | Purpose |
|---|---|---|
| GET | `/polling-unit/wards/{lga_id}` | Get wards in an LGA |
| GET | `/polling-unit/pus/{ward_id}` | Get polling units in a ward |
| GET | `/polling-unit/result/{uniqueid}` | Get results for a polling unit |
| GET | `/lga-sum/data/{lga_id}` | Get summed results for an LGA |
| POST | `/add-polling-unit` | Save a new polling unit and its results |

## Running the Project Locally

### Requirements

- PHP 8.2 or later
- Composer 2
- Node.js 20 or later
- A Supabase project containing the Bincom database tables and data

### 1. Install the dependencies

```bash
composer install
npm install
```

### 2. Set up the environment

Copy `.env.example` to `.env` and generate an application key.

```bash
cp .env.example .env
php artisan key:generate
```

Update the database settings in `.env` with your own Supabase connection details.

```env
DB_CONNECTION=pgsql
DB_HOST=YOUR-SUPABASE-POOLER-HOST
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=YOUR-SUPABASE-USERNAME
DB_PASSWORD='YOUR-PASSWORD'
DB_SSLMODE=require
```

Replace the example values with the credentials from your Supabase project. Keep these credentials private and do not commit your `.env` file to Git.

If your database password contains special characters such as `$`, check that it is parsed correctly by Laravel's environment configuration.

### 3. Build the frontend and start Laravel

```bash
npm run build
php artisan serve
```

Open **http://localhost:8000** in your browser.

## Deployment on Render

The project includes a `Dockerfile` for deployment. It installs PHP and the required extensions, installs Composer and Node.js, builds the frontend assets, and starts the Laravel application using the port supplied by Render.

### Environment variables

Add the following variables in your Render service settings.

| Variable | Value |
|---|---|
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_KEY` | Your generated Laravel application key |
| `APP_URL` | `https://bincom-laravel.onrender.com` |
| `DB_CONNECTION` | `pgsql` |
| `DB_HOST` | Your Supabase session pooler host |
| `DB_PORT` | `5432` |
| `DB_DATABASE` | `postgres` |
| `DB_USERNAME` | Your Supabase database username |
| `DB_PASSWORD` | Your Supabase database password |
| `DB_SSLMODE` | `require` |
| `SESSION_DRIVER` | `file` |
| `CACHE_STORE` | `file` |
| `QUEUE_CONNECTION` | `sync` |

### Deployment Notes

A few configuration issues came up during deployment:

- **Frontend assets:** Make sure `public/build` is included in the Docker image. Excluding it in `.dockerignore` can cause the deployed site to load without its CSS and JavaScript.
- **Vite development file:** Remove `public/hot` before deployment and add it to `.dockerignore`. Otherwise, Laravel may try to load assets from the local Vite development server.
- **Database SSL:** Use `DB_SSLMODE=require` for the Supabase connection.
- **HTTPS:** Set `APP_URL` to the deployed HTTPS address so Laravel generates the correct asset and request URLs.
- **Sessions:** Set `SESSION_DRIVER=file` because this project does not use Laravel's database-backed sessions.
- **Supabase connection:** Use the session pooler on port `5432` with the appropriate credentials. Avoid switching poolers without checking their compatibility with your connection settings.

## Database Setup

The assessment supplied `bincom_test.sql` as a MySQL dump. I converted it to PostgreSQL format and imported it into Supabase.

The main changes included:

- Replacing `AUTO_INCREMENT` with PostgreSQL identity columns.
- Converting `datetime` columns to `timestamp`.
- Replacing MySQL zero-dates with `NULL`.
- Removing MySQL-specific engine and character-set declarations.
- Quoting the `long` column name where needed.

The application works with the imported tables directly. It does not run Laravel migrations against the assessment database.

## License

Created for the Bincom recruitment assessment.
