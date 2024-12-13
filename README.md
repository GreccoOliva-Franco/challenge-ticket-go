# About

Challenge for Ticket Go

## Business Requirements

Explained in dedicated file: `./Challenge rules.pdf`

## Technical Requirements

- PHP installed (used 8.3.14)
- Composer installed (used 2.8.3)

## Setup

Run each step at a time:
1. Set up SQLite Database file

```bash
"" > ./database/database.sqlite
```

2. Install dependencies
```bash
composer install
```

3. Pick a seeder and run migrations (takes a while):

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

Swagger documentation in endpoint `http://localhost:8000/api/documentation`