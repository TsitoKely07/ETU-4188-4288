-- Préfixes autorisés
CREATE TABLE prefixe (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    code VARCHAR(10) NOT NULL UNIQUE
);

INSERT INTO prefixe (code) VALUES ('033'), ('037');

-- Comptes clients
CREATE TABLE compte_client (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    numero VARCHAR(20) NOT NULL UNIQUE,
    solde REAL DEFAULT 0.0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Types d'opérations
CREATE TABLE type_operation (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom VARCHAR(50) NOT NULL UNIQUE
);

INSERT INTO type_operation (nom) VALUES ('depot'), ('retrait'), ('transfert');

-- Barèmes de frais
CREATE TABLE bareme_frais (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_type_operation INTEGER NOT NULL,
    montant_min REAL NOT NULL,
    montant_max REAL NOT NULL,
    frais REAL NOT NULL,
    FOREIGN KEY (id_type_operation) REFERENCES type_operation(id)
);

-- Exemple de barèmes pour retrait et transfert
INSERT INTO bareme_frais (id_type_operation, montant_min, montant_max, frais) VALUES 
(2, 1000, 10000, 200),    -- Retrait
(2, 10001, 50000, 500),
(3, 1000, 10000, 100),    -- Transfert
(3, 10001, 50000, 250);

-- Historique des transactions
CREATE TABLE historique_operation (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_compte_source INTEGER NOT NULL,
    id_compte_dest INTEGER, -- NULL si ce n'est pas un transfert
    id_type_operation INTEGER NOT NULL,
    montant REAL NOT NULL,
    frais REAL DEFAULT 0.0,
    date_operation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_compte_source) REFERENCES compte_client(id),
    FOREIGN KEY (id_compte_dest) REFERENCES compte_client(id),
    FOREIGN KEY (id_type_operation) REFERENCES type_operation(id)
);