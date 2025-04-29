# ACME CSR Platform

A Corporate Social Responsibility (CSR) platform for ACME Corp that enables employee engagement in social and environmental initiatives through donations and campaign management.

## Features

- Employee authentication and authorization
- Campaign creation and management
- Donation tracking and reporting
- User profile management
- Real-time campaign updates
- Search and filter campaigns
- Admin dashboard for campaign oversight

## Technology Stack

### Backend
- Laravel 10.x
- PHP 8.2+
- PostgreSQL
- Pest for testing
- PHPStan (Level 8)

### Frontend
- Vue.js 3
- Vite
- Pinia for state management
- Vue Router
- Tailwind CSS

## Getting Started

1. Clone the repository
2. Install dependencies:
   ```bash
   composer install
   npm install
   ```
3. Set up environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. Configure database in .env
5. Run migrations:
   ```bash
   php artisan migrate
   ```
6. Start development servers:
   ```bash
   php artisan serve
   npm run dev
   ```

## Architecture

The application follows a modern, scalable architecture:

- Backend: RESTful API with Laravel
- Frontend: SPA with Vue.js 3
- Database: PostgreSQL for reliable data storage
- Authentication: Laravel Sanctum for API token management

## Testing

Run tests using Pest:
```bash
php artisan test
```

## Code Quality

PHPStan is configured at level 8 for strict type checking:
```bash
./vendor/bin/phpstan analyse
```
