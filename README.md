# About

Challenge for Ticket Go

## Business Requirements

Explained in dedicated file: `./Challenge rules.pdf`

## Technical Requirements

- PHP installed (used 8.3.14)
- Composer installed (used 2.8.3)

## Setup

Run each step at a time:

1. Set .env file

```bash
cp .env.example .env
```

2. Set SQLite Database

```bash
"" > ./database/database.sqlite
```

3. Set Laravel's key

```bash
php artisan key:generate
```

4. Install dependencies and generate Laravel's API_KEY
```bash
composer install
```

5. Pick a seeder and run migrations (takes a while):

- Simple seed (ReducedTicketGoSeeder):

```bash
php artisan migrate:fresh --seeder=ReducedTicketGoSeeder
```

- Full seed (TicketGoSeeder) (takes a while):

```bash
php artisan migrate:fresh --seeder=TicketGoSeeder
```

## Testing

Only Feature tests (integration testing)

Modules: 
- Pagination
- Products

Run tests:
```bash
php artisan test
```

## Deploy

```bash
php artisan serve
```

<!-- Swagger documentation in endpoint [http://localhost:8000/api/documentation` -->

## Documentation
REQUIREMENTS:
- Server should be deployed

Provided by Swagger in a dedicated [endpoint](http://localhost:8000/swagger)