CREATE TABLE departements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    description TEXT,
    actif BOOLEAN DEFAULT TRUE
);

CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE employes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE,
    password password NOT NULL,
    role_id INT,
    departement_id INT,
    date_embauche DATE,
    actif BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (departement_id) REFERENCES departements (id),
    FOREIGN KEY (role_id) REFERENCES roles (id)
);

CREATE TABLE types_conges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL UNIQUE,
    deductible BOOLEAN DEFAULT TRUE
);

CREATE TABLE soldes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employe_id INT,
    type_conge_id INT,
    annee INT,
    jours_attribues INT,
    jours_pris INT
);


CREATE TABLE status_conges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE conges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employe_id INT,
    type_conge_id INT,
    date_debut DATE,
    date_fin DATE,
    nb_jours INT,
    motif TEXT,
    status_id INT,
    commentaire_rh TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    traite_par INT,
    FOREIGN KEY (employe_id) REFERENCES employes (id),
    FOREIGN KEY (type_conge_id) REFERENCES types_conges (id),
    FOREIGN KEY (status_id) REFERENCES status_conges (id),
    FOREIGN KEY (traite_par) REFERENCES employes (id)
);