# Barrios Salon Management System - Quick Start Guide

## Prerequisites
- PHP 8.2+
- MySQL 8.0+
- Composer
- Node.js & npm

## Installation Steps

### 1. Clone or Initialize Project
```bash
cd barrios_salon
```

### 2. Install PHP Dependencies
```bash
composer install
```

### 3. Install Node Dependencies
```bash
npm install
```

### 4. Create Environment File
```bash
cp .env.example .env
```

### 5. Generate Application Key
```bash
php artisan key:generate
```

### 6. Configure Database
Edit `.env` and set:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=salon_db
DB_USERNAME=root
DB_PASSWORD=
```

### 7. Run Migrations
```bash
php artisan migrate
```

The system will automatically create the database if it doesn't exist.

### 8. Build Frontend Assets
```bash
npm run build
```

Or for development with hot reload:
```bash
npm run dev
```

### 9. Start Development Server
```bash
php artisan serve
```

Server will run on: **http://localhost:8000**

### 10. Access the Application
1. Open http://localhost:8000 in your browser
2. Click "Register" to create a new account
3. Verify your email address
4. Login to access the system

## First Time Setup

### Create Initial Services (Optional)
Once logged in:
1. Navigate to Services
2. Click "Add Service"
3. Create sample services:
   - Manicure ($25, 30 mins)
   - Pedicure ($30, 35 mins)
   - Gel Polish ($35, 45 mins)
   - Nail Extension ($40, 60 mins)

### Book Your First Appointment
1. Go to Appointments
2. Click "Book Appointment"
3. Select a service
4. Choose a future date and time
5. Click "Book Appointment"

### Process Payment
1. Go to the appointment
2. Click "Pay" button
3. Select payment method
4. Confirm payment

## Key URLs

| Page | URL |
|------|-----|
| Home | http://localhost:8000 |
| Login | http://localhost:8000/login |
| Register | http://localhost:8000/register |
| Dashboard | http://localhost:8000/dashboard |
| Services | http://localhost:8000/services |
| Appointments | http://localhost:8000/appointments |
| Payments | http://localhost:8000/payments |
| Profile | http://localhost:8000/profile |

## Available Commands

```bash
# Run migrations
php artisan migrate

# Reset database (caution: deletes all data)
php artisan migrate:reset

# Create new migration
php artisan make:migration migration_name

# Seed database with test data
php artisan db:seed

# Clear all caches
php artisan cache:clear

# Generate optimized autoloader
php artisan optimize

# List all routes
php artisan route:list

# Run tests
php artisan test

# Create backup
php artisan backup:run
```

## Useful Database Commands

```bash
# Access MySQL CLI
mysql -u root -p salon_db

# Useful MySQL queries:
# See all services
SELECT * FROM services;

# See all appointments
SELECT * FROM appointments;

# See all payments
SELECT * FROM payments;

# See user appointments
SELECT * FROM appointments WHERE user_id = 1;
```

## Troubleshooting

### White Page or 500 Error
1. Check `.env` file is properly configured
2. Verify database connection: `php artisan tinker` then `DB::connection()->getDatabaseName()`
3. Check logs in `storage/logs/laravel.log`
4. Clear cache: `php artisan cache:clear`

### Database Connection Error
1. Ensure MySQL is running
2. Verify credentials in `.env`
3. Ensure database `salon_db` exists
4. Run migrations: `php artisan migrate`

### Assets Not Loading
1. Rebuild assets: `npm run build`
2. Clear cache: `php artisan cache:clear`
3. Check `public/build/` directory exists

### Cannot Reset Password
1. Ensure email is configured in `.env`
2. Check mail settings (SMTP, Mailtrap, etc.)
3. Use `php artisan tinker` to manually reset

```php
// In tinker
$user = App\Models\User::find(1);
$user->password = Hash::make('newpassword');
$user->save();
```

### Port 8000 Already in Use
Use a different port:
```bash
php artisan serve --port=8001
```

## Features Checklist

- ✅ User Authentication
- ✅ User Registration with Email Verification
- ✅ Service Management (CRUD)
- ✅ Appointment Booking
- ✅ Duplicate Prevention
- ✅ Past Date Prevention
- ✅ Payment Processing
- ✅ Payment History
- ✅ User Authorization
- ✅ Error Handling
- ✅ Input Validation
- ✅ Modern UI Design
- ✅ Responsive Layout
- ✅ Dark Mode Support

## Development Tips

### Using Tinker Console
```bash
php artisan tinker

# Check database
DB::table('services')->get();

# Create test service
App\Models\Service::create([
    'name' => 'Test Service',
    'price' => 50,
    'duration' => 30
]);

# Find user
App\Models\User::find(1);
```

### Debugging
Add to `.env`:
```
APP_DEBUG=true
LOG_CHANNEL=stack
```

### Database Seeding
Create seeders for testing:
```bash
php artisan make:seeder ServiceSeeder
```

### File Uploads
If adding file uploads later:
```bash
php artisan storage:link
```

## Next Steps

1. **Customize**: Update salon name, branding
2. **Launch**: Deploy to production
3. **Monitor**: Set up error logging
4. **Backup**: Configure automatic backups
5. **Extend**: Add additional features (reports, SMS, etc.)

## Support Resources

- Email Integration: Check `config/mail.php`
- Storage: Check `config/filesystems.php`
- Cache: Check `config/cache.php`
- Database: Check `config/database.php`
- API Rate Limiting: Check `app/Http/Requests/`

## Production Deployment

Before deploying to production:

```bash
# Optimize everything
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Generate app key
php artisan key:generate

# Set permissions
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Run migrations
php artisan migrate --force
```

---

**System Features:**
- Modern, clean, and sleek UI
- Custom color scheme (#ff5bbc, #fdd1e9, #e8ecef, #8d9aaf, #2b2c43)
- Comprehensive error handling
- Duplicate appointment prevention
- Past date/time protection
- Full MVC architecture
- RESTful API patterns
- User authentication and authorization

**Ready to manage your salon!** 🎉
