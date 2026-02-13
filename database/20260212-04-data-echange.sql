-- =====================================================
-- Données d'échanges cohérentes
-- =====================================================
-- IMPORTANT: Ce script suppose que vous avez exécuté 20260212-01-refaire-data.sql
--            ET 20260212-04-alter-echanges.sql (ajout user1_id, user2_id)
-- 
-- État initial des objets (avant échanges) :
--   Objets 1-5   : user_id = 3 (user1)
--   Objets 6-11  : user_id = 4 (user2)
--   Objets 12-21 : user_id = 5 (user3)
--
-- Après les échanges acceptés, l'état final sera mis à jour.
-- =====================================================


-- Vider les échanges existants
DELETE FROM tt_echanges;

-- =====================================================
-- ÉCHANGES ACCEPTÉS (status_id = 2) - Ces échanges ont eu lieu
-- Les objets ont changé de propriétaire
-- =====================================================

-- Échange 1 : user1(3) propose objet 1 contre objet 6 de user2(4) - il y a 3 mois
-- Résultat : objet 1 → user2(4), objet 6 → user1(3)
INSERT INTO tt_echanges (objet1_id, objet2_id, user1_id, user2_id, status_id, date_echange) VALUES
(1, 6, 3, 4, 2, DATE_SUB(NOW(), INTERVAL 90 DAY));

-- Échange 2 : user1(3) propose objet 2 contre objet 12 de user3(5) - il y a 2 mois
-- Résultat : objet 2 → user3(5), objet 12 → user1(3)
INSERT INTO tt_echanges (objet1_id, objet2_id, user1_id, user2_id, status_id, date_echange) VALUES
(2, 12, 3, 5, 2, DATE_SUB(NOW(), INTERVAL 60 DAY));

-- Échange 3 : user2(4) propose objet 7 contre objet 13 de user3(5) - il y a 45 jours
-- Résultat : objet 7 → user3(5), objet 13 → user2(4)
INSERT INTO tt_echanges (objet1_id, objet2_id, user1_id, user2_id, status_id, date_echange) VALUES
(7, 13, 4, 5, 2, DATE_SUB(NOW(), INTERVAL 45 DAY));

-- Échange 4 : user3(5) propose objet 14 contre objet 3 de user1(3) - il y a 30 jours
-- Résultat : objet 14 → user1(3), objet 3 → user3(5)
INSERT INTO tt_echanges (objet1_id, objet2_id, user1_id, user2_id, status_id, date_echange) VALUES
(14, 3, 5, 3, 2, DATE_SUB(NOW(), INTERVAL 30 DAY));

-- Échange 5 : user2(4) propose objet 8 contre objet 4 de user1(3) - il y a 20 jours
-- Résultat : objet 8 → user1(3), objet 4 → user2(4)
INSERT INTO tt_echanges (objet1_id, objet2_id, user1_id, user2_id, status_id, date_echange) VALUES
(8, 4, 4, 3, 2, DATE_SUB(NOW(), INTERVAL 20 DAY));

-- Échange 6 : user3(5) propose objet 15 contre objet 9 de user2(4) - il y a 15 jours
-- Résultat : objet 15 → user2(4), objet 9 → user3(5)
INSERT INTO tt_echanges (objet1_id, objet2_id, user1_id, user2_id, status_id, date_echange) VALUES
(15, 9, 5, 4, 2, DATE_SUB(NOW(), INTERVAL 15 DAY));

-- Échange 7 : user1(3) propose objet 5 contre objet 16 de user3(5) - il y a 10 jours
-- Résultat : objet 5 → user3(5), objet 16 → user1(3)
INSERT INTO tt_echanges (objet1_id, objet2_id, user1_id, user2_id, status_id, date_echange) VALUES
(5, 16, 3, 5, 2, DATE_SUB(NOW(), INTERVAL 10 DAY));

-- Échange 8 : user2(4) propose objet 10 contre objet 17 de user3(5) - il y a 5 jours
-- Résultat : objet 10 → user3(5), objet 17 → user2(4)
INSERT INTO tt_echanges (objet1_id, objet2_id, user1_id, user2_id, status_id, date_echange) VALUES
(10, 17, 4, 5, 2, DATE_SUB(NOW(), INTERVAL 5 DAY));

-- =====================================================
-- ÉCHANGES REFUSÉS (status_id = 3) - Propositions rejetées
-- Note: les user IDs sont ceux des propriétaires au moment de la proposition
-- =====================================================

INSERT INTO tt_echanges (objet1_id, objet2_id, user1_id, user2_id, status_id, date_echange) VALUES
(11, 18, 4, 5, 3, DATE_SUB(NOW(), INTERVAL 40 DAY)),  -- user2(4) proposait objet 11 contre objet 18 de user3(5)
(19, 6, 5, 3, 3, DATE_SUB(NOW(), INTERVAL 35 DAY)),   -- user3(5) proposait objet 19 contre objet 6 (déjà chez user1(3) après échange 1)
(20, 3, 5, 3, 3, DATE_SUB(NOW(), INTERVAL 25 DAY)),   -- user3(5) proposait objet 20 contre objet 3 de user1(3)
(4, 21, 4, 5, 3, DATE_SUB(NOW(), INTERVAL 18 DAY)),   -- user2(4) proposait objet 4 (reçu échange 5) contre objet 21 de user3(5)
(12, 11, 3, 4, 3, DATE_SUB(NOW(), INTERVAL 12 DAY));  -- user1(3) proposait objet 12 (reçu échange 2) contre objet 11 de user2(4)

-- =====================================================
-- ÉCHANGES EN ATTENTE (status_id = 1) - Propositions en cours
-- =====================================================

INSERT INTO tt_echanges (objet1_id, objet2_id, user1_id, user2_id, status_id, date_echange) VALUES
(11, 19, 4, 5, 1, DATE_SUB(NOW(), INTERVAL 3 DAY)),   -- user2(4) propose objet 11 contre objet 19 de user3(5)
(20, 8, 5, 3, 1, DATE_SUB(NOW(), INTERVAL 2 DAY)),    -- user3(5) propose objet 20 contre objet 8 (maintenant chez user1(3))
(6, 21, 3, 5, 1, DATE_SUB(NOW(), INTERVAL 1 DAY)),    -- user1(3) propose objet 6 (reçu échange 1) contre objet 21 de user3(5)
(18, 14, 5, 3, 1, NOW());                              -- user3(5) propose objet 18 contre objet 14 (maintenant chez user1(3))

-- =====================================================
-- MISE À JOUR DES PROPRIÉTAIRES SELON LES ÉCHANGES ACCEPTÉS
-- L'état final doit correspondre aux échanges ci-dessus
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
-- RÉSUMÉ DE L'ÉTAT FINAL DES OBJETS
-- =====================================================
-- User 3 (user1) possède maintenant : 6, 12, 14, 8, 16 (5 objets)
-- User 4 (user2) possède maintenant : 1, 13, 4, 15, 17, 11 (6 objets)
-- User 5 (user3) possède maintenant : 2, 7, 3, 9, 5, 10, 18, 19, 20, 21 (10 objets)
-- =====================================================

-- Vérification (à exécuter pour confirmer)
-- SELECT user_id, COUNT(*) as nb_objets FROM tt_objets GROUP BY user_id;
