# 📡 API Documentation

Comprehensive API reference for the Amusement Park Management System.

## Base URL

```
http://localhost:8000/api
```

## Authentication

The API uses **Laravel Sanctum** for token-based authentication.

### Getting an API Token

```bash
POST /api/login
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "password"
}
```

**Response:**
```json
{
  "token": "1|abcdefgh..."
}
```

### Using the Token

Include the token in the Authorization header:

```bash
Authorization: Bearer {token}
```

## Response Format

All API responses follow a consistent JSON format:

### Success Response
```json
{
  "success": true,
  "message": "Operation successful",
  "data": {
    // Response data here
  }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error message",
  "errors": {
    "field_name": ["Error message"]
  }
}
```

## HTTP Status Codes

- `200` - OK (Successful request)
- `201` - Created (Resource successfully created)
- `400` - Bad Request (Invalid input)
- `401` - Unauthorized (Missing or invalid token)
- `403` - Forbidden (Insufficient permissions)
- `404` - Not Found (Resource not found)
- `422` - Unprocessable Entity (Validation error)
- `500` - Internal Server Error

## Endpoints

### Authentication

#### Login
```
POST /api/login
```

**Request:**
```json
{
  "email": "user@example.com",
  "password": "password"
}
```

**Response:**
```json
{
  "token": "1|eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
}
```

#### Logout
```
POST /api/logout
```

**Headers:** `Authorization: Bearer {token}`

**Response:**
```json
{
  "success": true,
  "message": "Logged out successfully"
}
```

### User Management

#### Get Current User Profile
```
GET /api/user
```

**Headers:** `Authorization: Bearer {token}`

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "03001234567",
    "role": "client",
    "email_verified_at": "2024-01-15T10:30:00Z",
    "created_at": "2024-01-10T15:20:00Z"
  }
}
```

#### Update User Profile
```
PUT /api/user
```

**Headers:** `Authorization: Bearer {token}`

**Request:**
```json
{
  "name": "John Doe Updated",
  "phone": "03009876543",
  "password": "newpassword"
}
```

### Rides & Attractions

#### Get All Rides
```
GET /api/rides
```

**Query Parameters:**
- `page` - Page number (default: 1)
- `per_page` - Items per page (default: 15)
- `status` - Filter by status (active, inactive)
- `sort` - Sort field (name, price, created_at)
- `order` - Sort order (asc, desc)

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Roller Coaster",
      "description": "Thrilling ride...",
      "price": 1500,
      "capacity": 50,
      "duration": 180,
      "status": "active",
      "image_url": "/storage/rides/1.jpg"
    }
  ],
  "pagination": {
    "total": 10,
    "per_page": 15,
    "current_page": 1,
    "last_page": 1
  }
}
```

#### Get Ride Details
```
GET /api/rides/{id}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Roller Coaster",
    "description": "Thrilling ride...",
    "price": 1500,
    "capacity": 50,
    "duration": 180,
    "status": "active",
    "image_url": "/storage/rides/1.jpg",
    "ratings": {
      "average": 4.5,
      "count": 25
    }
  }
}
```

#### Get Active Rides (Public)
```
GET /api/rides/active
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Roller Coaster",
      "price": 1500,
      "capacity": 50
    }
  ]
}
```

### Rooms & Accommodations

#### Get All Rooms
```
GET /api/rooms
```

**Query Parameters:**
- `page` - Page number (default: 1)
- `per_page` - Items per page (default: 15)
- `check_in` - Check-in date (YYYY-MM-DD)
- `check_out` - Check-out date (YYYY-MM-DD)
- `sort` - Sort field (price, rating, created_at)

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Deluxe Suite",
      "description": "Luxury room...",
      "price_per_night": 5000,
      "capacity": 2,
      "status": "available",
      "amenities": ["WiFi", "AC", "TV"],
      "image_url": "/storage/rooms/1.jpg"
    }
  ],
  "pagination": {
    "total": 20,
    "per_page": 15,
    "current_page": 1
  }
}
```

#### Get Room Details
```
GET /api/rooms/{id}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Deluxe Suite",
    "description": "Luxury room...",
    "price_per_night": 5000,
    "capacity": 2,
    "status": "available",
    "amenities": ["WiFi", "AC", "TV"],
    "image_url": "/storage/rooms/1.jpg",
    "ratings": {
      "average": 4.8,
      "count": 15
    }
  }
}
```

### Bookings

#### Get User Bookings
```
GET /api/bookings
```

**Headers:** `Authorization: Bearer {token}`

**Query Parameters:**
- `type` - Filter by type (ride, room, food)
- `status` - Filter by status (pending, confirmed, cancelled)
- `page` - Page number (default: 1)

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "reference": "BK001-2024-001",
      "type": "ride",
      "ride_id": 1,
      "ride_name": "Roller Coaster",
      "date": "2024-01-20",
      "time": "10:00",
      "status": "confirmed",
      "amount": 1500,
      "created_at": "2024-01-15T10:30:00Z"
    }
  ],
  "pagination": {
    "total": 5,
    "per_page": 15,
    "current_page": 1
  }
}
```

#### Create Booking
```
POST /api/bookings
```

**Headers:** `Authorization: Bearer {token}`

**Request:**
```json
{
  "booking_type": "ride",
  "ride_id": 1,
  "booking_date": "2024-01-20",
  "booking_time": "10:00",
  "number_of_guests": 2,
  "special_requests": "Wheelchair accessible"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Booking created successfully",
  "data": {
    "id": 1,
    "reference": "BK001-2024-001",
    "type": "ride",
    "status": "pending",
    "amount": 3000,
    "created_at": "2024-01-15T10:30:00Z"
  }
}
```

#### Confirm Booking
```
PUT /api/bookings/{id}/confirm
```

**Headers:** `Authorization: Bearer {token}`

**Response:**
```json
{
  "success": true,
  "message": "Booking confirmed successfully",
  "data": {
    "id": 1,
    "status": "confirmed"
  }
}
```

#### Cancel Booking
```
PUT /api/bookings/{id}/cancel
```

**Headers:** `Authorization: Bearer {token}`

**Request:**
```json
{
  "reason": "Cannot attend"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Booking cancelled successfully",
  "data": {
    "id": 1,
    "status": "cancelled"
  }
}
```

### Tickets

#### Get Available Tickets
```
GET /api/tickets
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Entry Ticket",
      "type": "entry",
      "price": 2000,
      "validity_days": 1,
      "description": "Single day entry"
    }
  ]
}
```

#### Purchase Ticket
```
POST /api/tickets/purchase
```

**Headers:** `Authorization: Bearer {token}`

**Request:**
```json
{
  "ticket_id": 1,
  "quantity": 2
}
```

**Response:**
```json
{
  "success": true,
  "message": "Tickets purchased successfully",
  "data": {
    "id": 1,
    "reference": "TK001-2024-001",
    "quantity": 2,
    "total_amount": 4000,
    "created_at": "2024-01-15T10:30:00Z"
  }
}
```

### Ratings & Reviews

#### Get Ratings for Ride
```
GET /api/rides/{id}/ratings
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "user_name": "John Doe",
      "rating": 5,
      "review": "Amazing experience!",
      "created_at": "2024-01-14T15:30:00Z"
    }
  ],
  "average_rating": 4.5,
  "total_ratings": 25
}
```

#### Create Rating
```
POST /api/ratings
```

**Headers:** `Authorization: Bearer {token}`

**Request:**
```json
{
  "rateable_type": "Ride",
  "rateable_id": 1,
  "rating": 5,
  "review": "Excellent experience!"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Rating submitted successfully",
  "data": {
    "id": 1,
    "rating": 5,
    "created_at": "2024-01-15T10:30:00Z"
  }
}
```

### Notifications

#### Get User Notifications
```
GET /api/notifications
```

**Headers:** `Authorization: Bearer {token}`

**Query Parameters:**
- `read` - Filter by read status (true, false)
- `page` - Page number (default: 1)

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Booking Confirmed",
      "message": "Your booking #BK001 has been confirmed",
      "type": "booking",
      "read": false,
      "created_at": "2024-01-15T10:30:00Z"
    }
  ],
  "unread_count": 3
}
```

#### Mark Notification as Read
```
PUT /api/notifications/{id}/read
```

**Headers:** `Authorization: Bearer {token}`

**Response:**
```json
{
  "success": true,
  "message": "Notification marked as read"
}
```

## Error Handling

### Common Errors

#### 401 Unauthorized
```json
{
  "success": false,
  "message": "Unauthenticated"
}
```

#### 422 Validation Error
```json
{
  "success": false,
  "message": "Validation error",
  "errors": {
    "email": ["The email field is required."],
    "password": ["Password must be at least 8 characters."]
  }
}
```

#### 404 Not Found
```json
{
  "success": false,
  "message": "Resource not found"
}
```

## Rate Limiting

API requests are limited to **60 requests per minute** per user/token.

Headers will include:
- `X-RateLimit-Limit`: 60
- `X-RateLimit-Remaining`: 59
- `X-RateLimit-Reset`: 1642253400

## Pagination

Paginated endpoints support the following parameters:

- `page` - Current page (default: 1)
- `per_page` - Items per page (default: 15, max: 100)

Response includes:
```json
{
  "pagination": {
    "total": 100,
    "per_page": 15,
    "current_page": 1,
    "last_page": 7
  }
}
```

## Filtering & Sorting

### Filtering

Use query parameters to filter results:

```
GET /api/rides?status=active&price_min=1000&price_max=5000
```

### Sorting

Use the `sort` and `order` parameters:

```
GET /api/rides?sort=price&order=desc
```

Allowed values for `order`: `asc`, `desc`

## WebSocket Events (Real-time)

Real-time updates are available through WebSocket (Pusher/Laravel Broadcasting):

### Subscribe to Channel
```javascript
Echo.channel('bookings')
  .listen('BookingConfirmed', (event) => {
    console.log('Booking confirmed:', event.booking);
  });
```

### Available Events
- `BookingCreated` - When a new booking is made
- `BookingConfirmed` - When booking is confirmed
- `BookingCancelled` - When booking is cancelled
- `NotificationSent` - When user receives notification

## Code Examples

### JavaScript (Fetch)

```javascript
// Login
const response = await fetch('/api/login', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    email: 'user@example.com',
    password: 'password'
  })
});

const { token } = await response.json();

// Get rides
const ridesResponse = await fetch('/api/rides', {
  headers: { 'Authorization': `Bearer ${token}` }
});

const rides = await ridesResponse.json();
```

### PHP (Laravel HTTP Client)

```php
use Illuminate\Support\Facades\Http;

// Login
$response = Http::post('/api/login', [
    'email' => 'user@example.com',
    'password' => 'password'
]);

$token = $response['token'];

// Get rides
$rides = Http::withToken($token)->get('/api/rides');
```

### cURL

```bash
# Login
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","password":"password"}'

# Get rides
curl -X GET http://localhost:8000/api/rides \
  -H "Authorization: Bearer {token}"
```

## Changelog

### Version 1.0.0 (January 2024)
- Initial API release
- Authentication and user management
- Rides and bookings endpoints
- Rooms and ratings endpoints
- Tickets and notifications

---

**Last Updated**: January 2024  
**Status**: Production Ready
