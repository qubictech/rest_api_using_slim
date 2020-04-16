DATABASE NAME = my_app

CREATE TABLE users(
    id int NOT NULL AUTO_INCREMENT,
    email VARCHAR(200) NOT NULL,
    password text NOT NULL,
    name VARCHAR(500) NOT NULL,
    school VARCHAR(1000) NOT NULL,
    CONSTRAINT users_pk PRIMARY KEY (id)
    );

INSERT INTO users (email,password,name,school) VALUES(
    'mazharul15-8950@diu.edu.bd',
    'adminisboss',
    'Mazharul Sabbir',
    'Daffodil International School'
    );