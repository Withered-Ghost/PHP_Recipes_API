# Recipe Management REST API in PHP

A simple REST API for managing and rating recipes, built with PHP and MySQL.

## Tech Stack

**Code:** PHP

**Server:** Nginx

**Database:** MySQL

**Authentication:** JSON Web Tokens

## Installation

Install the source code with:

```bash
  git clone https://github.com/Withered-Ghost/PHP_Recipes_API.git
  cd PHP_Recipes_API
```

To deploy the API, make sure Docker Engine is installed and running. Then run the following command in the root directory of the project.

```bash
docker-compose up -d
```

To stop all the containers:

```bash
docker-compose down
```

Access the API at:

```
http://<Host_IP>:8080/
```

## API Reference

This API accepts request payload only in JSON format.

Header: `Content-Type: application/json; charset=UTF-8`

Requests to endpoints marked as `Protected: YES` must also have the `Authorization` header.

Header: `Authorization: Bearer <token>`

#### Error response

```
{
    "status": int,
    "message": string
}
```

#### Login to get the JWT

```
POST /recipes/login
Protected: NO

Payload:
{
    "email": string,
    "password": string
}

Output:
{
    "status": int,
    "message": string,
    "token": string
}
```

#### Get all recipes

```
GET /recipes
Protected: NO

Output:
{
    "status": int,
    "message": string,
    "data": [
        {
            "uid": int,
            "name": string,
            "prep_time": int,
            "difficulty": int,
            "veg": bool,
            "rating": float,
            "rating_count": int
        }
    ]
}
```

#### Get a single recipe

```
GET /recipes/{uid}
Protected: NO

Output:
{
    "status": int,
    "message": string,
    "data": {
        "uid": int,
        "name": string,
        "prep_time": int,
        "difficulty": int,
        "veg": bool,
        "rating": float,
        "rating_count": int
    }
}
```

#### Create a recipe

```
POST /recipes
Protected: YES

Payload:
{
    "name": string,
    "prep_time": int | null,
    "difficulty": int | null,
    "veg": bool | null
}

Output:
{
    "status": int,
    "message": string,
    "affected_rows": int
}
```

#### Rate a recipe

```
POST /recipes/{uid}/{rating}
Protected: NO

Payload:
{}

Output:
{
    "status": int,
    "message": string,
    "affected_rows": int
}
```

#### Update a recipe

```
PUT /recipes/{uid}
Protected: YES

Payload:
{
    "name": string,
    "prep_time": int,
    "difficulty": int,
    "veg": bool
}

Output:
{
    "status": int,
    "message": string,
    "affected_rows": int
}
```

#### Delete a recipe

```
DELETE /recipes/{uid}
Protected: YES

Payload:
{}

Output:
{
    "status": int,
    "message": string,
    "affected_rows": int
}
```

#### Recipe Schema

```sql
create table recipes (
    uid INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL UNIQUE,
    prep_time INT NOT NULL DEFAULT 60,
    difficulty INT NOT NULL DEFAULT 1,
    veg BOOLEAN NOT NULL DEFAULT 1,
    rating DECIMAL(2, 1) NOT NULL DEFAULT 0.0,
    rating_count INT NOT NULL DEFAULT 0,

    CHECK (length(name) > 0),
    CHECK (prep_time >= 1),
    CHECK (1 <= difficulty AND difficulty <= 3),
    CHECK (0.0 <= rating AND rating <= 5.0)
);
```

#### User Schema

```sql
create table users (
  email VARCHAR(255) PRIMARY KEY,
  password VARCHAR(255) NOT NULL,
  root BOOLEAN NOT NULL DEFAULT 0
);
```
