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
    JOIN tt_categories c ON c.id=o.category_id; 