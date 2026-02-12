-- Ajouter les colonnes user1_id et user2_id pour tracer les propriétaires au moment de l'échange
ALTER TABLE tt_echanges 
ADD COLUMN user1_id INT NOT NULL COMMENT 'Propriétaire de objet1 au moment de l''échange' AFTER objet2_id,
ADD COLUMN user2_id INT NOT NULL COMMENT 'Propriétaire de objet2 au moment de l''échange' AFTER user1_id;

-- Ajouter les clés étrangères
ALTER TABLE tt_echanges
ADD CONSTRAINT fk_echange_user1 FOREIGN KEY (user1_id) REFERENCES tt_users(id),
ADD CONSTRAINT fk_echange_user2 FOREIGN KEY (user2_id) REFERENCES tt_users(id);

-- Mettre à jour la vue v_echange_comp pour utiliser ces nouvelles colonnes
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
    
    -- Infos user1 (propriétaire de objet1 au moment de l'échange)
    u1.username AS user1_name,
    u1.pdp AS user1_pdp,
    
    -- Infos objet 2
    o2.libelle AS objet2_libelle,
    o2.description AS objet2_description,
    o2.prix_estimatif AS objet2_prix,
    (SELECT url FROM tt_photos_objet WHERE objet_id = o2.id LIMIT 1) AS objet2_photo,
    
    -- Infos user2 (propriétaire de objet2 au moment de l'échange)
    u2.username AS user2_name,
    u2.pdp AS user2_pdp,
    
    -- Statut
    s.libelle AS status_libelle
    
FROM tt_echanges e
INNER JOIN tt_objets o1 ON e.objet1_id = o1.id
INNER JOIN tt_users u1 ON e.user1_id = u1.id
INNER JOIN tt_objets o2 ON e.objet2_id = o2.id
INNER JOIN tt_users u2 ON e.user2_id = u2.id
INNER JOIN tt_status s ON e.status_id = s.id;
