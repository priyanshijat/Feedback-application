# User Feedback System - PHP Docker Application
#hello
## 📌 Project Overviewi

This is a simple PHP web application that demonstrates:
- **User Interface**: Clean, modern form for collecting user feedback
- **Database Operations**: Create, Read operations with MySQL
- **Form Validation**: Input validation and error handling
- **Security**: SQL injection protection using prepared statements, XSS protection with htmlspecialchars()

## 🎯 Features

1. **User Feedback Form**
   - Collects Name, Email, and Message from users
   - Real-time validation
   - Success/Error messages

2. **Feedback Display**
   - Shows recently submitted feedback (latest 10)
   - Displays submission date and time
   - Beautiful, responsive UI

3. **Database Integration**
   - MySQL database storage
   - Automatic timestamp tracking
   - Indexed for performance

## 📁 Project Structure

```
php-docker-app/
├── index.php              # Main application file
├── config/
│   └── config.php         # Database configuration
├── init-db.php            # Database initialization script
├── Dockerfile             # Docker configuration
├── .env                   # Environment variables (local testing)
├── docker-compose.yml     # Docker compose for local testing
└── README.md              # This file
```

## 🗄️ Database Schema

### feedback table
```sql
CREATE TABLE feedback (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_created_at (created_at)
);
```

## 🔐 Environment Variables

The application uses environment variables for database configuration:

```
DB_HOST      - MySQL server hostname
DB_USER      - MySQL username
DB_PASSWORD  - MySQL password
DB_NAME      - Database name
DB_PORT      - MySQL port (default: 3306)
```

## 🚀 Local Testing (Before Dockerization)

### Requirements
- PHP 7.4+
- MySQL 5.7+
- Web server (Apache/Nginx)

### Steps
1. Create database: `mysql -u root -p < setup.sql`
2. Update `config/config.php` with your database credentials
3. Run on local server: `php -S localhost:8000`
4. Access: `http://localhost:8000`

## 🐳 Docker Setup

See the separate Dockerfile and Docker documentation for containerization steps.

## 📊 Application Flow

1. User visits the application
2. Enters feedback form (name, email, message)
3. Form is submitted via POST request
4. Backend validates the input
5. If valid, data is inserted into MySQL database
6. Success message is displayed
7. Latest feedback entries are fetched and displayed
8. Page refreshes showing new feedback

## 🔒 Security Features

- **Prepared Statements**: Prevents SQL injection attacks
- **Input Validation**: Email format validation
- **HTML Escaping**: XSS protection using htmlspecialchars()
- **Error Handling**: Graceful error messages without exposing sensitive info

## 📝 Code Explanation (For Interview)

### Key Concepts:

1. **Database Connection** (config.php)
   - Uses mysqli for database operations
   - Environment-based configuration for flexibility
   - UTF-8 charset support

2. **Form Handling** (index.php)
   - POST method for form submission
   - Input sanitization with trim()
   - Basic validation checks

3. **Database Operations**
   - INSERT: Adds new feedback to database
   - SELECT: Retrieves recent feedback entries

4. **Display & Presentation**
   - HTML/CSS for beautiful UI
   - Responsive design
   - Real-time feedback display

## 🎓 Interview Talking Points

1. Explain the form submission flow
2. Discuss prepared statements and SQL injection prevention
3. Explain the database schema design
4. Discuss environment variable usage for configuration
5. Explain the UI/UX design decisions
6. Discuss how this will be Dockerized
7. Explain AWS integration strategy

## 📚 Technologies Used

- **PHP**: Server-side logic and database operations
- **MySQL**: Data persistence
- **HTML/CSS**: User interface
- **JavaScript**: Form validation (can be added)
- **Docker**: Containerization
- **AWS RDS**: Managed database service
- **AWS EC2**: Computing instance
- **AWS ECR**: Docker image registry

---

**Ready to move to Step 2: Dockerization?**
