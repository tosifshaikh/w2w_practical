# W2W Tech - Laravel Developer Assignment

## Overview

Build a CSV upload and transaction management system using Laravel 12 with Vue.js/React with Inertia.js or API.

## Getting Started

### Prerequisites
- PHP 8.2+
- Composer
- Node.js 18+ and npm
- SQLite or MySQL or PostgreSQL (SQLite is included with PHP)

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/dahalros/developer_assignment.git
   cd developer_assignment
   ```

2. **Install dependencies**
  
3. **Environment setup**

4. **Database setup**


5. **Start development servers**
   ```bash
   composer dev
   ```

6. **Access the application**
   - Visit: `http://localhost:8000`
   - Register a new account to begin

## Assignment Tasks

Build a simple CSV upload and transaction listing system.

### Task 1: Database Design & Models
- [ ] Create `Customer` and `Transaction` models with migrations
- [ ] Design appropriate database schema based on the CSV structure
- [ ] Set up proper relationships between models
- [ ] Add appropriate database indexes and constraints

### Task 2: CSV Upload Functionality  
- [ ] Create file upload form with validation
- [ ] Process CSV data and store in database
- [ ] Handle duplicate customers appropriately
- [ ] Provide user feedback for success/error cases

### Task 3: Data Display & Analytics
- [ ] Create a dashboard showing key transaction metrics and analytics
- [ ] Build transactions listing page with pagination
- [ ] Implement search and filtering capabilities

### Task 4: Code Quality (Bonus)
- [ ] Implement proper validation and error handling
- [ ] Write tests for key functionality
- [ ] Follow Laravel best practices and conventions

## Sample CSV Data

Download the sample CSV file for testing: [sample-transactions.csv](./public/sample-transactions.csv)

## Technical Requirements

### Backend
- Laravel 12 with Eloquent ORM
- Customer and Transaction models with proper relationships
- Form Request validation
- RESTful controllers
- Proper error handling
- Database migrations with indexes and foreign key constraints

### Frontend  
- Vue.js 3 with either API or Inertia.js
- Tailwind CSS for styling
- Responsive design
- Form validation with feedback

## Development Commands

```bash
# Run tests
composer test

# Code formatting
php artisan pint
npm run lint

# Build assets
npm run build
```

## Evaluation Criteria

| Criteria | Weight |
|----------|--------|
| **Functionality** | 50% |
| **Code Quality** | 30% |
| **Database Design** | 20% |
| **Documentation** | 5% |
| **Testing** | 5% |

## Submission

Once you complete the assignment, push your code to GitHub and share the repository link with us.

---

**Good luck!** We look forward to reviewing your Laravel development skills.