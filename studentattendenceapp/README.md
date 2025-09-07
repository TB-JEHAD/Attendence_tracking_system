# Student Attendance System

A Laravel-based web application for managing student attendance.

## Features

- Student management (add, edit, delete)
- Daily attendance tracking
- Monthly attendance reports
- Attendance statistics and analytics
- Responsive design with Bootstrap 5

## Requirements

- PHP >= 8.1
- Composer
- MySQL/MariaDB
- Node.js & NPM (for frontend assets)

## Installation

1. Clone the repository:
```bash
git clone <repository-url>
cd student-attendance-system
```

2. Install PHP dependencies:
```bash
composer install
```

3. Create environment file:
```bash
cp .env.example .env
```

4. Generate application key:
```bash
php artisan key:generate
```

5. Configure your database in `.env` file:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=student_attendance
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

6. Run database migrations:
```bash
php artisan migrate
```

7. Start the development server:
```bash
php artisan serve
```

8. Visit `http://localhost:8000` in your browser

## Usage

1. Add Students:
   - Navigate to Students section
   - Click "Add New Student"
   - Fill in student details
   - Click "Add Student"

2. Take Attendance:
   - Go to Attendance section
   - Select date
   - Mark attendance for each student
   - Add remarks if needed
   - Click "Save Attendance"

3. View Reports:
   - Go to Reports section
   - Select month
   - View attendance statistics
   - Export reports if needed

## License

MIT 