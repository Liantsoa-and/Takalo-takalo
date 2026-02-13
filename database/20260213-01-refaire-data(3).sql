-- =====================================================
-- BASE DE DONNÉES TAKALO - VERSION COMPLÈTE V3
-- Copier-coller ce fichier complet dans phpMyAdmin
-- Date: 13/02/2026
-- =====================================================
-- Inclut:
--   - Toutes les tables (tt_users, tt_categories, tt_objets, tt_photos_objet, tt_status, tt_echanges, membres)
--   - Toutes les vues (v_users_roles, v_objets_cat, v_objets_public, v_echange_comp)
--   - Toutes les données cohérentes avec les images existantes
-- =====================================================

DROP DATABASE IF EXISTS takalo;
CREATE DATABASE takalo CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE takalo;

-- =====================================================
-- TABLES
-- =====================================================

-- Table des utilisateurs
CREATE TABLE tt_users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL,
    pdp VARCHAR(255) DEFAULT 'default.png',
    bio TEXT DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table des catégories
CREATE TABLE tt_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    libelle VARCHAR(255) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table des objets
CREATE TABLE tt_objets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    libelle VARCHAR(255) NOT NULL,
    description TEXT,
    category_id INT,
    user_id INT,
    prix_estimatif DECIMAL(10, 2),
    FOREIGN KEY (category_id) REFERENCES tt_categories(id),
    FOREIGN KEY (user_id) REFERENCES tt_users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table des photos d'objets
CREATE TABLE tt_photos_objet (
    id INT PRIMARY KEY AUTO_INCREMENT,
    objet_id INT,
    url VARCHAR(255) NOT NULL,
    FOREIGN KEY (objet_id) REFERENCES tt_objets(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table des statuts
CREATE TABLE tt_status (
    id INT PRIMARY KEY AUTO_INCREMENT,
    libelle VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table des échanges (avec user1_id et user2_id pour tracer les propriétaires)
CREATE TABLE tt_echanges (
    id INT PRIMARY KEY AUTO_INCREMENT,
    objet1_id INT,
    objet2_id INT,
    user1_id INT NOT NULL COMMENT 'Propriétaire de objet1 au moment de l''échange',
    user2_id INT NOT NULL COMMENT 'Propriétaire de objet2 au moment de l''échange',
    date_echange TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status_id INT,
    FOREIGN KEY (objet1_id) REFERENCES tt_objets(id),
    FOREIGN KEY (objet2_id) REFERENCES tt_objets(id),
    FOREIGN KEY (user1_id) REFERENCES tt_users(id),
    FOREIGN KEY (user2_id) REFERENCES tt_users(id),
    FOREIGN KEY (status_id) REFERENCES tt_status(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table des membres du projet (Membership)
CREATE TABLE membres (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(150) NOT NULL,
    prenom VARCHAR(150) NOT NULL,
    etu VARCHAR(50) NOT NULL,
    photo VARCHAR(255) DEFAULT NULL,
    bio TEXT DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- VUES
-- =====================================================

-- Vue des rôles utilisateurs
CREATE OR REPLACE VIEW v_users_roles AS
SELECT
    id,
    username,
    role,
    COUNT(*) AS nb
FROM tt_users
GROUP BY role;

-- Vue des objets avec catégories
CREATE OR REPLACE VIEW v_objets_cat AS
SELECT
    o.id AS obj_id,
    o.libelle AS obj_libelle,
    o.description AS obj_description,
    o.prix_estimatif AS obj_prix_estimatif,
    c.libelle AS cat_libelle,
    o.user_id AS user_id,
    c.id AS cat_id
FROM tt_objets o
JOIN tt_categories c ON c.id = o.category_id;

-- Vue pour la liste publique des objets
CREATE OR REPLACE VIEW v_objets_public AS
SELECT
    o.id,
    o.libelle,
    o.description,
    o.prix_estimatif,
    o.category_id,
    o.user_id,
    c.libelle AS category_name,
    u.username AS owner_name,
    u.pdp AS owner_pdp,
    (SELECT url FROM tt_photos_objet WHERE objet_id = o.id LIMIT 1) AS main_photo,
    (SELECT COUNT(*) FROM tt_photos_objet WHERE objet_id = o.id) AS photos_count
FROM tt_objets o
INNER JOIN tt_users u ON o.user_id = u.id
INNER JOIN tt_categories c ON o.category_id = c.id;

-- Vue complète des échanges
CREATE OR REPLACE VIEW v_echange_comp AS
SELECT 
    e.id,
    e.objet1_id,
    e.objet2_id,
    e.user1_id,
    e.user2_id,
    e.status_id,
    e.date_echange,
    
    -- Infos objet 1
    o1.libelle AS objet1_libelle,
    o1.description AS objet1_description,
    o1.prix_estimatif AS objet1_prix,
    (SELECT url FROM tt_photos_objet WHERE objet_id = o1.id LIMIT 1) AS objet1_photo,

    -- Aliases backward-compatible pour les templates existants
    o1.libelle AS objet1,
    
    -- Infos user1 (propriétaire de objet1 au moment de l'échange)
    u1.username AS user1_name,
    u1.pdp AS user1_pdp,

    u1.username AS user1,
    
    -- Infos objet 2
    o2.libelle AS objet2_libelle,
    o2.description AS objet2_description,
    o2.prix_estimatif AS objet2_prix,
    (SELECT url FROM tt_photos_objet WHERE objet_id = o2.id LIMIT 1) AS objet2_photo,

    -- Alias backward-compatible
    o2.libelle AS objet2,
    
    -- Infos user2 (propriétaire de objet2 au moment de l'échange)
    u2.username AS user2_name,
    u2.pdp AS user2_pdp,

    u2.username AS user2,
    
    -- Statut
    s.libelle AS status_libelle
    , s.libelle AS status
    
FROM tt_echanges e
INNER JOIN tt_objets o1 ON e.objet1_id = o1.id
INNER JOIN tt_users u1 ON e.user1_id = u1.id
INNER JOIN tt_objets o2 ON e.objet2_id = o2.id
INNER JOIN tt_users u2 ON e.user2_id = u2.id
INNER JOIN tt_status s ON e.status_id = s.id;

-- =====================================================
-- DONNÉES: UTILISATEURS
-- 2 admins (id 1-2) et 3 utilisateurs (id 3-5)
-- Photos: admin1.png, admin2.png, user1.png, user2.png, user3.png
-- =====================================================

INSERT INTO tt_users (id, username, password, role, pdp, bio) VALUES
(1, 'admin1', 'adminpass1', 'admin', 'admin1.png', 'Administrateur principal du système.'),
(2, 'admin2', 'adminpass2', 'admin', 'admin2.png', 'Administrateur secondaire.'),
(3, 'user1', 'userpass1', 'user', 'user1.png', 'Passionné de technologie et de livres.'),
(4, 'user2', 'userpass2', 'user', 'user2.png', 'Amateur de sports et de musique.'),
(5, 'user3', 'userpass3', 'user', 'user3.png', 'Collectionneur d\'art et de bijoux.');

-- =====================================================
-- DONNÉES: CATÉGORIES
-- =====================================================

INSERT INTO tt_categories (id, libelle) VALUES
(1, 'Électronique'),
(2, 'Vêtements'),
(3, 'Livres'),
(4, 'Mobilier'),
(5, 'Sports et Loisirs'),
(6, 'Bijoux et Accessoires'),
(7, 'Véhicules'),
(8, 'Instruments de Musique'),
(9, 'Art et Décoration'),
(10, 'Jouets et Jeux');

-- =====================================================
-- DONNÉES: STATUTS
-- =====================================================

INSERT INTO tt_status (id, libelle) VALUES
(1, 'en attente'),
(2, 'confirme'),
(3, 'refuse');

-- =====================================================
-- DONNÉES: OBJETS
-- État INITIAL avant tout échange:
--   Objets 1-5   : user_id = 3 (user1)
--   Objets 6-11  : user_id = 4 (user2)
--   Objets 12-21 : user_id = 5 (user3)
-- =====================================================

INSERT INTO tt_objets (id, libelle, description, category_id, user_id, prix_estimatif) VALUES
-- Objets de user1 (id=3) : objets 1-5
(1, 'Ordinateur portable', 'Ordinateur portable en bon état, 8GB RAM, SSD 256GB.', 1, 3, 500000),
(2, 'Téléphone smartphone', 'Smartphone Android récent, écran 6 pouces, caméra 48MP.', 1, 3, 300000),
(3, 'Veste en cuir', 'Veste en cuir noir, taille M, légèrement usée.', 2, 3, 80000),
(4, 'Roman policier', 'Livre de suspense, édition poche, bon état.', 3, 3, 10000),
(5, 'Table basse', 'Table basse en bois, dimensions 100x60cm.', 4, 3, 150000),

-- Objets de user2 (id=4) : objets 6-11
(6, 'Raquette de tennis', 'Raquette de tennis Wilson, modèle professionnel.', 5, 4, 120000),
(7, 'Collier en argent', 'Collier en argent avec pendentif, longueur 45cm.', 6, 4, 50000),
(8, 'Vélo électrique', 'Vélo électrique pliable, autonomie 50km.', 7, 4, 800000),
(9, 'Guitare acoustique', 'Guitare acoustique Yamaha, caisse en bois.', 8, 4, 200000),
(10, 'Tableau abstrait', 'Tableau d\'art abstrait, huile sur toile, 50x70cm.', 9, 4, 250000),
(11, 'Poupée Barbie', 'Poupée Barbie vintage, avec accessoires.', 10, 4, 30000),

-- Objets de user3 (id=5) : objets 12-21
(12, 'Casque audio', 'Casque audio sans fil, réduction de bruit.', 1, 5, 100000),
(13, 'Robe d\'été', 'Robe légère en coton, taille S, couleur bleue.', 2, 5, 40000),
(14, 'Encyclopédie', 'Ensemble d\'encyclopédie, 10 volumes.', 3, 5, 70000),
(15, 'Chaise de bureau', 'Chaise ergonomique avec roulettes.', 4, 5, 180000),
(16, 'Ballon de football', 'Ballon de football officiel, taille 5.', 5, 5, 25000),
(17, 'Bague en or', 'Bague en or 18 carats, sertie de diamants.', 6, 5, 300000),
(18, 'Moto scooter', 'Scooter électrique, vitesse max 25km/h.', 7, 5, 400000),
(19, 'Piano numérique', 'Piano numérique 88 touches, avec casque.', 8, 5, 600000),
(20, 'Vase décoratif', 'Vase en céramique, hauteur 30cm.', 9, 5, 45000),
(21, 'Jeu de société', 'Jeu de société Monopoly, édition classique.', 10, 5, 20000);

-- =====================================================
-- DONNÉES: PHOTOS D'OBJETS
-- Cohérent avec les fichiers dans public/uploads/photos/
-- =====================================================

INSERT INTO tt_photos_objet (objet_id, url) VALUES
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

-- =====================================================
-- DONNÉES: ÉCHANGES
-- =====================================================

-- ÉCHANGES ACCEPTÉS (status_id = 2)
-- Ces échanges ont eu lieu et ont changé les propriétaires

-- Échange 1 : user1(3) propose objet 1 contre objet 6 de user2(4) - il y a 90 jours
INSERT INTO tt_echanges (objet1_id, objet2_id, user1_id, user2_id, status_id, date_echange) VALUES
(1, 6, 3, 4, 2, DATE_SUB(NOW(), INTERVAL 90 DAY));

-- Échange 2 : user1(3) propose objet 2 contre objet 12 de user3(5) - il y a 60 jours
INSERT INTO tt_echanges (objet1_id, objet2_id, user1_id, user2_id, status_id, date_echange) VALUES
(2, 12, 3, 5, 2, DATE_SUB(NOW(), INTERVAL 60 DAY));

-- Échange 3 : user2(4) propose objet 7 contre objet 13 de user3(5) - il y a 45 jours
INSERT INTO tt_echanges (objet1_id, objet2_id, user1_id, user2_id, status_id, date_echange) VALUES
(7, 13, 4, 5, 2, DATE_SUB(NOW(), INTERVAL 45 DAY));

-- Échange 4 : user3(5) propose objet 14 contre objet 3 de user1(3) - il y a 30 jours
INSERT INTO tt_echanges (objet1_id, objet2_id, user1_id, user2_id, status_id, date_echange) VALUES
(14, 3, 5, 3, 2, DATE_SUB(NOW(), INTERVAL 30 DAY));

-- Échange 5 : user2(4) propose objet 8 contre objet 4 de user1(3) - il y a 20 jours
INSERT INTO tt_echanges (objet1_id, objet2_id, user1_id, user2_id, status_id, date_echange) VALUES
(8, 4, 4, 3, 2, DATE_SUB(NOW(), INTERVAL 20 DAY));

-- Échange 6 : user3(5) propose objet 15 contre objet 9 de user2(4) - il y a 15 jours
INSERT INTO tt_echanges (objet1_id, objet2_id, user1_id, user2_id, status_id, date_echange) VALUES
(15, 9, 5, 4, 2, DATE_SUB(NOW(), INTERVAL 15 DAY));

-- Échange 7 : user1(3) propose objet 5 contre objet 16 de user3(5) - il y a 10 jours
INSERT INTO tt_echanges (objet1_id, objet2_id, user1_id, user2_id, status_id, date_echange) VALUES
(5, 16, 3, 5, 2, DATE_SUB(NOW(), INTERVAL 10 DAY));

-- Échange 8 : user2(4) propose objet 10 contre objet 17 de user3(5) - il y a 5 jours
INSERT INTO tt_echanges (objet1_id, objet2_id, user1_id, user2_id, status_id, date_echange) VALUES
(10, 17, 4, 5, 2, DATE_SUB(NOW(), INTERVAL 5 DAY));

-- ÉCHANGES REFUSÉS (status_id = 3)
INSERT INTO tt_echanges (objet1_id, objet2_id, user1_id, user2_id, status_id, date_echange) VALUES
(11, 18, 4, 5, 3, DATE_SUB(NOW(), INTERVAL 40 DAY)),
(19, 6, 5, 3, 3, DATE_SUB(NOW(), INTERVAL 35 DAY)),
(20, 3, 5, 3, 3, DATE_SUB(NOW(), INTERVAL 25 DAY)),
(4, 21, 4, 5, 3, DATE_SUB(NOW(), INTERVAL 18 DAY)),
(12, 11, 3, 4, 3, DATE_SUB(NOW(), INTERVAL 12 DAY));

-- ÉCHANGES EN ATTENTE (status_id = 1)
INSERT INTO tt_echanges (objet1_id, objet2_id, user1_id, user2_id, status_id, date_echange) VALUES
(11, 19, 4, 5, 1, DATE_SUB(NOW(), INTERVAL 3 DAY)),
(20, 8, 5, 3, 1, DATE_SUB(NOW(), INTERVAL 2 DAY)),
(6, 21, 3, 5, 1, DATE_SUB(NOW(), INTERVAL 1 DAY)),
(18, 14, 5, 3, 1, NOW());

-- =====================================================
-- MISE À JOUR DES PROPRIÉTAIRES APRÈS ÉCHANGES ACCEPTÉS
-- =====================================================

-- Échange 1 : objet 1 → user 4, objet 6 → user 3
UPDATE tt_objets SET user_id = 4 WHERE id = 1;
UPDATE tt_objets SET user_id = 3 WHERE id = 6;

-- Échange 2 : objet 2 → user 5, objet 12 → user 3
UPDATE tt_objets SET user_id = 5 WHERE id = 2;
UPDATE tt_objets SET user_id = 3 WHERE id = 12;

-- Échange 3 : objet 7 → user 5, objet 13 → user 4
UPDATE tt_objets SET user_id = 5 WHERE id = 7;
UPDATE tt_objets SET user_id = 4 WHERE id = 13;

-- Échange 4 : objet 14 → user 3, objet 3 → user 5
UPDATE tt_objets SET user_id = 3 WHERE id = 14;
UPDATE tt_objets SET user_id = 5 WHERE id = 3;

-- Échange 5 : objet 8 → user 3, objet 4 → user 4
UPDATE tt_objets SET user_id = 3 WHERE id = 8;
UPDATE tt_objets SET user_id = 4 WHERE id = 4;

-- Échange 6 : objet 15 → user 4, objet 9 → user 5
UPDATE tt_objets SET user_id = 4 WHERE id = 15;
UPDATE tt_objets SET user_id = 5 WHERE id = 9;

-- Échange 7 : objet 5 → user 5, objet 16 → user 3
UPDATE tt_objets SET user_id = 5 WHERE id = 5;
UPDATE tt_objets SET user_id = 3 WHERE id = 16;

-- Échange 8 : objet 10 → user 5, objet 17 → user 4
UPDATE tt_objets SET user_id = 5 WHERE id = 10;
UPDATE tt_objets SET user_id = 4 WHERE id = 17;

-- =====================================================
-- DONNÉES: MEMBRES DU PROJET
-- Photos: H.jpg, L.jpg, J.jpg dans public/assets/images/membre/
-- =====================================================

INSERT INTO membres (nom, prenom, etu, photo, bio) VALUES
('RAKOTOARIVONY', 'Harena Natolotra Sarobidy', 'ETU-3940', 'H.jpg', 'Membre du projet - Back-office et gestion des catégories.'),
('FENOHERILIANTSOA', 'Ny Aina Andréane', 'ETU-4199', 'L.jpg', 'Membre du projet - Front-office et interface utilisateur.'),
('FANEVA', 'Jedidia', 'ETU-4042', 'J.jpg', 'Membre du projet - Statistiques, recherche et historique.');

-- =====================================================
-- RÉSUMÉ FINAL DES PROPRIÉTAIRES
-- =====================================================
-- user1 (id=3) possède: 6, 12, 14, 8, 16 (5 objets)
-- user2 (id=4) possède: 1, 13, 4, 15, 17, 11 (6 objets)
-- user3 (id=5) possède: 2, 7, 3, 9, 5, 10, 18, 19, 20, 21 (10 objets)
-- =====================================================

-- Vérification (décommenter pour tester)
-- SELECT user_id, COUNT(*) as nb_objets FROM tt_objets GROUP BY user_id;
-- SELECT * FROM v_objets_public;
-- SELECT * FROM v_echange_comp;
