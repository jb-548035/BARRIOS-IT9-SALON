# Barrios Salon Management System - User Guide

## Overview
Barrios Salon Management System is a modern, sleek web application built with Laravel Framework for managing salon/nail services, customer appointments, and payment processing. The system features a beautiful modern design with optimized user experience and comprehensive error handling.

## System Features

### 1. **Authentication & Authorization**
- Secure login system using email and password
- Email verification for new user accounts
- Password reset functionality
- Session management with automatic timeout
- Role-based authorization for data access

### 2. **Service Management**
- **Add Services**: Create new salon services with name, price, duration, and description
- **View Services**: Browse all available services in a clean table format
- **Edit Services**: Update service details anytime
- **Delete Services**: Remove services from the system
- **Service Details**: View complete service information
- **Validation**: Ensures all required fields are filled and data is valid

### 3. **Appointment/Booking Management**
- **Book Appointments**: Schedule new appointments for available services
- **Date/Time Selection**: Choose future dates and times (cannot book past appointments)
- **Duplicate Prevention**: System prevents double booking of same service at same time
- **View Appointments**: See all personal appointments in chronological order
- **Edit Appointments**: Modify appointment details
- **Cancel Appointments**: Remove unwanted appointments
- **Status Tracking**: Track appointment status (Pending, Confirmed, Completed, Cancelled)
- **Past Appointment Protection**: Cannot book appointments for dates/times that have already passed

### 4. **Payment Management**
- **Process Payments**: Record and process payments for appointments
- **Payment Status**: Track payment status (Paid, Unpaid, Refunded)
- **Payment History**: View complete payment transaction history
- **Payment Methods**: Support for Cash, Credit Card, Debit Card, and Check
- **Automatic Amount Calculation**: System automatically calculates amount based on service price
- **Payment Validation**: Ensures payment data integrity

### 5. **Error Handling**
- **Form Validation**: Comprehensive validation for all inputs
- **Error Messages**: Clear, user-friendly error notifications
- **Duplicate Prevention**: Prevents duplicate appointments at same time slot
- **Authorization Checks**: Users can only access their own data
- **Exception Handling**: Graceful error handling with informative messages
- **Session Management**: Proper session handling and cleanup

## Color Scheme
Modern and sleek design using Barrios Salon's custom color palette:
- **Primary Pink**: #ff5bbc (Main accent color)
- **Light Pink**: #fdd1e9 (Background highlights)
- **Light Gray**: #e8ecef (Light backgrounds)
- **Medium Gray**: #8d9aaf (Secondary accents)
- **Dark Gray**: #2b2c43 (Text and headers)

## System Navigation

### Dashboard
Welcome page with quick links to main sections:
- Services
- Appointments
- Payments

### Services Section
1. **Index Page**: View all services in a table
   - Displays: Name, Price, Duration, Description
   - Actions: View, Edit, Delete

2. **Create Page**: Add a new service
   - Required: Name, Price, Duration
   - Optional: Description
   - Minimum duration: 15 minutes

3. **Edit Page**: Modify service details
   - Update all service information
   - Validates unique service names

4. **Show Page**: View full service details
   - Complete service information
   - Links to edit or delete

### Appointments Section
1. **Index Page**: Your appointments
   - Filters by logged-in user
   - Displays: Service, Date, Time, Status
   - Actions: View, Edit, Pay, Delete
   - Only shows future appointments

2. **Create Page**: Book new appointment
   - Select service
   - Choose date (today or future)
   - Choose time (future times only on today)
   - Add optional notes
   - Prevents duplicate bookings

3. **Edit Page**: Modify appointment
   - Change service
   - Update date/time
   - Change status
   - Update notes
   - Prevents past date/time selection

4. **Show Page**: Appointment details
   - Customer name
   - Service details
   - Appointment time
   - Price information
   - Links to edit or process payment

### Payments Section
1. **Index Page**: Payment history
   - All payments associated with your appointments
   - Displays: Service, Amount, Status, Paid Date
   - Actions: View payment details

2. **Create Page**: Process payment
   - Shows appointment summary
   - Service details and amount due
   - Payment method selection
   - Confirmation before processing

3. **Show Page**: Payment details
   - Payment ID and amount
   - Payment status
   - Payment method
   - Appointment information
   - Links to appointment and payment history

## Validation Rules

### Service Validation
- **Name**: Required, max 255 characters, must be unique
- **Price**: Required, must be numeric, cannot be negative
- **Duration**: Required, must be integer, minimum 15 minutes
- **Description**: Optional, max 500 characters

### Appointment Validation
- **Service**: Required, must exist in database
- **Date**: Required, must be today or future date
- **Time**: Required, must be in HH:MM format
- **Time Constraint**: Cannot book times that have passed today
- **Duplicate Check**: Service cannot have two bookings at same time
- **Notes**: Optional, max 500 characters

### Payment Validation
- **Appointment**: Must exist and have no existing payment
- **Amount**: Automatically set to service price
- **Payment Method**: Optional, max 50 characters

## Security Features

1. **Authentication**: Users must logged in to access system
2. **Authorization**: Users can only view/edit their own appointments and payments
3. **CSRF Protection**: All forms protected with CSRF tokens
4. **SQL Injection Prevention**: Uses Laravel's query builder and Eloquent ORM
5. **Password Hashing**: All passwords securely hashed using bcrypt
6. **Email Verification**: New accounts require email verification
7. **Session Handling**: Secure session management with timeout

## Error Prevention

### Duplicate Appointments
- System uses unique constraint on (service_id, date, time)
- Validates at application level before saving
- Shows clear error message if attempting duplicate booking

### Past Date/Time Prevention
- Appointment booking form prevents selecting past dates
- JavaScript client-side validation for immediate feedback
- Server-side validation ensures data integrity
- Cannot create/edit appointments in the past

### Authorization
- Users can only view their own appointments and payments
- Policies enforce user ownership
- 403 errors for unauthorized access attempts

### Data Validation
- Client-side HTML5 validation (immediate feedback)
- Server-side Laravel validation (security)
- Custom error messages for all validation rules
- Form data preservation on validation errors

## User Experience Features

1. **Responsive Design**: Works on desktop, tablet, and mobile
2. **Dark Mode Support**: System supports dark theme preference
3. **Smooth Animations**: Fade-in and transition effects
4. **Error Highlighting**: Invalid fields clearly highlighted in form
5. **Success Notifications**: Confirmation messages after actions
6. **Navigation Labels**: Clear breadcrumbs and navigation
7. **Empty States**: Helpful messages when no data exists
8. **Quick Actions**: Direct links between related pages

## Database Schema

### Users Table
- id, name, email, email_verified_at, password, remember_token, created_at, updated_at

### Services Table
- id, name, price, duration, description, created_at, updated_at

### Appointments Table
- id, user_id (FK), service_id (FK), date, time, status, notes, created_at, updated_at
- Unique constraint: (service_id, date, time)

### Payments Table
- id, appointment_id (FK), amount, status, payment_method, paid_at, created_at, updated_at

## Getting Started

1. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

2. **Setup Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Configure Database**
   - Update .env with MySQL credentials
   - Database name: salon_db

4. **Run Migrations**
   ```bash
   php artisan migrate
   ```

5. **Build Assets**
   ```bash
   npm run build
   ```

6. **Start Server**
   ```bash
   php artisan serve
   ```

7. **Access Application**
   - Visit: http://localhost:8000
   - Register new account or login

## Support

For issues or questions:
1. Check validation error messages
2. Verify form inputs meet requirements
3. Ensure you're logged in
4. Clear browser cache if issues persist
5. Check database connection if server errors occur

## Technical Stack

- **Framework**: Laravel 11
- **Frontend**: Blade Templates, Tailwind CSS
- **Database**: MySQL
- **Authentication**: Laravel Auth
- **Styling**: Custom CSS + Tailwind CSS
- **Validation**: Laravel Form Requests
- **Authorization**: Laravel Gates/Policies

---
**Barrios Salon Management System** - Modern, Sleek, and Functional
