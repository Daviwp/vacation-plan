# Vacation Plan API Documentation

This document provides an overview of the available routes in the Vacation Plan API, explaining the purpose of each and how to use them.

## Running the Project
To set up and run the Vacation Plan API project using Docker, follow these steps:

1. ## Clone the Repository
First, clone the repository to your local machine:
    
    git clone https://github.com/Daviwp/vacation-plan.git
    cd vacation-plan

2. ## Build and Start Docker Containers
Use Docker Compose to build and start the containers:

    docker-compose up --build

This command will build the Docker images defined in your docker-compose.yml file and start the containers for MariaDB, Nginx, and PHP. The application should be accessible at http://localhost:8000

3. ## Access the Application
Once the Docker containers are running, you can access the application by navigating to http://localhost:8000 in your web browser or through API testing tools like Postman.

4. ## Access the PHP container

    ```bash
    docker-compose exec php bash

5. ## Inside the PHP container, run Laravel setup commands

    ```bash
    composer install
    php artisan migrate

- composer install installs the PHP dependencies for the Laravel application.
- php artisan migrate runs the database migrations to set up the necessary database tables.


6. ## Additional Commands
After starting the Docker containers, you might need to run additional commands to set up the Laravel application. Open a new terminal window and execute the following commands inside the Docker container:

## Authentication

Authentication for the API is handled via Bearer Token. There is no separate login route in this API. To access protected routes, you need to include the token in the `Authorization` header of your requests.

### Bearer Token

**Description:**  
Bearer Token is used for authenticating requests. You need to include this token in the `Authorization` header of your API requests.

**Example Header:**
Authorization: Bearer YOUR_ACCESS_TOKEN

---

## Vacation Plans

### `GET /api/vacation-plans`

**Description:**  
Retrieves a list of all vacation plans.

**Authentication:**  
Requires authentication via JWT token.

**Response:**  
Returns an array of vacation plans.

---

### `POST /api/vacation-plans`

**Description:**  
Creates a new vacation plan.

**Authentication:**  
Requires authentication via JWT token.

**Parameters:**
- `title`: Title of the vacation plan.
- `description`: Detailed description of the plan.
- `date`: Date of the plan in `YYYY-MM-DD` format.
- `location`: Location of the vacation plan.
- `participants`: Comma-separated list of participants.

**Response:**  
Returns the created vacation plan.

---

### `GET /api/vacation-plans/{id}`

**Description:**  
Retrieves the details of a specific vacation plan.

**Authentication:**  
Requires authentication via JWT token.

**Parameters:**
- `id`: The ID of the vacation plan to retrieve.

**Response:**  
Returns the details of the specified vacation plan.

---

### `PUT /api/vacation-plans/{id}`

**Description:**  
Updates an existing vacation plan.

**Authentication:**  
Requires authentication via JWT token.

**Parameters:**
- `id`: The ID of the vacation plan to update.
- Other optional parameters as per the plan (e.g., `title`, `description`, etc.)

**Response:**  
Returns the updated vacation plan.

---

### `DELETE /api/vacation-plans/{id}`

**Description:**  
Deletes a specific vacation plan.

**Authentication:**  
Requires authentication via JWT token.

**Parameters:**
- `id`: The ID of the vacation plan to delete.

**Response:**  
Returns an empty response with a `204 No Content` status.

---

### `GET /api/vacation-plans/{id}/pdf`

**Description:**  
Generates a PDF of the specified vacation plan.

**Authentication:**  
Requires authentication via JWT token.

**Parameters:**
- `id`: The ID of the vacation plan for which the PDF will be generated.

**Response:**  
Downloads a PDF file with the details of the vacation plan.

---

## Fallback Route

### `Fallback`

**Description:**  
Returns an error when a route or HTTP method is not supported by the API.

**Response:**  
Returns an error message with a `404 Not Found` status.
