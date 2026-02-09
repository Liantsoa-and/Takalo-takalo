-- Seed data for Takalo-takalo (fichier de test)
-- Ce fichier insère des enregistrements cohérents avec database/schema.sql

CREATE DATABASE IF NOT EXISTS takalo;
USE takalo;

-- Utilisateurs (ids explicites pour faciliter les références)
INSERT INTO tt_users (id, username, password, role) VALUES
(1, 'alice', 'pass123', 'user'),
(2, 'bob', 'pass456', 'user'),
(3, 'admin', 'adminpass', 'admin');

-- Catégories
INSERT INTO tt_categories (id, libelle) VALUES
(1, 'Meubles'),
(2, 'Livres'),
(3, 'Vêtements');

-- Statuts d'échange
INSERT INTO tt_status (id, libelle) VALUES
(1, 'en attente'),
(2, 'confirme'),
(3, 'refuser'),
(4, 'libre');

-- Objets
INSERT INTO tt_objets (id, libelle, description, category_id, user_id, prix_estimatif) VALUES
(1, 'Chaise vintage', 'Chaise en bois, bon état', 1, 1, 20.00),
(2, 'Roman SF', 'Roman de science-fiction, couverture souple', 2, 2, 5.00),
(3, 'Veste en cuir', 'Taille M, très bon état', 3, 1, 35.00),
(4, 'Table basse', 'Table en verre, quelques rayures', 1, 2, 50.00);

-- Photos d'objets
INSERT INTO tt_photos_objet (id, objet_id, url) VALUES
(1, 1, '/assets/img/chaise.jpg'),
(2, 2, '/assets/img/roman.jpg'),
(3, 3, '/assets/img/veste.jpg');

-- Échanges (références aux objets ci-dessus)
INSERT INTO tt_echanges (id, objet1_id, objet2_id, status_id) VALUES
(1, 1, 2, 2),
(2, 3, 4, 1);

-- FIN du fichier de seed
