Nayxixi — Full ready for XAMPP (PHP + MySQL)

Instructions:
1. Copy the folder `nayxixi` into XAMPP htdocs: e.g. C:\xampp\htdocs\nayxixi
2. Start Apache and MySQL in XAMPP Control Panel.
3. Create database `nayxixi_db` in phpMyAdmin or let install_seed.php create tables.
   Recommended: import sql/schema.sql first (it creates DB and tables), then run install_seed.php once.
4. Open in browser: http://localhost/nayxixi/
5. To populate sample data, open: http://localhost/nayxixi/install_seed.php

Default credentials (after running install_seed.php):
- Admin: admin@example.com / 1234
- User: user@example.com / 1234

If you see "Seed already ran (users exist).", you can either clear tables in phpMyAdmin or delete users to rerun seed.
