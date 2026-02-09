CREATE OR REPLACE VIEW v_users_roles AS
SELECT id,
         username,
         role,
         count(*) nb
FROM tt_users GROUP BY role;