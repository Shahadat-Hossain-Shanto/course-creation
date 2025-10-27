# 📘 Course Creation - README

## Setup :

Clone the repository:

bash: 
git clone https://github.com/Shahadat-Hossain-Shanto/course-creation.git

Install dependencies:  composer install

Copy environment file:  cp .env.example .env

Generate app key:  php artisan key:generate

Configure DB in .env

Run migrations:  php artisan migrate

Serve the app:  php artisan serve



Architecture :

This is a Laravel 12 project following MVC architecture

Models: Course, Module, Content for database interaction

Controllers: App\Http\Controllers\CourseController handles Course logic

Routes: Defined in routes/web.php

Views: Blade templates for frontend (Bootstrap 5 , JS, HTML, CSS)

Database: MySQL


