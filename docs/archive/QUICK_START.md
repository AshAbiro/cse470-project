# 🚀 Quick Start Guide

Get the Amusement Park Management System running in minutes!

## Prerequisites Check

Before starting, verify you have:
- PHP 8.0+ installed
- MySQL 5.7+ running
- Composer installed
- Git installed (optional)

Check versions:
```bash
php -v
mysql --version
composer --version
```

## 5-Minute Setup

### Step 1: Install Dependencies (2 minutes)

From the project root directory:

```bash
cd Amusement_Park_Management_System
composer install --no-dev
```

### Step 2: Setup Environment (1 minute)

```bash
cp .env.example .env
php artisan key:generate
```

### Step 3: Configure Database (1 minute)

Edit `.env` file and set:
```env
DB_DATABASE=amusement_park_management_system
DB_USERNAME=root
DB_PASSWORD=
```

Create database:
```bash
mysql -u root -p -e "CREATE DATABASE amusement_park_management_system;"
```

### Step 4: Run Migrations (1 minute)

```bash
php artisan migrate
php artisan db:seed
```

### Step 5: Start Server

```bash
php -S localhost:8000 -t public/
```

**✅ Done!** Access the app at: **http://localhost:8000**

---

## Login Credentials

Use these test accounts (after seeding):

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@example.com | password |
| Staff | staff@example.com | password |
| Client | client@example.com | password |

---

## Quick Navigation

### For Admins
- **Dashboard**: http://localhost:8000/home
- **Manage Rides**: http://localhost:8000/admin/manage-rides
- **Manage Rooms**: http://localhost:8000/admin/nawab-palace
- **Analytics**: http://localhost:8000/admin/analytics
- **Bookings**: http://localhost:8000/admin/ride-bookings

### For Staff
- **Dashboard**: http://localhost:8000/staff/dashboard
- **Book Guest**: http://localhost:8000/staff/book-for-guest
- **Food Orders**: http://localhost:8000/staff/food
- **Room Bookings**: http://localhost:8000/staff/rooms
- **My Ratings**: http://localhost:8000/staff/my-ratings

### For Clients
- **Dashboard**: http://localhost:8000/dashboard
- **Book Rides**: http://localhost:8000/client/book-rides
- **Browse Rooms**: http://localhost:8000/client/nawab-palace
- **My Bookings**: http://localhost:8000/client/booking-history
- **Rate Rides**: http://localhost:8000/client/rate-rides

---

## Common Commands

```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear

# Run tests
php artisan test

# Create new migration
php artisan make:migration migration_name

# Create new controller
php artisan make:controller ControllerName

# Create new model with migration
php artisan make:model ModelName -m

# List all routes
php artisan route:list

# Database operations
php artisan migrate              # Run migrations
php artisan migrate:rollback     # Undo last migration
php artisan db:seed              # Run seeders
php artisan tinker              # Interactive shell
```

---

## API Quick Start

### Get API Token

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"client@example.com","password":"password"}'
```

### Get Rides

```bash
curl -X GET http://localhost:8000/api/rides
```

### Get Your Bookings (Authenticated)

```bash
curl -X GET http://localhost:8000/api/bookings \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## Troubleshooting

### "MySQL connection refused"
```bash
# Make sure MySQL is running
# Windows: Start MySQL Service
# Linux: sudo systemctl start mysql
# macOS: brew services start mysql
```

### "Class not found" errors
```bash
composer dump-autoload
php artisan cache:clear
```

### "No application key"
```bash
php artisan key:generate
```

### Port 8000 already in use
```bash
# Use different port
php -S localhost:8001 -t public/

# Or kill existing process
# Windows: taskkill /PID processid /F
# Linux: kill -9 processid
```

### Permission denied on storage
```bash
# Windows: Right-click → Properties → Security → Edit permissions
# Linux: chmod -R 777 storage bootstrap/cache
```

---

## Next Steps

1. **Explore the Dashboard**: Login as admin and check out the features
2. **Read Documentation**: Check [README.md](README.md) for full details
3. **Review API**: See [API_DOCUMENTATION.md](API_DOCUMENTATION.md) for API endpoints
4. **Learn Development**: Read [DEVELOPMENT_GUIDE.md](DEVELOPMENT_GUIDE.md) to extend features
5. **Setup Production**: Follow [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md) when ready

---

## Feature Overview

### 🎢 Rides Management
Browse and book amusement park attractions with:
- Detailed descriptions and images
- Real-time availability
- Customer ratings
- Price comparison

### 🏨 Room Reservations
Book accommodations with:
- Date-based availability
- Multiple room types
- Amenity listings
- Room ratings and reviews

### 🍽️ Food Ordering
Order from park restaurants:
- Menu browsing
- Special dietary options
- Delivery to room/pickup
- Order history

### 🅿️ Parking Management
Reserve parking spots:
- Spot availability
- Reserved parking
- Payment integration
- Parking pass generation

### 💰 Booking System
Complete booking workflow:
- Multiple booking types (rides, rooms, food)
- Real-time confirmation
- Automatic notifications
- Booking history
- Easy cancellation

### ⭐ Ratings & Reviews
Share experiences:
- Rate attractions and rooms
- Leave detailed reviews
- Rate staff members
- View community ratings

### 📊 Analytics Dashboard
Admin analytics with:
- Revenue tracking
- Booking statistics
- Occupancy rates
- Customer growth
- Performance metrics

### 👥 Multi-Role System
Three user roles:
- **Admin**: Full park management
- **Staff**: Guest services and support
- **Client**: Guest booking and reviews

---

## Getting Help

### Documentation Files
- [README.md](README.md) - Project overview
- [API_DOCUMENTATION.md](API_DOCUMENTATION.md) - API reference
- [DEVELOPMENT_GUIDE.md](DEVELOPMENT_GUIDE.md) - Development standards
- [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md) - Production deployment
- [CONFIGURATION_GUIDE.md](CONFIGURATION_GUIDE.md) - Configuration & troubleshooting
- [ROADMAP.md](ROADMAP.md) - Future features and enhancements

### Need Help?
- Check the relevant documentation file
- Review the codebase comments
- Run tests: `php artisan test`
- Use Tinker shell: `php artisan tinker`
- Check logs: `tail -f storage/logs/laravel-*.log`

---

## Technology Stack

- **Framework**: Laravel 9
- **Database**: MySQL
- **Frontend**: Blade Templates, Livewire, Tailwind CSS
- **API**: RESTful with Sanctum
- **Authentication**: Laravel Fortify & Sanctum
- **Real-time**: Laravel Livewire
- **PHP**: 8.0+

---

## Project Structure

```
├── app/
│   ├── Http/Controllers/       # Route controllers
│   ├── Models/                 # Database models
│   ├── Services/               # Business logic
│   ├── Livewire/               # Real-time components
│   └── Helpers/                # Utility functions
├── database/
│   ├── migrations/             # Database schema
│   └── seeders/                # Sample data
├── resources/
│   └── views/                  # Blade templates
├── routes/
│   ├── web.php                # Web routes
│   └── api.php                # API routes
└── storage/
    ├── app/                   # File storage
    └── logs/                  # Application logs
```

---

## Tips & Best Practices

### Development
- Always run tests before committing: `php artisan test`
- Clear caches regularly: `php artisan cache:clear`
- Use `.env` for environment-specific config
- Never commit `.env` file to version control

### Database
- Write migrations for schema changes
- Use seeders for test data
- Create indexes for frequently queried columns
- Backup database before major changes

### Security
- Always validate user input
- Use Laravel's built-in security features
- Keep dependencies updated
- Use strong passwords
- Enable CSRF protection
- Implement rate limiting

### Performance
- Use eager loading to prevent N+1 queries
- Cache frequently accessed data
- Optimize database queries
- Use database indexes
- Monitor application logs

---

## Support & Contributing

### Issues & Bugs
1. Check [CONFIGURATION_GUIDE.md](CONFIGURATION_GUIDE.md) for troubleshooting
2. Review application logs
3. Check database connection
4. Test in isolation (Tinker shell)

### Contributing
Follow the [DEVELOPMENT_GUIDE.md](DEVELOPMENT_GUIDE.md) for:
- Coding standards (PSR-12)
- Testing practices
- Git workflow
- Pull request process

---

## Version Information

- **Current Version**: 1.0.0
- **Laravel Version**: 9.52.21
- **PHP Version**: 8.0.30+
- **Last Updated**: January 2024

---

## Checklist for Production

Before deploying to production:
- [ ] Set `APP_DEBUG=false` in `.env`
- [ ] Configure proper database (not sqlite)
- [ ] Setup email service
- [ ] Configure storage location
- [ ] Setup SSL/HTTPS
- [ ] Configure logging
- [ ] Setup monitoring
- [ ] Create database backups
- [ ] Test all functionality
- [ ] Run security audit

See [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md) for detailed instructions.

---

**Ready to start?** Run these commands:

```bash
cd Amusement_Park_Management_System
composer install --no-dev
php artisan key:generate
php artisan migrate --seed
php -S localhost:8000 -t public/
```

Then open **http://localhost:8000** in your browser! 🎉

---

**Last Updated**: January 2024  
**Status**: Ready to Use
