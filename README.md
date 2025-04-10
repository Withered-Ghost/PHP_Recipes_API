create table recipes (
  uid INT PRIMARY KEY,
  name varchar(255) NOT NULL UNIQUE,
  prep_time INT NOT NULL DEFAULT 60,
  difficulty INT NOT NULL DEFAULT 1,
  veg BOOLEAN NOT NULL DEFAULT 1,
  rating DECIMAL(2, 1) NOT NULL DEFAULT 0.0,
  rating_count INT NOT NULL DEFAULT 0,

  CHECK (length(name)>0),
  CHECK (prep_time>=1),
  CHECK (1<=difficulty AND difficulty<=3),
  CHECK (0.0<=rating AND rating<=5.0)
);

create table users (
  email varchar(255) PRIMARY KEY,
  password varchar(255) NOT NULL,
  root BOOLEAN NOT NULL DEFAULT 0
);

+--------------+--------------+------+-----+---------+-------+
| Field        | Type         | Null | Key | Default | Extra |
+--------------+--------------+------+-----+---------+-------+
| uid          | int          | NO   | PRI | NULL    |       |
| name         | varchar(255) | NO   | UNI | NULL    |       |
| prep_time    | int          | NO   |     | 60      |       |
| difficulty   | int          | NO   |     | 1       |       |
| veg          | tinyint(1)   | NO   |     | 1       |       |
| rating       | decimal(2,1) | NO   |     | 0.0     |       |
| rating_count | int          | NO   |     | 0       |       |
+--------------+--------------+------+-----+---------+-------+

| Name   | Method      | URL                    | Protected |
| ---    | ---         | ---                    | ---       |
| List   | `GET`       | `/recipes`             | 0         |
| Create | `POST`      | `/recipes`             | 1         |
| Get    | `GET`       | `/recipes/{id}`        | 0         |
| Update | `PUT/PATCH` | `/recipes/{id}`        | 1         |
| Rate   | `POST`      | `/recipes/{id}/rating` | 0         |
| Delete | `DELETE`    | `/recipes/{id}`        | 1         |