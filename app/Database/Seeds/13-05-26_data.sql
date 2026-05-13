-- Donnees d'initialisation (13-05-26)

-- Departements
INSERT INTO departements (id, nom, description) VALUES
	(1, 'RH', 'Ressources humaines'),
	(2, 'IT', 'Informatique et support'),
	(3, 'Finance', 'Comptabilite et budget'),
	(4, 'Operations', 'Operations internes');

-- Roles
INSERT INTO roles (id, nom) VALUES
	(1, 'Admin'),
	(2, 'Manager'),
	(3, 'Employe');

-- Employes
INSERT INTO employes (id, nom, prenom, email, password, role_id, departement_id, date_embauche, actif) VALUES
	(1, 'Rakoto', 'Jean', 'jean.rakoto@example.com', 'hashed_jean', 1, 2, '2022-04-15', TRUE),
	(2, 'Rabe', 'Mia', 'mia.rabe@example.com', 'hashed_mia', 2, 1, '2023-01-10', TRUE),
	(3, 'Andry', 'Koto', 'koto.andry@example.com', 'hashed_koto', 3, 2, '2023-06-05', TRUE),
	(4, 'Lala', 'Sara', 'sara.lala@example.com', 'hashed_sara', 3, 3, '2024-02-20', TRUE),
	(5, 'Razafy', 'Nina', 'nina.razafy@example.com', 'hashed_nina', 3, 4, '2024-07-01', FALSE);

-- Types de conges
INSERT INTO types_conges (id, nom, deductible) VALUES
	(1, 'Conge annuel', TRUE),
	(2, 'Conge maladie', TRUE),
	(3, 'Conge sans solde', FALSE);

-- Status conges
INSERT INTO status_conges (id, nom) VALUES
	(1, 'En attente'),
	(2, 'Approuve'),
	(3, 'Refuse');

-- Soldes
INSERT INTO soldes (id, employe_id, type_conge_id, annee, jours_attribues, jours_pris) VALUES
	(1, 1, 1, 2025, 24, 5),
	(2, 2, 1, 2025, 24, 8),
	(3, 3, 1, 2025, 24, 2),
	(4, 4, 2, 2025, 10, 3),
	(5, 5, 3, 2025, 30, 0);

-- Conges
INSERT INTO conges (id, employe_id, type_conge_id, date_debut, date_fin, nb_jours, motif, status_id, commentaire_rh, traite_par) VALUES
	(1, 2, 1, '2025-03-10', '2025-03-14', 5, 'Vacances', 2, 'Bon voyage', 1),
	(2, 3, 2, '2025-02-05', '2025-02-07', 3, 'Grippe', 2, 'Certificat valide', 2),
	(3, 4, 1, '2025-04-22', '2025-04-25', 4, 'Mariage', 1, NULL, NULL),
	(4, 5, 3, '2025-01-15', '2025-01-20', 6, 'Projet personnel', 3, 'Refuse par manque de personnel', 2);
