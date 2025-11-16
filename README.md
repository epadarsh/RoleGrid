## Laravel Backend: Referral System & Role-Based Admin Management

This project implements a comprehensive full-stack application featuring a referral tracking system and a role-based administration panel. The backend is built using Laravel (API-only), and the frontend is a modern React application utilizing Redux for state management.

## Setup Instructions

Follow these steps to get the application running on your local machine.

## Prerequisites

PHP (8.1+)

Composer

Node.js & npm (or Yarn)

A MySQL database instance

Laravel installed

## Step 1: Clone the Repository & Install Backend Dependencies

1. Clone the repository and navigate into the project directory as follows.

    git clone https://github.com/epadarsh/RoleGrid.git

    cd RoleGrid

## Install Composer dependencies:

    composer install

## Step 2: Configure the Environment (.env)

Copy the example environment file:

cp .env.example .env

Open the newly created .env file and configure your database connection (DB_DATABASE, DB_USERNAME, DB_PASSWORD).

Crucially, configure the application URL and Sanctum's token expiration (if desired):

# --- Backend Configuration ---

APP_URL=http://localhost:8000

# --- Frontend Development URL (CORS FIX) ---

SANCTUM_STATEFUL_DOMAINS=localhost:5173

# --- Database (Adjust as needed) ---

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=role_grid
DB_USERNAME=role_grid
DB_PASSWORD=

Generate the application key:

php artisan key:generate

## Step 3: Database Migration and Seeding

Run the migrations to create the users and products tables, and run the seeders to populate the database with the default Admin user, normal users, and products.

php artisan migrate:fresh --seed

## Step 4: Run the Backend

Start the Laravel development server. This should run on port 8000 by default.

php artisan serve

## Step 5: Run the Frontend (React Application)

Navigate into the frontend directory.

Install Node dependencies:

npm install

Start the React application (typically runs on port 5173 or 3000):

npm run dev # (Vite)

# OR

npm start # (Create React App)

## The application should now be accessible in your browser (e.g., http://localhost:5173).

#### Test Credentials

Use these accounts (created by the backend seeder) for immediate testing:

### Admin user

email - admin@rolegrid.com
password - Admin@12

### Normal user 1

email - deric@rolegrid.com
password - Deric@12

### Normal user 2

email - embape@rolegrid.com
password - Embape@12

### Normal user 3

email - david@rolegrid.com
password - David@12

---

🛠️ Key Technical Decisions

1. Backen- Laravel

Laravel (API-only): Chosen for its robustness, Eloquent ORM capabilities (for database interaction), and built-in features like Sanctum for secure API authentication. The API-only configuration ensures a clean separation of concerns from the frontend.

React: Selected for its component-based architecture, which simplifies the creation of complex, reusable UI elements (like the Admin DataTables, forms, and the AuthInitializer).

2. Authentication & Authorization

Laravel Sanctum: Used for token-based authentication. This allows the decoupled React frontend to authenticate once and receive a secure token, which is then sent with every subsequent API request via the Authorization: Bearer <token> header.

Role-Based Access Control (RBAC): Implemented using an enum column (role: 'admin', 'user') on the users table. Access to administration routes (/api/admin/\*) is strictly enforced using the custom AdminMiddleware, which checks the authenticated user's role.

3. State Management (Redux)

Redux Toolkit was chosen to handle the complex, global state required by the application:

authSlice: Manages the user's login status, token, user object, and the crucial isAuthReady flag to prevent race conditions during page reloads.

Centralized API Interceptor: Axios intercepts all API responses to dispatch global Toast Messages for instant, non-blocking success/failure feedback and to automatically handle token expiration/logout events.

4. Referral Logic

Observer Pattern: The UserObserver is used to automatically assign a unique referral_code when a new User model is created, ensuring the code is generated even during seeding.

Tracking: The observer also handles incrementing the referral_count of the referrer if a referrer_id is present in the new user's record, providing a performant, cached count for the user dashboard.
