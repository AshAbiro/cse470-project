# Amusement Park Management System

A comprehensive Laravel-based web application for managing an amusement park with features for admin, staff, and client users.

## Features

### Admin Dashboard
- Manage rides and attractions
- Manage rooms and accommodations
- Manage tickets and bookings
- View analytics and statistics
- Handle maintenance requests
- Manage park operations
- View customer ratings

### Staff Dashboard
- Book attractions for guests
- Manage food orders
- Handle room bookings
- Track maintenance tasks
- View customer notifications
- Chat with admin

### Client Portal
- Browse and book rides
- Reserve rooms
- Purchase tickets
- Make dining reservations
- View booking history
- Rate experiences
- Parking reservations
- Map and information

## System Requirements

- **PHP**: 8.0 or higher
- **MySQL**: 5.7 or higher
- **Node.js**: 12 or higher (for asset compilation)
- **Composer**: Latest version

## Installation

### 1. Setup Environment
```bash
cd Amusement_Park_Management_System
cp .env.example .env
```

### 2. Install Dependencies
```bash
composer install --no-dev
```

### 3. Generate Application Key
```bash
php artisan key:generate
```

### 4. Configure Database
Edit `.env` file with your database credentials:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=amusement_park_management_system
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Run Migrations
```bash
php artisan migrate
```

### 6. Seed Database (Optional)
```bash
php artisan db:seed
```

### 7. Compile Assets (Optional)
```bash
npm install
npm run build
```

## Running the Application

### Development Server
```bash
php -S localhost:8000 -t public/
```

Or using Laravel's Artisan command:
```bash
php artisan serve
```

Then access the application at **http://localhost:8000**

## User Roles

### Admin
- **Path**: `/home` or `/admin/*`
- **Login**: Via admin login page
- **Permissions**: Full system access, manage all resources

### Staff
- **Path**: `/staff/*`
- **Login**: Via staff login page
- **Permissions**: Book attractions, manage orders, handle requests

### Client/User
- **Path**: `/dashboard` and `/client/*`
- **Permissions**: Browse attractions, make bookings, rate experiences

## Database Structure

The system includes the following main entities:
- **Users**: System users with different roles
- **Rides**: Amusement park attractions
- **Rooms**: Accommodations (Nawab Palace)
- **Bookings**: Ride/attraction reservations
- **RoomBookings**: Room reservations
- **Tickets**: Entry and ride tickets
- **Dishes**: Food menu items
- **DishBookings**: Food orders
- **ParkingSlots**: Parking availability
- **MaintenanceReports**: Park maintenance tracking
- **Ratings**: Customer reviews and ratings

## Key Routes

### Public Routes
- `/` - Welcome page
- `/health` - Health check endpoint

### Authentication
- `/login` - User login
- `/register` - User registration
- `/admin/login` - Admin login
- `/staff/login` - Staff login

### Admin Routes
- `/home` - Admin dashboard
- `/admin/manage-rides` - Manage attractions
- `/admin/nawab-palace` - Manage rooms
- `/admin/analytics` - View statistics
- `/admin/bookings/*` - View bookings

### Staff Routes
- `/staff/dashboard` - Staff dashboard
- `/staff/book-for-guest` - Make bookings for guests
- `/staff/requests` - View requests

### Client Routes
- `/dashboard` - Client dashboard
- `/client/book-rides` - Browse attractions
- `/client/nawab-palace` - View accommodations
- `/client/parking` - Parking reservations

## Controllers

- **AdminController**: Admin operations and dashboard
- **StaffController**: Staff operations
- **ClientController**: Client operations
- **RideController**: Manage attractions
- **RoomController**: Manage accommodations
- **ClientProfileController**: User profile management
- **HealthController**: Health check

## Technologies Used

- **Framework**: Laravel 9
- **Frontend**: Blade Templates, Livewire, Tailwind CSS
- **Database**: MySQL
- **Authentication**: Laravel Fortify & Sanctum
- **Real-time**: Laravel Livewire

## File Structure

```
├── app/
│   ├── Console/        # Artisan commands
│   ├── Exceptions/     # Exception handling
│   ├── Http/           # Controllers, middleware, requests
│   ├── Livewire/       # Real-time components
│   └── Models/         # Eloquent models
├── database/
│   ├── migrations/     # Schema migrations
│   └── seeders/        # Database seeders
├── resources/
│   ├── css/            # Stylesheets
│   ├── js/             # JavaScript
│   └── views/          # Blade templates
├── routes/             # API and web routes
├── public/             # Publicly accessible files
└── config/             # Configuration files
```

## Troubleshooting

### Database Connection Error
- Verify MySQL is running
- Check `.env` database credentials
- Run `php artisan migrate` again

### Class Not Found Error
- Run `composer dump-autoload`
- Clear cache: `php artisan cache:clear`

### Permission Denied
- Ensure `storage/` and `bootstrap/cache/` are writable
- Run: `chmod -R 777 storage bootstrap/cache`

## Support

For issues or questions, please contact the development team or check the project documentation.

## License

This project is proprietary software for the Amusement Park Management System.
