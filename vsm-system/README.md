# VSM System

A PHP Vehicle Service Management starter with enhanced Bootstrap UI, role-based dashboards, and module pages.

## Setup
1. Import `database/vsm.sql`.
2. Update DB credentials in `config/config.php`.
3. Run local server:
   ```bash
   php -S localhost:8000 -t vsm-system
   ```
4. Login with `admin@example.com / password`.

## UI Notes
- Uses Bootstrap CDN for CSS/JS and custom styling in `assets/css/style.css`.
- Shared app shell (`navbar + sidebar + content`) is reused across dashboards and modules.
