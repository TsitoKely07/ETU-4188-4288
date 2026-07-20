# Plan de migration - Mise en place des Migrations pour toutes les tables

## Étapes à réaliser

### 1. Créer les migrations manquantes
- [x] `CreateOperateurTable` - Table operateur
- [x] `CreateCommissionInteroperateurTable` - Table commission_interoperateur  
- [x] `CreateDecompteOperateurTable` - Table decompte_operateur

### 2. Mettre à jour les migrations existantes (incomplètes)
- [x] `CreatePrefixeTable` - Ajouter id_operateur + FK
- [x] `CreateBaremeFraisTable` - Ajouter id_operateur, type_frais
- [x] `CreateHistoriqueOperationTable` - Ajouter id_operateur_destination, frais_retrait_inclus, montants_destinations, transaction_parent

### 3. Mettre à jour le Seeder
- [x] `InitialDataSeeder` - Ajouter toutes les données initiales complètes

### 4. Tester
- [ ] Exécuter les migrations avec `php spark migrate:refresh`

