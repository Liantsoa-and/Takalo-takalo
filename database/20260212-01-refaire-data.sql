DROP DATABASE IF EXISTS takalo;

CREATE DATABASE takalo;

USE takalo;

CREATE TABLE
    tt_users (
        id INT PRIMARY KEY AUTO_INCREMENT,
        username VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role VARCHAR(50) NOT NULL
    );

CREATE TABLE
    tt_categories (
        id INT PRIMARY KEY AUTO_INCREMENT,
        libelle VARCHAR(255) NOT NULL UNIQUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

CREATE TABLE
    tt_objets (
        id INT PRIMARY KEY AUTO_INCREMENT,
        libelle VARCHAR(255) NOT NULL,
        description TEXT,
        category_id INT,
        user_id INT,
        prix_estimatif DECIMAL(10, 2),
        FOREIGN KEY (category_id) REFERENCES tt_categories (id),
        FOREIGN KEY (user_id) REFERENCES tt_users (id)
    );

CREATE TABLE
    tt_photos_objet (
        id INT PRIMARY KEY AUTO_INCREMENT,
        objet_id INT,
        url VARCHAR(255) NOT NULL,
        FOREIGN KEY (objet_id) REFERENCES tt_objets (id)
    );

CREATE TABLE
    tt_status (id INT PRIMARY KEY AUTO_INCREMENT, libelle TEXT);

CREATE TABLE
    tt_echanges (
        id INT PRIMARY KEY AUTO_INCREMENT,
        objet1_id INT,
        objet2_id INT,
        date_echange TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        status_id INT,
        FOREIGN KEY (objet1_id) REFERENCES tt_objets (id),
        FOREIGN KEY (objet2_id) REFERENCES tt_objets (id),
        FOREIGN KEY (status_id) REFERENCES tt_status (id)
    );

CREATE
OR REPLACE VIEW v_users_roles AS
SELECT
    id,
    username,
    role,
    count(*) nb
FROM
    tt_users
GROUP BY
    role;

ALTER TABLE tt_users
ADD COLUMN pdp VARCHAR(255) DEFAULT 'default.png';

CREATE
OR REPLACE VIEW v_objets_cat AS
SELECT
    o.id AS obj_id,
    o.libelle AS obj_libelle,
    o.description AS obj_description,
    o.prix_estimatif AS obj_prix_estimatif,
    c.libelle AS cat_libelle,
    o.user_id AS user_id,
    c.id AS cat_id
FROM
    tt_objets o
    JOIN tt_categories c ON c.id = o.category_id;

-- Vue pour la liste publique des objets avec toutes les informations nécessaires
CREATE
OR REPLACE VIEW v_objets_public AS
SELECT
    o.id,
    o.libelle,
    o.description,
    o.prix_estimatif,
    o.category_id,
    o.user_id,
    c.libelle as category_name,
    u.username as owner_name,
    (
        SELECT
            url
        FROM
            tt_photos_objet
        WHERE
            objet_id = o.id
        LIMIT
            1
    ) as main_photo,
    (
        SELECT
            COUNT(*)
        FROM
            tt_photos_objet
        WHERE
            objet_id = o.id
    ) as photos_count
FROM
    tt_objets o
    INNER JOIN tt_users u ON o.user_id = u.id
    INNER JOIN tt_categories c ON o.category_id = c.id;

-- ...existing code...
-- Insertion des utilisateurs (2 admins et 3 utilisateurs)
INSERT INTO
    tt_users (username, password, role)
VALUES
    ('admin1', 'adminpass1', 'admin'),
    ('admin2', 'adminpass2', 'admin'),
    ('user1', 'userpass1', 'user'),
    ('user2', 'userpass2', 'user'),
    ('user3', 'userpass3', 'user');

-- Insertion des catégories
INSERT INTO
    tt_categories (libelle)
VALUES
    ('Électronique'),
    ('Vêtements'),
    ('Livres'),
    ('Mobilier'),
    ('Sports et Loisirs'),
    ('Bijoux et Accessoires'),
    ('Véhicules'),
    ('Instruments de Musique'),
    ('Art et Décoration'),
    ('Jouets et Jeux');

-- Insertion des objets pour les utilisateurs (pas d'objets pour les admins)
INSERT INTO
    tt_objets (
        libelle,
        description,
        category_id,
        user_id,
        prix_estimatif
    )
VALUES
    (
        'Ordinateur portable',
        'Ordinateur portable en bon état, 8GB RAM, SSD 256GB.',
        1,
        3,
        500.00
    ),
    (
        'Téléphone smartphone',
        'Smartphone Android récent, écran 6 pouces, caméra 48MP.',
        1,
        3,
        300.00
    ),
    (
        'Veste en cuir',
        'Veste en cuir noir, taille M, légèrement usée.',
        2,
        3,
        80.00
    ),
    (
        'Roman policier',
        'Livre de suspense, édition poche, bon état.',
        3,
        3,
        10.00
    ),
    (
        'Table basse',
        'Table basse en bois, dimensions 100x60cm.',
        4,
        3,
        150.00
    ),
    (
        'Raquette de tennis',
        'Raquette de tennis Wilson, modèle professionnel.',
        5,
        4,
        120.00
    ),
    (
        'Collier en argent',
        'Collier en argent avec pendentif, longueur 45cm.',
        6,
        4,
        50.00
    ),
    (
        'Vélo électrique',
        'Vélo électrique pliable, autonomie 50km.',
        7,
        4,
        800.00
    ),
    (
        'Guitare acoustique',
        'Guitare acoustique Yamaha, caisse en bois.',
        8,
        4,
        200.00
    ),
    (
        'Tableau abstrait',
        'Tableau d\'art abstrait, huile sur toile, 50x70cm.',
        9,
        4,
        250.00
    ),
    (
        'Poupée Barbie',
        'Poupée Barbie vintage, avec accessoires.',
        10,
        4,
        30.00
    ),
    (
        'Casque audio',
        'Casque audio sans fil, réduction de bruit.',
        1,
        5,
        100.00
    ),
    (
        'Robe d\'été',
        'Robe légère en coton, taille S, couleur bleue.',
        2,
        5,
        40.00
    ),
    (
        'Encyclopédie',
        'Ensemble d\'encyclopédie, 10 volumes.',
        3,
        5,
        70.00
    ),
    (
        'Chaise de bureau',
        'Chaise ergonomique avec roulettes.',
        4,
        5,
        180.00
    ),
    (
        'Ballon de football',
        'Ballon de football officiel, taille 5.',
        5,
        5,
        25.00
    ),
    (
        'Bague en or',
        'Bague en or 18 carats, sertie de diamants.',
        6,
        5,
        300.00
    ),
    (
        'Moto scooter',
        'Scooter électrique, vitesse max 25km/h.',
        7,
        5,
        400.00
    ),
    (
        'Piano numérique',
        'Piano numérique 88 touches, avec casque.',
        8,
        5,
        600.00
    ),
    (
        'Vase décoratif',
        'Vase en céramique, hauteur 30cm.',
        9,
        5,
        45.00
    ),
    (
        'Jeu de société',
        'Jeu de société Monopoly, édition classique.',
        10,
        5,
        20.00
    );

-- Insertion des photos pour les objets (chemins locaux)
INSERT INTO
    tt_photos_objet (objet_id, url)
VALUES
    (1, 'photo_1_1.jpg'),
    (1, 'photo_1_2.jpg'),
    (2, 'photo_2_1.jpg'),
    (3, 'photo_3_1.jpg'),
    (4, 'photo_4_1.jpg'),
    (5, 'photo_5_1.jpg'),
    (6, 'photo_6_1.jpg'),
    (7, 'photo_7_1.jpg'),
    (8, 'photo_8_1.jpg'),
    (9, 'photo_9_1.jpg'),
    (10, 'photo_10_1.jpg'),
    (11, 'photo_11_1.jpg'),
    (12, 'photo_12_1.jpg'),
    (13, 'photo_13_1.jpg'),
    (14, 'photo_14_1.jpg'),
    (15, 'photo_15_1.jpg'),
    (16, 'photo_16_1.jpg'),
    (17, 'photo_17_1.jpg'),
    (18, 'photo_18_1.jpg'),
    (19, 'photo_19_1.jpg'),
    (20, 'photo_20_1.jpg'),
    (21, 'photo_21_1.jpg');

-- Insertion des statuts (pour les échanges futurs)
INSERT INTO
    tt_status (libelle)
VALUES
    ('En attente'),
    ('Accepté'),
    ('Refusé'),
    ('Terminé');