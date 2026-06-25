<<<<<<< HEAD
# Deukhuri Mobile & Computer Repairing Center — Frontend (PHP)

> These PHP files are static project files. They do **not** run in the Lovable preview.
> Copy the `deukhuri_shop/` folder into your XAMPP `htdocs/` to use them.

## Setup
1. Copy `deukhuri_shop/` into `C:/xampp/htdocs/` (or your server's web root).
2. Start Apache + MySQL in XAMPP.
3. Open phpMyAdmin → import `database.sql`.
4. Edit `includes/db.php` if your MySQL user/pass differ from `root` / empty.
5. Visit `http://localhost/deukhuri_shop/`.
6. Make sure the `uploads/` folder is writable by the web server.

## What's included (front part only)
- `index.php`, `mobiles.php`, `parts.php`, `vapes.php`, `contact.php`
- `product_modal.php` — AJAX endpoint returning product JSON
- `includes/` — `db.php`, `functions.php`, `header.php`, `footer.php`, `category_page.php`
- `assets/css/` — `style.css`, `responsive.css`, `admin.css`
- `assets/js/` — `theme.js`, `main.js`, `search.js`, `modal.js`
- `assets/images/placeholder.png.svg` — rename to `placeholder.png` if you want a PNG
- `database.sql`

## Not included (per your instruction "FRONT PART only")
- The `/admin/` panel (login, dashboard, add/edit/delete product, image upload)
- `setup_admin.php`

Ask for the admin panel next and I'll generate those files.

## Placeholders to replace
- `assets/js/modal.js` → `WHATSAPP_NUMBER`
- `includes/footer.php` → WhatsApp link number
- `contact.php` → phone, email, Google Maps embed URL
- `includes/db.php` → DB credentials if needed
=======
# Deukhuri-mobile-center
DMC
>>>>>>> 29ad6c2aca2c868acd95db9fbee3ab8a06167958
