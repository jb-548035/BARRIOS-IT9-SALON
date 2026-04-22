# Barrios Salon - Development Guide

## Project Structure

```
barrios_salon/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── ServiceController.php
│   │   │   ├── AppointmentController.php
│   │   │   ├── PaymentController.php
│   │   │   └── ProfileController.php
│   │   ├── Requests/
│   │   │   ├── StoreServiceRequest.php
│   │   │   └── StoreAppointmentRequest.php
│   │   └── Middleware/
│   ├── Models/
│   │   ├── Service.php
│   │   ├── Appointment.php
│   │   ├── Payment.php
│   │   └── User.php
│   ├── Policies/
│   │   └── AppointmentPolicy.php
│   ├── Providers/
│   │   └── AppServiceProvider.php
├── resources/
│   ├── css/
│   │   └── app.css
│   ├── js/
│   │   ├── app.js
│   │   └── bootstrap.js
│   └── views/
│       ├── dashboard.blade.php
│       ├── layouts/
│       │   ├── app.blade.php
│       │   ├── guest.blade.php
│       │   └── navigation.blade.php
│       ├── services/
│       ├── appointments/
│       ├── payments/
│       ├── auth/
│       ├── profile/
│       └── components/
├── routes/
│   ├── web.php
│   ├── auth.php
│   └── console.php
├── database/
│   ├── migrations/
│   ├── factories/
│   └── seeders/
├── public/
│   ├── css/
│   │   └── styles.css
│   └── build/
├── config/
├── storage/
└── tests/
```

## Architecture Overview

### MVC Pattern Implementation

**Models** (`app/Models/`)
- Service: Represents salon services
- Appointment: Represents customer bookings
- Payment: Represents payment transactions
- User: Represents customers/users

**Controllers** (`app/Http/Controllers/`)
- ServiceController: Handles all service CRUD operations
- AppointmentController: Manages appointment lifecycle
- PaymentController: Processes and tracks payments
- ProfileController: User profile management

**Views** (`resources/views/`)
- Blade template files for UI rendering
- Component-based structure for reusability
- Responsive design using Tailwind CSS

**Routes** (`routes/web.php`)
- RESTful routes for resources
- Auth middleware protection
- Named routes for easy linking

### Request Validation

**Form Requests** (`app/Http/Requests/`)
- StoreServiceRequest: Service validation
- StoreAppointmentRequest: Appointment validation
- Centralized validation logic
- Custom error messages

### Authorization

**Policies** (`app/Policies/`)
- AppointmentPolicy: Controls access to appointments
- Users can only modify their own appointments
- Authorization checks in controller methods

## Database Schema

### Relationships

```
User (1) ──── (M) Appointment
User (1) ──── (M) Password Reset Token

Service (1) ──── (M) Appointment

Appointment (1) ──── (1) Payment
```

### Key Migrations

1. **Users Table** (Laravel default)
   - Authentication and user management

2. **Services Table**
   - Salon service definitions
   - Columns: id, name, price, duration, description, timestamps

3. **Appointments Table**
   - Customer bookings
   - Columns: id, user_id, service_id, date, time, status, notes, timestamps
   - Unique constraint: (service_id, date, time)

4. **Payments Table**
   - Payment records
   - Columns: id, appointment_id, amount, status, payment_method, paid_at, timestamps

## Key Features Implementation

### 1. Duplicate Appointment Prevention

**Database Level**
```php
$table->unique(['service_id', 'date', 'time']);
```

**Application Level** (AppointmentController.php)
```php
$existing = Appointment::where('service_id', $request->service_id)
    ->where('date', $request->date)
    ->where('time', $request->time)
    ->exists();

if ($existing) {
    return back()->with('error', 'This time slot is already booked.');
}
```

### 2. Past Date Prevention

**Validation Rules**
```php
'date' => 'required|date|after_or_equal:today',
'time' => 'required|date_format:H:i',
```

**Code Validation** (AppointmentController.php)
```php
$dateTime = Carbon::createFromFormat('Y-m-d H:i', $request->date . ' ' . $request->time);

if ($dateTime->isPast()) {
    return back()->with('error', 'Cannot book appointments in the past.');
}
```

**JavaScript Validation** (appointments/create.blade.php)
```javascript
timeInput.addEventListener('change', function() {
    const selectedDateTime = new Date(selectedDate + 'T' + this.value);
    if (selectedDateTime <= now) {
        this.setCustomValidity('Time must be in the future');
    }
});
```

### 3. Authorization & Authorization

**Policies** (AppointmentPolicy.php)
```php
public function view(User $user, Appointment $appointment): bool
{
    return $user->id === $appointment->user_id;
}
```

**Controller Usage**
```php
public function show(Appointment $appointment): View
{
    $this->authorize('view', $appointment);
    return view('appointments.show', compact('appointment'));
}
```

### 4. Error Handling

**Try-Catch Blocks**
```php
try {
    Service::create($request->validated());
    return redirect()->route('services.index')->with('success', 'Created successfully.');
} catch (\Exception $e) {
    return redirect()->back()->withErrors(['error' => 'Failed to create: ' . $e->getMessage()]);
}
```

**Validation Errors Display**
```blade
@if($errors->any())
    <div class="alert-danger">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </div>
@endif
```

## Styling System

### Color Variables
```css
:root {
    --primary: #ff5bbc;
    --light-pink: #fdd1e9;
    --light-gray: #e8ecef;
    --medium-gray: #8d9aaf;
    --dark: #2b2c43;
}
```

### Tailwind Extensions
See `resources/css/app.css` for custom component classes:
- `.btn-primary`, `.btn-secondary`, etc.
- `.badge-success`, `.badge-danger`, etc.
- `.card`, `.input-field`, `.table-header`

### Responsive Breakpoints
- Mobile: < 768px
- Tablet: 768px - 1024px
- Desktop: > 1024px

## Common Tasks

### Adding a New Service Feature

1. **Create Migration**
   ```bash
   php artisan make:migration add_feature_to_services
   ```

2. **Update Model** (`Service.php`)
   ```php
   protected $fillable = [..., 'new_field'];
   ```

3. **Update Controller** (`ServiceController.php`)
   ```php
   public function store(StoreServiceRequest $request) {
       // Handle new field
   }
   ```

4. **Update Form Request** (`StoreServiceRequest.php`)
   ```php
   'new_field' => 'validation_rules',
   ```

5. **Update Views**
   - services/create.blade.php
   - services/edit.blade.php
   - services/show.blade.php

### Adding Validation Rule

1. **Update Form Request**
   ```php
   public function rules(): array
   {
       return [
           'field' => 'required|rule1|rule2',
       ];
   }
   ```

2. **Add Custom Message** (optional)
   ```php
   public function messages(): array
   {
       return [
           'field.rule' => 'Custom error message',
       ];
   }
   ```

### Creating a New Report

1. **Add Route**
   ```php
   Route::get('reports/appointments', [ReportController::class, 'appointments']);
   ```

2. **Create Controller Method**
   ```php
   public function appointments() {
       $appointments = Appointment::with('service', 'user')->get();
       return view('reports.appointments', compact('appointments'));
   }
   ```

3. **Create View**
   ```blade
   <x-app-layout>
       <!-- Report content -->
   </x-app-layout>
   ```

## Testing

Run tests with:
```bash
php artisan test
```

Key test areas:
- Authentication
- Authorization
- Validation rules
- Duplicate prevention
- Relationships

## Performance Optimization

### Query Optimization
Use eager loading to prevent N+1 queries:
```php
$appointments = Appointment::with('service', 'user')->get();
```

### Caching
For frequently accessed services:
```php
$services = Cache::remember('services', 3600, function () {
    return Service::all();
});
```

### Database Indexing
Ensure proper indexes on:
- user_id (appointments, payments)
- service_id (appointments)
- Unique constraint on (service_id, date, time)

## Deployment Checklist

- [ ] Update `.env` with production values
- [ ] Set `APP_DEBUG=false`
- [ ] Run `php artisan optimize`
- [ ] Run migrations on production
- [ ] Set proper file permissions
- [ ] Configure backup strategy
- [ ] Setup error logging
- [ ] Configure HTTPS/SSL
- [ ] Test all features
- [ ] Setup monitoring

## Troubleshooting

### Common Issues

**Migrations fail**
```bash
php artisan migrate:reset
php artisan migrate
```

**Routes not working**
```bash
php artisan route:cache
php artisan route:clear
```

**Assets not loading**
```bash
npm run build
php artisan cache:clear
```

**Database connection error**
- Verify .env database credentials
- Ensure MySQL is running
- Check database exists

## Contributing Guidelines

1. Follow PSR-12 coding standards
2. Write meaningful commit messages
3. Test changes before committing
4. Document new features
5. Update tests for new code
6. Keep functions small and focused

## Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Tailwind CSS](https://tailwindcss.com)
- [Eloquent ORM](https://laravel.com/docs/eloquent)
- [Blade Templates](https://laravel.com/docs/blade)
