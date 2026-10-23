# HatchesHouse Event & Media

A black-and-white PHP/MySQL event website and ticketing foundation for HatchesHouse.

## Stack
- PHP 8+
- MySQL/MariaDB
- Tailwind CSS via CDN
- Paystack API for payments
- QRCode.js for browser-side ticket QR generation

## Included
1. Premium responsive public website
2. Upcoming events
3. Event detail pages
4. Registration
5. Ticket types
6. Paystack initialization + verification
7. MoMo-capable Paystack checkout
8. Digital ticket codes
9. QR ticket display
10. Admin login/dashboard
11. Event creation
12. Registration/payment reporting
13. Ticket check-in
14. Contact form
15. Dynamic Open Graph/Twitter metadata

## Local setup
1. Create a MySQL database by importing `database/hatcheshouse.sql`.
2. Edit `config/config.php`.
3. Set `APP_URL` to your local URL.
4. Add your Paystack secret/public keys.
5. Put the folder inside XAMPP `htdocs`.
6. Open `http://localhost/hatcheshouse/`.

## Admin
Use the seeded admin account shown in the SQL comments, then change it immediately.

## Production checklist
- Replace `APP_URL`.
- Use live Paystack keys.
- Add HTTPS.
- Change admin credentials.
- Configure Paystack webhooks/signature verification.
- Add server-side image upload validation.
- Add proper email/SMS notifications.
- Add camera QR scanning to check-in.
- Add backups and monitoring.


## Next release
- Camera QR ticket scanning with html5-qrcode.
- Manual ticket validation fallback.
- Paystack webhook signature verification.
- GHS payment amount validation before tickets are issued.

Configure Paystack webhook URL as `/payment/webhook.php` in production.
