# Kurinji Handloom Saree - Full Ecommerce Platform (PHP)

This project now includes a complete **PHP backend + browser-based ecommerce UI** for Kurinji handloom sarees.

## What is included

### Storefront (customer side)
- Browse saree catalog
- Add products to cart
- Checkout with customer details
- Place order from web UI

### Admin dashboard
- Create products
- View product catalog and stock
- Adjust inventory quantities with reasons
- View orders and update order status (`pending`, `paid`, `packed`, `shipped`, `delivered`, `cancelled`)

### Backend APIs
- Product CRUD
- Inventory transaction logging
- Order creation with transactional stock deduction
- Auto stock rollback when cancellable orders are cancelled

## Quick start

1) Initialize DB:
```bash
php database/init.php
```

2) Run server:
```bash
php -S 0.0.0.0:8000 -t public
```

3) Open platform in browser:
- Storefront: `http://localhost:8000/`
- Admin dashboard: `http://localhost:8000/admin`

## API Routes

- `GET /health`
- `GET /products`
- `GET /products/{id}`
- `POST /products`
- `PUT /products/{id}`
- `DELETE /products/{id}`
- `POST /inventory/adjust`
- `GET /inventory/transactions`
- `GET /orders`
- `GET /orders/{id}`
- `POST /orders`
- `PATCH /orders/{id}/status`

## Notes
- Default DB is MySQL (`kurinji_sarees`) using `config/database.php`.
- Load the schema from `database/schema.mysql.sql` (for `users`, `reviews`, and `role` enum).
