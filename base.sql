
PRAGMA foreign_keys = OFF;
DROP TABLE IF EXISTS transactions;
DROP TABLE IF EXISTS clients;
DROP TABLE IF EXISTS baremes_frais;
DROP TABLE IF EXISTS types_operation;
DROP TABLE IF EXISTS prefixes;
PRAGMA foreign_keys = ON;

CREATE TABLE operateur (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom VARCHAR(100) NOT NULL UNIQUE,
    code VARCHAR(20)
);

INSERT INTO operateur (nom, code) VALUES ('Airtel', 'LOC'), ('Orange', 'OPA');
INSERT INTO operateur (nom, code) VALUES ('Telma', 'TEL');

CREATE TABLE prefixe (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    code VARCHAR(10) NOT NULL,
    id_operateur INTEGER, -- NULL = préfixe générique ou non associé
    UNIQUE(code, id_operateur),
    FOREIGN KEY (id_operateur) REFERENCES operateur(id)
);

INSERT INTO prefixe (code, id_operateur) VALUES ('033', 1), ('037', 1), ('038', 2), ('034', 2);


CREATE TABLE compte_client (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    numero VARCHAR(20) NOT NULL UNIQUE,
    solde REAL DEFAULT 0.0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE type_operation (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom VARCHAR(50) NOT NULL UNIQUE
);

INSERT INTO type_operation (nom) VALUES ('depot'), ('retrait'), ('transfert');


CREATE TABLE bareme_frais (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_type_operation INTEGER NOT NULL,
    montant_min REAL NOT NULL,
    montant_max REAL NOT NULL,
    frais REAL NOT NULL,
    pourcentage_promotion INTEGER,
    id_operateur INTEGER DEFAULT NULL, -- si NULL = barème propre, sinon barème pour transferts vers cet opérateur
    type_frais VARCHAR(50) DEFAULT 'standard', -- ex: 'retrait', 'transfert_interne', 'transfert_externe'
    FOREIGN KEY (id_type_operation) REFERENCES type_operation(id),
    FOREIGN KEY (id_operateur) REFERENCES operateur(id)
);




INSERT INTO bareme_frais (id_type_operation, montant_min, montant_max, frais, id_operateur, type_frais) VALUES 
(2, 1000, 10000, 200, NULL, 'retrait'), 
(2, 10001, 50000, 500, NULL, 'retrait'),
(3, 1000, 10000, 100, NULL, 'transfert_interne'),   
(3, 10001, 50000, 250, NULL, 'transfert_interne');


CREATE TABLE commission_interoperateur (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_operateur_source INTEGER NOT NULL,
    id_operateur_destination INTEGER NOT NULL,
    pourcentage_commission REAL NOT NULL,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_operateur_source) REFERENCES operateur(id),
    FOREIGN KEY (id_operateur_destination) REFERENCES operateur(id)
);


INSERT INTO commission_interoperateur (id_operateur_source, id_operateur_destination, pourcentage_commission) VALUES (1, 2, 5.0);


CREATE TABLE decompte_operateur (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_operateur INTEGER NOT NULL,
    mois_annee VARCHAR(7) NOT NULL, -- format YYYY-MM
    montant_total_a_envoyer REAL DEFAULT 0.0,
    montant_deja_envoye REAL DEFAULT 0.0,
    statut VARCHAR(20) DEFAULT 'en_attente',
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_operateur) REFERENCES operateur(id)
);


CREATE TABLE historique_operation (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_compte_source INTEGER NOT NULL,
    id_compte_dest INTEGER, 
    id_type_operation INTEGER NOT NULL,
    montant REAL NOT NULL,
    frais REAL DEFAULT 0.0,
    id_operateur_destination INTEGER DEFAULT NULL, -- pour transfert interopérateur
    frais_retrait_inclus INTEGER DEFAULT 0, -- 0=false, 1=true
    montants_destinations TEXT DEFAULT NULL, -- JSON string pour transferts multiples
    transaction_parent INTEGER DEFAULT NULL, -- id de l'opération parent si multiple
    date_operation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_compte_source) REFERENCES compte_client(id),
    FOREIGN KEY (id_compte_dest) REFERENCES compte_client(id),
    FOREIGN KEY (id_type_operation) REFERENCES type_operation(id),
    FOREIGN KEY (id_operateur_destination) REFERENCES operateur(id)
);

-- Exemple d'insertions de base
INSERT INTO compte_client (numero, solde) VALUES ('0331234567', 50000), ('0379876543', 20000);

-- fin du schéma V2