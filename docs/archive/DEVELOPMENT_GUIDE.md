# 🔨 Development Guide

Comprehensive guide for developing and extending the Amusement Park Management System.

## Getting Started

### 1. Local Development Setup

```bash
# Clone the repository
git clone <repository-url>
cd Amusement_Park_Management_System

# Install dependencies
composer install

# Setup environment
cp .env.example .env

# Generate key
php artisan key:generate

# Create database
mysql -u root -p -e "CREATE DATABASE amusement_park_management_system;"

# Configure .env with database credentials
nano .env

# Run migrations
php artisan migrate

# Seed database (optional)
php artisan db:seed

# Start development server
php -S localhost:8000 -t public/
```

### 2. IDE Setup

**Recommended Extensions for VS Code:**
- PHP Intelephense
- Laravel Extension Pack
- Database Client
- Thunder Client (API testing)

**Recommended for PhpStorm:**
- Laravel Plugin
- PHP Annotations
- Database Tools and SQL

## Architecture Overview

### MVC Pattern

```
Request
   ↓
Router (routes/web.php, routes/api.php)
   ↓
Middleware (app/Http/Middleware/)
   ↓
Controller (app/Http/Controllers/)
   ↓
Service (app/Services/) - Business Logic
   ↓
Model (app/Models/) - Database
   ↓
Response
```

### Service Layer Pattern

Services provide business logic separation:

```php
// Controller uses service
public function store(Request $request)
{
    $booking = $this->bookingService->createRideBooking(
        auth()->id(),
        $request->ride_id,
        $request->booking_date
    );
    
    return response()->json($booking);
}

// Service handles business logic
public function createRideBooking($userId, $rideId, $date)
{
    DB::beginTransaction();
    try {
        $booking = Booking::create([...]);
        $this->createNotification($userId, ...);
        DB::commit();
        return $booking;
    } catch (Exception $e) {
        DB::rollback();
        throw $e;
    }
}
```

## File Organization

### Controllers

Location: `app/Http/Controllers/`

**Naming Convention:** `{Resource}Controller.php`

```php
namespace App\Http\Controllers;

use App\Models\Ride;
use App\Services\BookingService;

class RideController extends Controller
{
    public function __construct(private BookingService $bookingService) {}
    
    public function index()
    {
        return Ride::active()->paginate(15);
    }
    
    public function show(Ride $ride)
    {
        return $ride->load('ratings');
    }
}
```

### Models

Location: `app/Models/`

**Naming Convention:** Singular, PascalCase `{Model}.php`

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ride extends Model
{
    protected $fillable = ['name', 'description', 'price'];
    
    protected $casts = [
        'price' => 'decimal:2',
        'created_at' => 'datetime'
    ];
    
    // Relationships
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
    
    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
    
    // Accessors
    public function getFormattedPriceAttribute()
    {
        return formatCurrency($this->price);
    }
}
```

### Services

Location: `app/Services/`

**Naming Convention:** `{Domain}Service.php`

```php
namespace App\Services;

use App\Models\Booking;
use Illuminate\Database\DatabaseManager;

class BookingService
{
    public function __construct(private DatabaseManager $db) {}
    
    public function createBooking($data)
    {
        $this->db->beginTransaction();
        try {
            $booking = Booking::create($data);
            // Additional logic
            $this->db->commit();
            return $booking;
        } catch (Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }
}
```

### Migrations

Location: `database/migrations/`

**Naming:** `YYYY_MM_DD_HHmmss_{description}.php`

```php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRidesTable extends Migration
{
    public function up()
    {
        Schema::create('rides', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->integer('capacity');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->index('status');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('rides');
    }
}
```

### Views

Location: `resources/views/`

**Naming Convention:** `{resource}.blade.php` or `{resource}/{action}.blade.php`

```blade
<div class="ride-card">
    <h3>{{ $ride->name }}</h3>
    <p>{{ $ride->description }}</p>
    <p class="price">{{ formatCurrency($ride->price) }}</p>
    
    @auth
        <button>Book Now</button>
    @else
        <a href="{{ route('login') }}">Login to book</a>
    @endauth
</div>
```

## Creating New Features

### Adding a New Resource (Step by Step)

#### 1. Create Migration

```bash
php artisan make:migration create_reviews_table
```

```php
public function up()
{
    Schema::create('reviews', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id');
        $table->unsignedBigInteger('ride_id');
        $table->integer('rating')->min(1)->max(5);
        $table->text('comment')->nullable();
        $table->timestamps();
        
        $table->foreign('user_id')->references('id')->on('users');
        $table->foreign('ride_id')->references('id')->on('rides');
    });
}
```

#### 2. Create Model

```bash
php artisan make:model Review
```

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['user_id', 'ride_id', 'rating', 'comment'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function ride()
    {
        return $this->belongsTo(Ride::class);
    }
}
```

#### 3. Create Controller

```bash
php artisan make:controller ReviewController --resource
```

```php
namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ride_id' => 'required|exists:rides,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string'
        ]);
        
        $review = Review::create([
            ...$validated,
            'user_id' => auth()->id()
        ]);
        
        return response()->json($review, 201);
    }
}
```

#### 4. Add Routes

```php
// routes/api.php
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/reviews', [ReviewController::class, 'store']);
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy']);
});
```

#### 5. Run Migration

```bash
php artisan migrate
```

## Testing

### Unit Tests

```bash
php artisan make:test BookingServiceTest --unit
```

```php
namespace Tests\Unit;

use Tests\TestCase;
use App\Services\BookingService;

class BookingServiceTest extends TestCase
{
    public function test_creates_booking()
    {
        $service = app(BookingService::class);
        
        $booking = $service->createRideBooking([
            'user_id' => 1,
            'ride_id' => 1,
            'date' => now()
        ]);
        
        $this->assertNotNull($booking->id);
    }
}
```

### Feature Tests

```bash
php artisan make:test BookingTest
```

```php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Ride;

class BookingTest extends TestCase
{
    public function test_user_can_create_booking()
    {
        $user = User::factory()->create();
        $ride = Ride::factory()->create();
        
        $response = $this->actingAs($user)
            ->post('/api/bookings', [
                'ride_id' => $ride->id,
                'booking_date' => now()->addDay()
            ]);
        
        $response->assertStatus(201);
    }
}
```

### Run Tests

```bash
php artisan test
php artisan test tests/Feature/BookingTest.php
php artisan test --coverage
```

## Debugging

### Using Laravel Debugbar

```bash
composer require --dev barryvdh/laravel-debugbar
php artisan vendor:publish --provider="Barryvdh\Debugbar\ServiceProvider"
```

### Logging

```php
Log::info('Booking created', ['booking_id' => $booking->id]);
Log::error('Booking failed', ['error' => $e->getMessage()]);

// Check logs in storage/logs/
```

### Debugging with dd()

```php
public function show($id)
{
    $ride = Ride::find($id);
    dd($ride); // Dump and die
}
```

## Common Tasks

### Adding a Helper Function

Add to `app/Helpers/AppHelpers.php`:

```php
function getBookingTotal($bookingId)
{
    $booking = Booking::find($bookingId);
    return $booking->amount ?? 0;
}
```

### Adding Middleware

```bash
php artisan make:middleware CheckBookingStatus
```

```php
public function handle(Request $request, Closure $next)
{
    $booking = $request->route('booking');
    
    if ($booking->status !== 'confirmed') {
        return response()->json(['error' => 'Booking not confirmed'], 403);
    }
    
    return $next($request);
}
```

Register in `app/Http/Kernel.php`:

```php
protected $routeMiddleware = [
    // ...
    'booking.status' => \App\Http\Middleware\CheckBookingStatus::class,
];
```

### Creating Events

```bash
php artisan make:event BookingConfirmed
```

```php
namespace App\Events;

use App\Models\Booking;
use Illuminate\Foundation\Events\Dispatchable;

class BookingConfirmed
{
    use Dispatchable;
    
    public function __construct(public Booking $booking) {}
}
```

Trigger event:
```php
BookingConfirmed::dispatch($booking);
```

Listen for event:
```php
Event::listen(BookingConfirmed::class, function ($event) {
    // Send email, notification, etc.
});
```

### Creating Jobs

```bash
php artisan make:job ProcessBookingPayment
```

```php
namespace App\Jobs;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

class ProcessBookingPayment implements ShouldQueue
{
    use Queueable, SerializesModels;
    
    public function handle()
    {
        // Process payment
    }
}
```

Dispatch job:
```php
ProcessBookingPayment::dispatch($booking);
```

## Best Practices

### 1. Use Repository Pattern for Complex Queries

```php
interface BookingRepositoryInterface
{
    public function getActiveBookings($userId);
    public function getPendingBookings();
}

class BookingRepository implements BookingRepositoryInterface
{
    public function getActiveBookings($userId)
    {
        return Booking::where('user_id', $userId)
            ->whereIn('status', ['pending', 'confirmed'])
            ->with('ride')
            ->get();
    }
}
```

### 2. Use Form Requests for Validation

```bash
php artisan make:request StoreBookingRequest
```

```php
namespace App\Http\Requests;

class StoreBookingRequest extends FormRequest
{
    public function rules()
    {
        return [
            'ride_id' => 'required|exists:rides,id',
            'date' => 'required|date|after:today',
            'guests' => 'required|integer|min:1'
        ];
    }
}
```

### 3. Use Dependency Injection

```php
public function __construct(
    private BookingService $bookingService,
    private NotificationService $notificationService
) {}
```

### 4. Use Scopes for Query Reusability

```php
// Model
public function scopeActive($query)
{
    return $query->where('status', 'active');
}

public function scopeExpensive($query)
{
    return $query->where('price', '>', 5000);
}

// Usage
Ride::active()->expensive()->get();
```

### 5. Use Mutators for Data Transformation

```php
protected function setNameAttribute($value)
{
    $this->attributes['name'] = strtoupper($value);
}

protected function getFormattedPriceAttribute()
{
    return '$' . number_format($this->price, 2);
}
```

## Coding Standards

### PSR-12 Compliance

```php
// Correct
namespace App\Models;

class User extends Model
{
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}

// Incorrect
namespace App\Models;
class User extends Model {
    public function bookings() {
        return $this->hasMany(Booking::class);
    }
}
```

### Naming Conventions

| Type | Convention | Example |
|------|-----------|---------|
| Classes | PascalCase | `UserController`, `BookingService` |
| Methods | camelCase | `createBooking()`, `getUserBookings()` |
| Variables | camelCase | `$userId`, `$bookingDate` |
| Constants | UPPER_SNAKE_CASE | `MAX_GUESTS`, `DEFAULT_PRICE` |
| Tables | snake_case_plural | `users`, `ride_bookings` |
| Columns | snake_case | `user_id`, `created_at` |

## Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Laravel Best Practices](https://laravel.com/docs/testing)
- [PSR-12 Code Style](https://www.php-fig.org/psr/psr-12/)
- [Design Patterns in PHP](https://refactoring.guru/design-patterns/php)

---

**Last Updated**: January 2024  
**Status**: Active Development
