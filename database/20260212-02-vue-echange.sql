-- Vue complète des échanges avec toutes les informations nécessaires
CREATE OR REPLACE VIEW v_echange_comp AS
SELECT 
    e.id,
    e.objet1_id,
    e.objet2_id,
    e.status_id,
    e.date_echange,
    
    -- Infos objet 1
    o1.libelle AS objet1_libelle,
    o1.description AS objet1_description,
    o1.prix_estimatif AS objet1_prix,
    o1.user_id AS objet1_user_id,
    u1.username AS objet1_owner_name,
    u1.pdp AS objet1_owner_pdp,
    (SELECT url FROM tt_photos_objet WHERE objet_id = o1.id LIMIT 1) AS objet1_photo,
    
    -- Infos objet 2
    o2.libelle AS objet2_libelle,
    o2.description AS objet2_description,
    o2.prix_estimatif AS objet2_prix,
    o2.user_id AS objet2_user_id,
    u2.username AS objet2_owner_name,
    u2.pdp AS objet2_owner_pdp,
    (SELECT url FROM tt_photos_objet WHERE objet_id = o2.id LIMIT 1) AS objet2_photo,
    
    -- Statut
    s.libelle AS status_libelle
    
FROM tt_echanges e
INNER JOIN tt_objets o1 ON e.objet1_id = o1.id
INNER JOIN tt_users u1 ON o1.user_id = u1.id
INNER JOIN tt_objets o2 ON e.objet2_id = o2.id
INNER JOIN tt_users u2 ON o2.user_id = u2.id
INNER JOIN tt_status s ON e.status_id = s.id;
