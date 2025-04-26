Async Task API (Laravel + Database Queue)
This project is a RESTful API built with Laravel to handle computationally intensive tasks asynchronously using database-backed queues.

Clients can:
    - Submit a task
    - Check the status of a task
    - Retrieve the result once completed

Requirements:
    - PHP 8.1 or higher
    - Composer
    - MySQL (or any database Laravel supports)
    - Laravel 11 (or latest stable version)

Setup Instructions
1. Clone the repository
    git clone https://github.com/amirsaroye/laravel-async-task-api.git
    cd async-task-api

2. Install dependencies
    composer install

3. Copy .env.example to .env and set your database credentials:
    cp .env.example .env

4. set your database credentials in .env, Update the following in .env:
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=your_database_user
    DB_PASSWORD=your_database_password
    QUEUE_CONNECTION=databas

5. Run database migrations
    php artisan migrate

6. Run the queue worker
    php artisan queue:work

7. Serve the application (in new terminal)
    php artisan serve

The API will be available at http://127.0.0.1:8000

API Endpoints

Method	Endpoint	                Description                 Query Parameter
POST	http://127.0.0.1:8000/api/submit-task	        Submit a new async task     text (string, required)
GET	    http://127.0.0.1:8000/api/task-status/{task_id}	Check task status
GET	    http://127.0.0.1:8000/api/task-result/{task_id}	Retrieve task result

Example Requests

Submit Task
-----------
POST http://127.0.0.1:8000/api/submit-task
Content-Type: application/json

{
    "text": "Hello World"
}

successful response
-------------------
{
    "message": "Task submitted successfully.",
    "task_id": "228aebeb-d387-45b0-adc9-88f7676bc28e",
    "status": "pending"
}

=================================

Get Task Status
GET http://127.0.0.1:8000/api/task-status/8ad65e90-0e2d-4a8e-9a53-c9745378b8e9

successful response
-------------------
{
    "task_id": "8ad65e90-0e2d-4a8e-9a53-c9745378b8e9",
    "status": "failed"
}

================================

Get Task Result
GET http://127.0.0.1:8000/api/task-result/8b0561b1-20b8-4c06-a302-c1094384e54c

successful response
-------------------
{
    "task_id": "8b0561b1-20b8-4c06-a302-c1094384e54c",
    "result": "2 TSET_1.7868446166905E+125"
}

================================

Asynchronous Processing Details:
    - Tasks are dispatched to a database queue.
    - Worker processes tasks asynchronously in the background.
    - Each task has states: pending, processing, completed, or failed.
    - Random failures are simulated to mimic real-world instability.
    - Processing involves computing a mathematical series and reversing+uppercasing the input.

Important Note: Ensure php artisan queue:work is always running in background, otherwise tasks won't be processed.