# Unnnati – Personal + Business Dashboard

Modern single-folder PHP application (no framework) with:

- Public portfolio / biodata page
- Secure login/register
- ERP modules (inventory, sales)
- Asset tracking
- Personal growth tracker ("Unnnati Dashboard")
- Dark mode, responsive design, Chart.js visualizations

## Tech stack
- PHP 8.1+
- MySQLi (prepared statements)
- Tailwind CSS (CDN)
- Vanilla JS + AOS animations
- Chart.js (CDN)
- PWA-ready (basic)

## Local setup (XAMPP / similar)

1. Clone repo
2. Create database `unnnati_db`
3. Import `db.sql`
4. Copy `config.example.php` → `config.php` and fill credentials
5. Start Apache + MySQL
6. Open `http://localhost/unnnati/`

## Deployment notes
- Works well on Hostinger shared hosting
- Upload everything to public_html (or subdomain folder)
- Set folder permissions: `uploads/` → 755
- Change database credentials in `config.php`

## Security reminders
- Never commit real `config.php` with passwords
- Use `.gitignore` for `config.php`, `uploads/*`, `*.log`

Enjoy building!