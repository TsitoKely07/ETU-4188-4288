<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mon Espace Client</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root{
            --bg:#F7F6F2;
            --pink:#00BFFF;
            --yellow:#FFC72C;
            --purple:#C9BEFF;
            --black:#0F0F10;
            --gray:#6B6A70;
            --orange:#FF8C00;
        }
        *{ box-sizing:border-box; }
        body{
            margin:0;
            font-family:'Inter' ,-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            color:var(--black);
            background:var(--bg);
        }
        a{ text-decoration:none; }

        /* NAV */
        .navbar{
            background:#fff;
            border-bottom:1px solid rgba(15,15,16,0.06);
        }
        .navbar-inner{
            max-width:1120px;
            margin:0 auto;
            padding:16px 24px;
            display:flex;
            align-items:center;
            gap:16px;
        }
        .brand{
            display:flex;
            align-items:center;
            gap:8px;
            font-family: 'Inter';
            font-weight:800;
            font-size:1.1rem;
            letter-spacing:-0.02em;
        }
        .brand-dot{
            width:14px;height:14px;
            background:var(--pink);
        }
        .numero-pill{
            margin-left:8px;
            background:var(--purple);
            color:var(--black);
            padding:6px 16px;
            font-weight:600;
            font-size:0.85rem;
        }
        .btn-logout{
            margin-left:auto;
            border:1.5px solid rgba(15,15,16,0.15);
            padding:9px 18px;
            font-weight:600;
            font-size:0.85rem;
            color:var(--black);
            background:#fff;
            transition:background .15s ease;
        }
        .btn-logout:hover{ background:#F1F0EC; }

        /* CONTAINER */
        .container{
            max-width:1120px;
            margin:0 auto;
            padding:40px 24px 64px;
        }

        .alert{
            padding:12px 18px;
            font-size:0.9rem;
            margin-bottom:24px;
            font-weight:500;
        }
        .alert-danger{
            background:rgba(255,31,109,0.10);
            color:#C4104F;
            border:1px solid rgba(255,31,109,0.25);
        }
        .alert-success{
            background:rgba(60,180,110,0.10);
            color:#1F8F52;
            border:1px solid rgba(60,180,110,0.25);
        }

        /* SOLDE */
        .balance-card{
            background:linear-gradient(135deg,var(--pink),#FF5A93);
            color:#fff;
            padding:36px 40px;
            margin-bottom:32px;
            display:flex;
            align-items:center;
            justify-content:space-between;
            flex-wrap:wrap;
            gap:16px;
        }
        .balance-card .label{
            font-size:0.95rem;
            font-weight:600;
            opacity:0.9;
            margin:0 0 6px;
        }
        .balance-card .amount{
            font-family: 'inter';
            font-weight:800;
            font-size:2.6rem;
            letter-spacing:-0.02em;
            margin:0;
        }

        /* NAVIGATION BAR DES ACTIONS (TABS) */
        .tabs-nav {
            display: flex;
            gap: 10px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }
        .tab-btn {
            flex: 1;
            min-width: 140px;
            padding: 14px 18px;
            border: none;
            font-family: 'Inter';
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            background: #fff;
            color: var(--black);
            border: 1px solid rgba(15,15,16,0.08);
            transition: all .15s ease;
        }
        .tab-btn:hover {
            transform: translateY(-2px);
        }
        /* Styles actifs personnalisés selon le type d'opération */
        .tab-btn[data-tab="depot"].active {
            background: var(--purple);
            border-color: var(--purple);
            color: var(--black);
        }
        .tab-btn[data-tab="retrait"].active {
            background: var(--yellow);
            border-color: var(--yellow);
            color: var(--black);
        }
        .tab-btn[data-tab="transfert"].active {
            background: var(--black);
            border-color: var(--black);
            color: #fff;
        }
        .tab-btn[data-tab="transfert-multiple"].active {
            background: var(--orange);
            border-color: var(--orange);
            color: #fff;
        }

        /* CONTENEURS DE FORMULAIRE (PANELS) */
        .action-card {
            padding: 32px;
            display: none; /* Masqué par défaut */
            flex-direction: column;
            margin-bottom: 32px;
        }
        .action-card.active {
            display: flex; /* Affiché quand actif */
        }

        .action-card.depot { background:var(--purple); }
        .action-card.retrait { background:var(--yellow); }
        .action-card.transfert { background:var(--black); color:#fff; }
        .action-card.transfert-multiple { background:var(--orange); color:#fff; }
        
        .action-title {
            font-family: 'inter';
            font-weight:700;
            font-size:1.2rem;
            margin:0 0 20px;
            letter-spacing:-0.01em;
        }
        .action-card label {
            font-size:0.85rem;
            font-weight:600;
            margin-bottom:6px;
            display:block;
            opacity:0.85;
        }
        .form-control {
            width:100%;
            border:none;
            padding:12px 14px;
            font-family:'Inter' ,-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            font-size:0.95rem;
            background:rgba(255,255,255,0.85);
            outline:none;
            margin-bottom:16px;
        }

        .action-card.transfert .form-control,
        .action-card.transfert-multiple .form-control {
            background:rgba(255,255,255,0.15);
            color:#fff;
        }
        .action-card.transfert .form-control::placeholder,
        .action-card.transfert-multiple .form-control::placeholder { 
            color:rgba(255,255,255,0.6); 
        }

        /* CHECKBOX OPTION */
        .checkbox-container {
            display:flex;
            align-items:center;
            gap:8px;
            margin-bottom:20px;
            cursor:pointer;
        }
        .checkbox-container input[type="checkbox"] {
            width:16px;
            height:16px;
            cursor:pointer;
        }
        .checkbox-container span {
            font-size:0.85rem;
            font-weight:500;
            opacity:0.9;
        }

        .btn-action {
            margin-top:auto;
            border:none;
            padding:14px;
            font-family: 'inter';
            font-weight:700;
            font-size:0.95rem;
            cursor:pointer;
            background:var(--black);
            color:#fff;
            transition:transform .12s ease;
            width: 100%;
        }
        .action-card.transfert .btn-action,
        .action-card.transfert-multiple .btn-action {
            background:#fff;
            color:var(--black);
        }
        .btn-action:hover { transform:translateY(-1px); }

        /* HISTORIQUE */
        .history-card {
            background:#fff;
            padding:8px 8px 4px;
            border:1px solid rgba(15,15,16,0.06);
        }
        .history-header {
            font-family: 'inter';
            font-weight:700;
            font-size:1.05rem;
            padding:20px 24px 4px;
        }
        table {
            width:100%;
            border-collapse:collapse;
        }
        thead th {
            text-align:left;
            font-size:0.75rem;
            text-transform:uppercase;
            letter-spacing:0.04em;
            color:var(--gray);
            font-weight:600;
            padding:14px 24px;
            border-bottom:1px solid rgba(15,15,16,0.06);
        }
        tbody td {
            padding:16px 24px;
            font-size:0.92rem;
            border-bottom:1px solid rgba(15,15,16,0.05);
        }
        tbody tr:last-child td { border-bottom:none; }
        .badge {
            display:inline-block;
            padding:5px 14px;
            font-size:0.78rem;
            font-weight:700;
            background:var(--purple);
            color:var(--black);
        }
        .empty-row {
            text-align:center;
            color:var(--gray);
            padding:32px 24px;
        }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="navbar-inner">
        <span class="brand"><span class="brand-dot"></span>Mobile Money</span>
        <span class="numero-pill">N° <?= esc($client['numero']) ?></span>
        <a href="<?= base_url('client/logout') ?>" class="btn-logout">Déconnexion</a>
    </div>
</nav>

<div class="container">

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <!-- Solde -->
    <div class="balance-card">
        <div>
            <p class="label">Solde actuel</p>
            <h2 class="amount"><?= number_format($client['solde'], 2, ',', ' ') ?> Ar</h2>
        </div>
    </div>

    <!-- BOUTONS DE NAVIGATION DES OPERATIONS -->
    <div class="tabs-nav">
        <button class="tab-btn active" data-tab="depot">Dépôt</button>
        <button class="tab-btn" data-tab="retrait">Retrait</button>
        <button class="tab-btn" data-tab="transfert">Transfert Simple</button>
        <button class="tab-btn" data-tab="transfert-multiple">Transfert Multiple</button>
    </div>

    <!-- CONTENEURS DE FORMULAIRES -->
    <div class="actions-wrapper">
        <!-- Dépôt -->
        <div class="action-card depot active" id="tab-depot">
            <h3 class="action-title">Dépôt d'argent</h3>
            <form action="<?= base_url('client/depot') ?>" method="post">
                <label>Montant (Ar)</label>
<<<<<<< HEAD
                <input type="number" step="0.01" name="montant" class="form-control" placeholder="1000Ar" required>
                <button type="submit" class="btn-action">Déposer</button>
=======
                <input type="number" step="0.01" name="montant" class="form-control" placeholder="Ex: 10000" required>
                <button type="submit" class="btn-action">Valider le dépôt</button>
>>>>>>> b8089328fad1b4119a57f6615231dc654935b95a
            </form>
        </div>

        <!-- Retrait -->
        <div class="action-card retrait" id="tab-retrait">
            <h3 class="action-title">Retrait d'argent</h3>
            <form action="<?= base_url('client/retrait') ?>" method="post">
                <label>Montant (Ar)</label>
<<<<<<< HEAD
                <input type="number" step="0.01" name="montant" class="form-control" placeholder="1000Ar" required>
                <button type="submit" class="btn-action">Retirer</button>
=======
                <input type="number" step="0.01" name="montant" class="form-control" placeholder="Ex: 5000" required>
                <button type="submit" class="btn-action">Valider le retrait</button>
>>>>>>> b8089328fad1b4119a57f6615231dc654935b95a
            </form>
        </div>

        <!-- Transfert Simple -->
        <div class="action-card transfert" id="tab-transfert">
            <h3 class="action-title">Transfert Simple</h3>
            <form action="<?= base_url('client/transfert') ?>" method="post">
                <label>N° destinataire</label>
                <input type="text" name="numero_dest" class="form-control" placeholder="ex : 0331234567" required>
                <label>Montant (Ar)</label>
<<<<<<< HEAD
                <input type="number" step="0.01" name="montant" class="form-control" placeholder="1000Ar" required>
=======
                <input type="number" step="0.01" name="montant" class="form-control" placeholder="Ex: 20000" required>
>>>>>>> b8089328fad1b4119a57f6615231dc654935b95a
                
                <label class="checkbox-container">
                    <input type="checkbox" name="inclure_frais_retrait" value="1">
                    <span>Inclure frais de retrait pour le destinataire</span>
                </label>

                <button type="submit" class="btn-action">Effectuer le transfert</button>
            </form>
        </div>

        <!-- Transfert Multiple -->
        <div class="action-card transfert-multiple" id="tab-transfert-multiple">
            <h3 class="action-title">Transfert Multiple (2 Destinataires)</h3>
            <form action="<?= base_url('client/transfert-multiple') ?>" method="post">
                <?= csrf_field() ?>

                <label>Numéro Destinataire 1</label>
                <input type="text" name="numero_dest_1" class="form-control" placeholder="Ex: 0331234567" required>

                <label>Numéro Destinataire 2</label>
                <input type="text" name="numero_dest_2" class="form-control" placeholder="Ex: 0379876543" required>

                <label>Montant à envoyer à CHACUN (Ar)</label>
                <input type="number" step="0.01" name="montant" class="form-control" placeholder="Ex: 50000" required>

                <label class="checkbox-container">
                    <input type="checkbox" name="inclure_frais_retrait" value="1">
                    <span>Inclure frais de retrait pour les 2 personnes</span>
                </label>

                <button type="submit" class="btn-action">Envoyer à chacun</button>
            </form>
        </div>
    </div>

    <!-- Historique des opérations -->
    <div class="history-card">
        <div class="history-header">Historique des opérations</div>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Montant</th>
                    <th>Frais</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($historiques)): ?>
                    <tr>
                        <td colspan="4" class="empty-row">Aucune opération effectuée.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($historiques as $h): ?>
                        <tr>
                            <td><?= $h['date_operation'] ?></td>
                            <td><span class="badge"><?= ucfirst($h['type_nom']) ?></span></td>
                            <td><?= number_format($h['montant'], 2, ',', ' ') ?> Ar</td>
                            <td><?= number_format($h['frais'], 2, ',', ' ') ?> Ar</td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<!-- SCRIPT POUR CHANGER LES ONGLETS -->
<script>
    document.querySelectorAll('.tab-btn').forEach(button => {
        button.addEventListener('click', () => {
            const tabName = button.getAttribute('data-tab');

            // Réinitialiser les états actifs des boutons
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            
            // Réinitialiser les formulaires affichés
            document.querySelectorAll('.action-card').forEach(card => card.classList.remove('active'));

            // Activer le bouton cliqué et le formulaire correspondant
            button.classList.add('active');
            document.getElementById('tab-' + tabName).classList.add('active');
        });
    });
</script>

</body>
</html>