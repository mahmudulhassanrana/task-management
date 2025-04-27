# 📋 Task Management System

A full-stack **Task Management Application** built with:

- **Laravel 12** (Backend)
- **Vue 3** (Frontend)
- **Inertia.js** (Single Page Application bridge)
- **MySQL** (Database)
- **Bootstrap 5** (CSS Framework)
- **Vue Draggable** (Drag and Drop Tasks)
- **Vue3-Toastify** (Toast Notifications)

---

## ✨ Features

- 🔐 Authentication (Login, Register, Logout)
- 📝 Task Creation, Editing, Deleting
- 📂 Project Creation, Editing, Deleting
- 🏷 Assign Tasks to Projects
- 📋 Task Status (Pending, In Progress, Completed)
- 🎯 Task Prioritization (Low, Medium, High)
- 🔱 Drag and Drop Reordering (with priority auto-update)
- 📆 Due Date Management
- ⚡ Real-time updates with Inertia.js
- 🔥 Toast Notifications for every action
- 🎨 Bootstrap 5 Design with Modals
- 💜 Pagination for large task lists

---

## 🛠 Project Setup

### Step 1: Clone the Repository
```bash
git clone https://github.com/your-username/task-management.git
cd task-management
```

### Step 2: Install Backend (Laravel) Dependencies
```bash
composer install
```

### Step 3: Install Frontend (Vue) Dependencies
```bash
npm install
```

### Step 4: Set up Environment Variables
```bash
cp .env.example .env
```
Edit `.env` file and set your database connection:

- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`

### Step 5: Generate Application Key
```bash
php artisan key:generate
```

### Step 6: Run Database Migrations
```bash
php artisan migrate
```

### Step 7: Build Frontend Assets

For production:
```bash
npm run build
```

For development:
```bash
npm run dev
```

### Step 8: Start Laravel Server
```bash
php artisan serve / php artisan serve --host=localhost --port=8000
```
Visit the application at:  
[http://localhost:8000](http://localhost:8000)

---

## 📦 Important Commands Summary

| Purpose                         | Command               |
|:---------------------------------|:----------------------|
| Install Laravel Packages         | `composer install`    |
| Install Vue/NPM Packages         | `npm install`         |
| Generate .env Key                | `php artisan key:generate` |
| Run Migrations                   | `php artisan migrate` |
| Build Vue Frontend for Production| `npm run build`        |
| Start Development Frontend       | `npm run dev`          |
| Start Laravel Server             | `php artisan serve`    |

---

## 🏗 Project Structure

```bash
/app
  ├── Http
  │    ├── Controllers
  │         ├── Auth
  │         │    ├── LoginController.php
  │         │    ├── RegisterController.php
  │         │    └── LogoutController.php
  │         ├── TaskController.php
  │         └── ProjectController.php
  ├── Models
  │    ├── Task.php
  │    └── Project.php

/resources
  └── js
       └── Pages
            ├── Auth
            │    ├── Login.vue
            │    └── Register.vue
            ├── Tasks
            │    └── Index.vue
                 └── Projects.vue
                 

/routes
  └── web.php

/database
  ├── migrations
       ├──2024_xx_xx_create_projects_table.php
       └──2024_xx_xx_create_tasks_table.php
```

✅ Clean separation of:

- Controllers
- Models
- Vue Pages
- Routes
- Migrations

---

## 🚀 How It Works

- Register a new user or login.
- Create Projects to organize your tasks.
- Add Tasks under any project.
- Set priorities, due dates, and statuses for tasks.
- Drag tasks to reorder priority automatically.
- Mark tasks as completed when done.
- Manage Projects (edit, delete) anytime.

---

## ✅ Requirements

- PHP >= 8.2
- Node.js >= 18.x
- Composer >= 2.x
- MySQL Database
- Laravel 12 Framework
- NPM or Yarn

---

## 📜 Important Packages Used

| Package               | Purpose                  |
|:----------------------|:--------------------------|
| `@inertiajs/inertia`   | SPA Navigation            |
| `@inertiajs/vue3`      | Vue 3 + Inertia Bridge    |
| `vue3-toastify`        | Toast Notifications      |
| `vuedraggable@next`    | Drag and Drop Support    |
| `bootstrap`            | Styling and Layout        |
| `axios`                | HTTP Requests             |


---

## 🙏 Acknowledgements

- Laravel Documentation
- Vue.js Documentation
- Inertia.js Documentation
- Bootstrap 5
- Vue Draggable

---

