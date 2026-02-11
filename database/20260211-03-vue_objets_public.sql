-- Vue pour la liste publique des objets avec toutes les informations nécessaires
CREATE OR REPLACE VIEW v_objets_public AS
SELECT 
    o.id,
    o.libelle,
    o.description,
    o.prix_estimatif,
    o.category_id,
    o.user_id,
    c.libelle as category_name,
    u.username as owner_name,
    (SELECT url FROM tt_photos_objet WHERE objet_id = o.id LIMIT 1) as main_photo,
    (SELECT COUNT(*) FROM tt_photos_objet WHERE objet_id = o.id) as photos_count
FROM tt_objets o
INNER JOIN tt_users u ON o.user_id = u.id
INNER JOIN tt_categories c ON o.category_id = c.id;