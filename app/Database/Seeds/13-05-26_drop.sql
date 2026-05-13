-- Drop des vues (si elles existent)
DROP VIEW IF EXISTS v_conges;
DROP VIEW IF EXISTS v_soldes;
DROP VIEW IF EXISTS v_employes;

-- Drop des tables (ordre inverse des dependances)
DROP TABLE IF EXISTS conges;
DROP TABLE IF EXISTS status_conges;
DROP TABLE IF EXISTS soldes;
DROP TABLE IF EXISTS types_conges;
DROP TABLE IF EXISTS employes;
DROP TABLE IF EXISTS roles;
DROP TABLE IF EXISTS departements;
