# Barrios Salon Management System - API & Database Reference

## API Routes

### Services Routes
```
GET    /services              - List all services               [services.index]
GET    /services/create       - Show create service form        [services.create]
POST   /services              - Store new service               [services.store]
GET    /services/{id}         - Show service details            [services.show]
GET    /services/{id}/edit    - Show edit service form          [services.edit]
PUT    /services/{id}         - Update service                  [services.update]
DELETE /services/{id}         - Delete service                  [services.destroy]
```

### Appointments Routes
```
GET    /appointments          - List user appointments          [appointments.index]
GET    /appointments/create   - Show create appointment form    [appointments.create]
POST   /appointments          - Store new appointment           [appointments.store]
GET    /appointments/{id}     - Show appointment details        [appointments.show]
GET    /appointments/{id}/edit - Show edit appointment form     [appointments.edit]
PUT    /appointments/{id}     - Update appointment              [appointments.update]
DELETE /appointments/{id}     - Delete appointment              [appointments.destroy]
```

### Payments Routes
```
GET    /payments              - List payment history            [payments.index]
GET    /appointments/{id}/payments/create - Show payment form   [payments.create]
POST   /appointments/{id}/payments - Store payment             [payments.store]
GET    /payments/{id}         - Show payment details            [payments.show]
```

### Authentication Routes (Laravel Breeze)
```
GET    /                      - Welcome page
GET    /dashboard             - Dashboard (protected)           [dashboard]
GET    /login                 - Login form
POST   /login                 - Process login
GET    /register              - Registration form
POST   /register              - Process registration
POST   /logout                - Logout
GET    /forgot-password       - Password reset request
POST   /forgot-password       - Send reset link
GET    /reset-password/{token} - Password reset form
POST   /reset-password        - Process password reset
GET    /verify-email          - Email verification
POST   /email/verification-notification - Resend verification
GET    /profile               - Profile edit form               [profile.edit]
PATCH  /profile               - Update profile                  [profile.update]
DELETE /profile               - Delete account                  [profile.destroy]
```

---

## Database Schema

### Users Table

```sql
CREATE TABLE users (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    INDEX idx_email (email),
    INDEX idx_email_verified_at (email_verified_at)
);
```

**Use**: User authentication and profile management

---

### Services Table

```sql
CREATE TABLE services (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL UNIQUE,
    price DECIMAL(8, 2) NOT NULL,
    duration INT NOT NULL,
    description TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    INDEX idx_name (name),
    INDEX idx_price (price)
);
```

**Constraints**:
- name: UNIQUE (prevents duplicate service names)
- price: DECIMAL(8, 2) for accurate currency
- duration: Minimum 15 minutes

**Relationships**:
- One Service → Many Appointments

---

### Appointments Table

```sql
CREATE TABLE appointments (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    service_id BIGINT UNSIGNED NOT NULL,
    date DATE NOT NULL,
    time TIME NOT NULL,
    status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
    notes TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE,
    UNIQUE KEY unique_appointment (service_id, date, time),
    INDEX idx_user_id (user_id),
    INDEX idx_service_id (service_id),
    INDEX idx_date (date),
    INDEX idx_status (status)
);
```

**Constraints**:
- UNIQUE(service_id, date, time): Prevents double booking
- date: Must be today or future (application validation)
- time: Must be future time on today (application validation)
- status: ENUM of 4 values

**Relationships**:
- Many Appointments → One User
- Many Appointments → One Service
- One Appointment → One Payment

---

### Payments Table

```sql
CREATE TABLE payments (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    appointment_id BIGINT UNSIGNED NOT NULL,
    amount DECIMAL(8, 2) NOT NULL,
    status ENUM('unpaid', 'paid', 'refunded') DEFAULT 'unpaid',
    payment_method VARCHAR(50) NULL,
    paid_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE CASCADE,
    UNIQUE KEY unique_payment (appointment_id),
    INDEX idx_status (status),
    INDEX idx_paid_at (paid_at)
);
```

**Constraints**:
- UNIQUE(appointment_id): One payment per appointment
- amount: DECIMAL(8, 2) for accurate currency
- status: ENUM of 3 values
- payment_method: Optional, recorded for reference

**Relationships**:
- Many Payments → One Appointment

---

## Data Relationships

### User → Appointments
```
1 User has Many Appointments
1:N Relationship
Foreign Key: appointments.user_id → users.id
```

### Service → Appointments
```
1 Service has Many Appointments
1:N Relationship
Foreign Key: appointments.service_id → services.id
```

### Appointment → Payment
```
1 Appointment has 1 Payment
1:1 Relationship
Foreign Key: payments.appointment_id → appointments.id
Unique Constraint: Only 1 payment per appointment
```

### Complete Relationship Graph
```
                    ┌─────────────┐
                    │    User     │
                    │ (customers) │
                    └──────┬──────┘
                           │
                           │ 1:N
                           │
                    ┌──────▼──────┐
            ┌───────┤ Appointment │
            │       └──────▲──────┘
            │              │
            │              │ M:1
            │              │
    ┌───────┤              │
    │       │       ┌──────┴──────┐
    │       │       │   Service   │
    │       │       │  (offerings)│
    │       │       └─────────────┘
    │       │ 1:1
    │       │
┌───▼───────┤
│  Payment  │
│(records)  │
└───────────┘
```

---

## Validation Rules Reference

### Service Validation
| Field | Rules | Example |
|-------|-------|---------|
| name | required, string, max:255, unique | "Manicure" |
| price | required, numeric, min:0 | 25.00 |
| duration | required, integer, min:15 | 30 |
| description | nullable, string, max:500 | "French manicure" |

### Appointment Validation
| Field | Rules | Example |
|-------|-------|---------|
| service_id | required, exists:services,id | 1 |
| date | required, date, after_or_equal:today | 2026-04-25 |
| time | required, date_format:H:i | 14:30 |
| notes | nullable, string, max:500 | "Prefer pink color" |

### Payment Validation
| Field | Rules | Example |
|-------|-------|---------|
| payment_method | nullable, string, max:50 | "credit_card" |
| amount | AUTO (from service.price) | 25.00 |

---

## Status Enumerations

### Appointment Status
- `pending`: Awaiting confirmation
- `confirmed`: Confirmed by user or staff
- `completed`: Appointment has occurred
- `cancelled`: Cancelled by user or system

### Payment Status
- `unpaid`: Not yet paid
- `paid`: Payment completed
- `refunded`: Payment refunded

---

## Query Examples

### Get User's Appointments
```php
$appointments = Appointment::where('user_id', $userId)
    ->with('service')
    ->orderBy('date', 'asc')
    ->orderBy('time', 'asc')
    ->get();
```

### Get Available Time Slots
```php
$bookedTimes = Appointment::where('service_id', $serviceId)
    ->where('date', $date)
    ->pluck('time')
    ->toArray();

$availableTimes = array_diff($allTimes, $bookedTimes);
```

### Get Payment History
```php
$payments = Payment::with('appointment.service', 'appointment.user')
    ->where('status', 'paid')
    ->orderBy('paid_at', 'desc')
    ->get();
```

### Check Duplicate Appointment
```php
$exists = Appointment::where('service_id', $serviceId)
    ->where('date', $date)
    ->where('time', $time)
    ->exists();
```

---

## Error Responses

### 404 Not Found
- Resource doesn't exist
- Example: Accessing non-existent appointment

### 403 Forbidden
- User not authorized to access resource
- Example: User A trying to access User B's appointment
- Policy enforces ownership

### 422 Unprocessable Entity
- Validation failed
- Returns validation errors
- Form data preserved with `old()` helper

### 500 Internal Server Error
- Caught exceptions
- Error logged to `storage/logs/laravel.log`
- User-friendly message displayed

---

## Database Indexing Strategy

### Primary Indexes (Automatic)
- id PRIMARY KEY on all tables

### Foreign Key Indexes (Automatic)
- user_id in appointments
- service_id in appointments
- appointment_id in payments

### Performance Indexes (Custom)
- email in users (for login)
- name in services (for search)
- status in appointments (for filtering)
- status in payments (for filtering)
- date in appointments (for date range queries)

---

## Cascade Actions

### On User Delete
- All associated appointments are deleted CASCADE
- All payments for deleted appointments are deleted CASCADE

### On Service Delete
- All associated appointments are deleted CASCADE
- All payments for deleted appointments are deleted CASCADE

### On Appointment Delete
- Associated payment is deleted CASCADE

---

## Transaction Safety

### Critical Operations
1. **Payment Processing**: Wrapped in try-catch
2. **Appointment Creation**: Duplicate check before insert
3. **Status Updates**: Authorization check before update
4. **Deletions**: Authorization check before delete

---

## Caching Opportunities

### Frequently Accessed (Could be cached)
- All services: `Cache::remember('services', 3600, ...)`
- User appointments: User-specific, not shared
- Payment history: User-specific, not shared

### Real-time Data (Not cached)
- Appointment availability (changes frequently)
- Payment status (must be current)
- User session (must be fresh)

---

## Performance Metrics

### Query Optimization
- Eager loading with `with()` prevents N+1
- Indexes on foreign keys for joins
- Proper use of `pluck()` for single column queries

### Load Time Targets
- Home page: < 200ms
- Service list: < 300ms
- Appointment list: < 400ms
- Payment history: < 400ms

---

## Backup Strategy

### Data to Backup
1. MySQL database (all tables)
2. User uploaded files (if any)
3. Configuration files (.env)

### Backup Schedule
- Daily automated backups recommended
- Weekly full backups
- Monthly archive backups

### Restore Procedure
```bash
# Backup
mysqldump -u root -p salon_db > backup.sql

# Restore
mysql -u root -p salon_db < backup.sql
```

---

## Scalability Considerations

### Database
- Add read replicas for heavy load
- Implement query caching
- Archive old appointments

### Application
- Implement API rate limiting
- Add queue jobs for heavy operations
- Use load balancer for multiple servers

### Storage
- Move file uploads to cloud storage
- Implement CDN for static assets
- Archive old logs

---

This reference guide provides complete API and database documentation for the Barrios Salon Management System.
