#!/bin/bash
# Script to rebuild the SQLite database with the correct schema from base.sql
# while preserving existing data

DB_PATH="/opt/lampp/htdocs/Projet-Final-Igniter/writable/database.sqlite"
BACKUP_PATH="${DB_PATH}.backup"

echo "=== Database Rebuild Script ==="
echo "Backup exists at: $BACKUP_PATH"

# Remove the old database
rm -f "$DB_PATH"

# Create new database from base.sql schema
echo "Creating new database from base.sql schema..."
sqlite3 "$DB_PATH" < "/opt/lampp/htdocs/Projet-Final-Igniter/base.sql"

echo "Schema created. Importing existing data..."

# Insert existing data with proper column mapping
sqlite3 "$DB_PATH" <<'SQLEOF'
-- Insert existing compte_client data
INSERT OR IGNORE INTO compte_client (id, numero, solde, created_at) VALUES
(1, '0333300033', 39400.0, '2026-07-20 07:56:27'),
(2, '0330940020', 10000.0, '2026-07-20 08:01:56'),
(3, '0344400116', 0.0, '2026-07-20 08:13:05'),
(4, '0336632733', 41300001.72, '2026-07-20 08:58:29'),
(5, '0334444444', 688888.0, '2026-07-20 09:00:37');

-- Insert existing prefixe data (with NULL id_operateur for existing ones)
INSERT OR IGNORE INTO prefixe (id, code) VALUES
(1, '033'),
(2, '037'),
(3, '034');

-- Insert existing bareme_frais data (with default values for new columns)
INSERT OR IGNORE INTO bareme_frais (id, id_type_operation, montant_min, montant_max, frais) VALUES
(1, 2, 1000.0, 10000.0, 200.0),
(2, 2, 10001.0, 50000.0, 500.0),
(3, 3, 1000.0, 10000.0, 100.0),
(4, 3, 10001.0, 50000.0, 250.0),
(5, 2, 1000.0, 10000.0, 200.0),
(6, 2, 10001.0, 50000.0, 500.0),
(7, 3, 1000.0, 10000.0, 100.0),
(8, 3, 10001.0, 50000.0, 250.0),
(9, 3, 1.0, 20000.0, 200.0),
(10, 3, 5000.0, 10000.0, 150.0);

-- Insert existing historique_operation data (with NULL for new columns)
INSERT OR IGNORE INTO historique_operation (id, id_compte_source, id_compte_dest, id_type_operation, montant, frais, date_operation) VALUES
(1, 1, NULL, 1, 100000.0, 0.0, '2026-07-20 07:56:42'),
(2, 1, NULL, 2, 50000.0, 500.0, '2026-07-20 08:01:04'),
(3, 1, 2, 3, 10000.0, 100.0, '2026-07-20 08:02:10'),
(4, 4, NULL, 2, -0.09, 0.0, '2026-07-20 08:58:37'),
(5, 4, NULL, 1, 40000000.0, 0.0, '2026-07-20 08:58:58'),
(6, 4, NULL, 2, -0.05, 0.0, '2026-07-20 08:59:07'),
(7, 4, NULL, 2, -0.76, 0.0, '2026-07-20 08:59:19'),
(8, 4, NULL, 2, -0.82, 0.0, '2026-07-20 08:59:29'),
(9, 4, NULL, 2, 2900000.0, 0.0, '2026-07-20 08:59:50'),
(10, 5, NULL, 1, 4888888.0, 0.0, '2026-07-20 09:00:43'),
(11, 5, 4, 3, 700000.0, 0.0, '2026-07-20 09:00:58'),
(12, 4, 5, 3, 1000000.0, 0.0, '2026-07-20 09:01:39'),
(13, 5, 4, 3, 4500000.0, 0.0, '2026-07-20 09:02:20');

-- Reset the sequence counters
DELETE FROM sqlite_sequence;
INSERT INTO sqlite_sequence VALUES('prefixe', 3);
INSERT INTO sqlite_sequence VALUES('type_operation', 3);
INSERT INTO sqlite_sequence VALUES('bareme_frais', 10);
INSERT INTO sqlite_sequence VALUES('compte_client', 5);
INSERT INTO sqlite_sequence VALUES('historique_operation', 13);
INSERT INTO sqlite_sequence VALUES('operateur', 2);
INSERT INTO sqlite_sequence VALUES('commission_interoperateur', 1);

SQLEOF

echo "Data imported successfully!"
echo ""
echo "Verifying schema..."
sqlite3 "$DB_PATH" ".schema"

echo ""
echo "Verifying data counts..."
echo "compte_client: $(sqlite3 "$DB_PATH" "SELECT COUNT(*) FROM compte_client;")"
echo "type_operation: $(sqlite3 "$DB_PATH" "SELECT COUNT(*) FROM type_operation;")"
echo "historique_operation: $(sqlite3 "$DB_PATH" "SELECT COUNT(*) FROM historique_operation;")"
echo "bareme_frais: $(sqlite3 "$DB_PATH" "SELECT COUNT(*) FROM bareme_frais;")"
echo "prefixe: $(sqlite3 "$DB_PATH" "SELECT COUNT(*) FROM prefixe;")"
echo "operateur: $(sqlite3 "$DB_PATH" "SELECT COUNT(*) FROM operateur;")"
echo "commission_interoperateur: $(sqlite3 "$DB_PATH" "SELECT COUNT(*) FROM commission_interoperateur;")"
echo ""
echo "=== Rebuild complete! ==="
