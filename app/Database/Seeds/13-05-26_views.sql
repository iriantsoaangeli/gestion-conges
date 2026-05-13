
CREATE view v_employes as
SELECT e.id, e.nom, e.prenom, e.email, r.nom as role, d.nom as departement, e.date_embauche, e.actif
FROM employes e
LEFT JOIN roles r ON e.role_id = r.id
LEFT JOIN departements d ON e.departement_id = d.id;


CREATE View v_soldes as
SELECT s.id , employe_id, e.nom as nom_employe, e.prenom as prenom_employe, type_conge_id, tc.nom as type_conge, annee, jours_attribues, jours_pris,jours_attribues-jours_pris as jours_restants
FROM soldes s
JOIN employes e ON s.employe_id = e.id
JOIN types_conges tc ON s.type_conge_id = tc.id;    


CREATE VIEW v_conges AS
SELECT c.id, c.employe_id, e.nom as nom_employe, e.prenom as prenom_employe, c.type_conge_id, tc.nom as type_conge, c.date_debut, c.date_fin, c.statut
FROM conges c
JOIN employes e ON c.employe_id = e.id
JOIN types_conges tc ON c.type_conge_id = tc.id;