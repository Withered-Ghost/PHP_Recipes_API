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

