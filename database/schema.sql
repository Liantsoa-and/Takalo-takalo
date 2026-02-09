CREATE DATABASE takalo

USE takalo;

CREATE TABLE tt_users(
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL
);

CREATE TABLE tt_categories(
    id INT PRIMARY KEY AUTO_INCREMENT,
    libelle VARCHAR(255) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE tt_objets(
    id INT PRIMARY KEY AUTO_INCREMENT,
    libelle VARCHAR(255) NOT NULL,
    description TEXT,
    category_id INT,
    user_id INT,
    prix_estimatif DECIMAL(10, 2),
    FOREIGN KEY (category_id) REFERENCES tt_categories(id),
    FOREIGN KEY (user_id) REFERENCES tt_users(id)
);

CREATE TABLE tt_photos_objet(
    id INT PRIMARY KEY AUTO_INCREMENT,
    objet_id INT,
    url VARCHAR(255) NOT NULL,
    FOREIGN KEY (objet_id) REFERENCES tt_objets(id)
);

CREATE TABLE tt_status(
    id INT PRIMARY KEY AUTO_INCREMENT,
    libelle TEXT
);

CREATE TABLE tt_echanges(
    id INT PRIMARY KEY AUTO_INCREMENT,
    objet1_id INT,
    objet2_id INT,
    date_echange TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status_id INT ,
    FOREIGN KEY (objet1_id) REFERENCES tt_objets(id),
    FOREIGN KEY (objet2_id) REFERENCES tt_objets(id),
    FOREIGN KEY (status_id) REFERENCES tt_status(id)
);
