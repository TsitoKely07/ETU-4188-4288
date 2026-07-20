<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Espace Opérateur Money</title>
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
        }
        *{ box-sizing:border-box; }
        body{
            margin:0;
            font-family:'Inter',sans-serif;
            color:var(--black);
            background:var(--bg);
        }

        .topbar{
            background:#0f172a;
            color:#fff;
        }
        .topbar-inner{
            max-width:1180px;
            margin:0 auto;
            padding:18px 24px;
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:12px;
        }
        .title{
            font-weight:700;
            font-size:1rem;
        }
        .operator-actions{
            display:flex;
            align-items:center;
            gap:14px;
            flex-wrap:wrap;
        }
        .topbar-logout{
            display:inline-block;
            border:1px solid rgba(255,255,255,0.65);
            color:#fff;
            text-decoration:none;
            padding:8px 14px;
            border-radius:10px;
            transition:background .15s ease;
        }
        .topbar-logout:hover{
            background:rgba(255,255,255,0.08);
        }

        .container{
            max-width:1180px;
            margin:0 auto;
            padding:48px 24px 64px;
        }

        .page-header{
            display:flex;
            align-items:center;
            gap:12px;
            margin-bottom:32px;
        }
        .brand-dot{
            width:14px;height:14px            background:var(--pink);
        }
        h1{
            font-family:'Sora',sans-serif;
            font-weight:800;
            font-size:1.7rem;
            letter-spacing:-0.02em;
            margin:0;
        }
        .eyebrow{
            font-size:0.8rem;
            font-weight:700;
            text-transform:uppercase;
            letter-spacing:0.06em;
            color:var(--gray);
            margin:0 0 4px;
        }

        .grid-2{
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:20px;
            margin-bottom:24px;
        }
        .grid-3{
            display:grid;
            grid-template-columns:1fr 2fr;
            gap:20px;
        }
        @media (max-width:900px){
            .grid-2, .grid-3{ grid-template-columns:1fr; }
        }

        .panel{
            background:#fff;
        
            border:1px solid rgba(15,15,16,0.06);
            overflow:hidden;
            display:flex;
            flex-direction:column;
        }
        .panel-header{
            padding:18px 24px;
            font-family:'Sora',sans-serif;
            font-weight:700;
            font-size:1rem;
            color:#fff;
            display:flex;
            align-items:center;
            gap:10px;
        }
        .panel-header.tone-pink{ background:var(--pink); }
        .panel-header.tone-black{ background:var(--black); }
        .panel-body{
            padding:20px 24px 24px;
            flex:1;
        }
        .panel-body.scroll{
            max-height:270px;
            overflow-y:auto;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }
        thead th{
            text-align:left;
            font-size:0.72rem;
            text-transform:uppercase;
            letter-spacing:0.05em;
            color:var(--gray);
            font-weight:700;
            padding:10px 8px;
            border-bottom:1px solid rgba(15,15,16,0.07);
        }
        tbody td{
            padding:13px 8px;
            font-size:0.92rem;
            border-bottom:1px solid rgba(15,15,16,0.05);
        }
        tbody tr:last-child td{ border-bottom:none; }
        .text-capitalize{ text-transform:capitalize; }
        .text-end{ text-align:right; }
        .fw-bold{ font-weight:700; }
        .text-secondary{ color:var(--gray); }

        .badge{
            display:inline-block;
        ;
            padding:5px 14px;
            font-size:0.78rem;
            font-weight:700;
            background:var(--pink);
            color:#fff;
        }

        /* Formulaires */
        .inline-form{
            display:flex;
            gap:10px;
            margin-bottom:18px;
        }
        .field{
            width:100%;
            border:1.5px solid #E7E5E0;
        
            padding:11px 14px;
            font-family:'Inter',sans-serif;
            font-size:0.92rem;
            background:#FAFAF8;
            outline:none;
            transition:border-color .15s ease;
        }
        .field:focus{ border-color:var(--pink); background:#fff; }
        select.field{ appearance:none; cursor:pointer; }

        .btn{
            border:none;
        ;
            padding:11px 20px;
            font-family:'Sora',sans-serif;
            font-weight:700;
            font-size:0.9rem;
            cursor:pointer;
            white-space:nowrap;
            background:var(--black);
            color:#fff;
            transition:transform .12s ease, background .15s ease;
        }
        .btn:hover{ background:var(--pink); transform:translateY(-1px); }
        .btn-yellow{ background:var(--yellow); color:var(--black); }
        .btn-yellow:hover{ background:var(--black); color:#fff; }

        .form-bareme{
            display:grid;
            grid-template-columns:1.4fr 1fr 1fr 1fr auto;
            gap:10px;
            margin-bottom:22px;
        }
        @media (max-width:640px){
            .form-bareme{ grid-template-columns:1fr 1fr; }
        }

        .prefix-list{
            list-style:none;
            margin:0;
            padding:0;
            display:flex;
            flex-direction:column;
            gap:8px;
        }
        .prefix-item{
            background:var(--purple);
        
            padding:12px 16px;
            font-size:0.9rem;
            display:flex;
            align-items:center;
            gap:8px;
        }
        .prefix-item strong{ font-weight:800; }
    </style>
</head>
<body>
<header class="topbar">
    <div class="topbar-inner">
        <div class="title">Espace Opérateur</div>
        <div class="operator-actions">
            <?php if (session()->has('operator')): ?>
                <span>Connecté comme <strong><?= esc(session()->get('operator')['username']) ?></strong></span>
            <?php endif; ?>
            <a href="<?= base_url('operator/logout') ?>" class="topbar-logout">Déconnexion</a>
        </div>
    </div>
</header>
<div class="container">

    <div class="page-header">
        <span class="brand-dot"></span>
        <div>
            <p class="eyebrow">Backoffice</p>
            <h1>Espace Opérateur Mobile Money</h1>
        </div>
    </div>

    <div class="grid-2">
        <!-- Situation des Gains -->
        <div class="panel">
            <div class="panel-header tone-pink">Situation des gains</div>
            <div class="panel-body">
                <table>
                    <thead><tr><th>Type d'opération</th><th>Total des gains</th></tr></thead>
                    <tbody>
                        <?php foreach($gains as $g): ?>
                            <tr>
                                <td class="text-capitalize"><?= esc($g['type']) ?></td>
                                <td class="fw-bold"><?= number_format($g['total_frais'], 2, ',', ' ') ?> Ar</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Situation des Comptes Clients -->
        <div class="panel">
            <div class="panel-header tone-black">Situation des comptes clients</div>
            <div class="panel-body scroll">
                <table>
                    <thead><tr><th>Numéro de téléphone</th><th>Solde actuel</th></tr></thead>
                    <tbody>
                        <?php foreach($clients as $c): ?>
                            <tr>
                                <td><?= esc($c['numero_telephone']) ?></td>
                                <td class="text-end fw-bold text-secondary"><?= number_format($c['solde'], 2, ',', ' ') ?> Ar</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="grid-3">
        <!-- Prefixe Valable -->
        <div class="panel">
            <div class="panel-header tone-black">Préfixes valables</div>
            <div class="panel-body">
                <form action="/operator/addPrefix" method="post" class="inline-form">
                    <input type="text" name="prefixe" class="field" placeholder="Ex : 032" required maxlength="5">
                    <button type="submit" class="btn">Ajouter</button>
                </form>
                <ul class="prefix-list">
                    <?php foreach($prefixes as $p): ?>
                        <li class="prefix-item">Numéros commençant par <strong><?= esc($p['prefixe']) ?></strong></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <!-- Baremes des Frais -->
        <div class="panel">
            <div class="panel-header tone-pink">Barèmes des frais par tranche</div>
            <div class="panel-body">
                <form action="/operator/saveBareme" method="post" class="form-bareme">
                    <select name="id_type_operation" class="field" required>
                        <option value="2">Retrait</option>
                        <option value="3">Transfert</option>
                    </select>
                    <input type="number" name="montant_min" class="field" placeholder="Min" required>
                    <input type="number" name="montant_max" class="field" placeholder="Max" required>
                    <input type="number" name="frais" class="field" placeholder="Frais" required>
                    <button type="submit" class="btn btn-yellow">+</button>
                </form>

                <table>
                    <thead>
                        <tr><th>Type</th><th>Tranche montant</th><th>Frais</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach($baremes as $b): ?>
                            <tr>
                                <td><span class="badge"><?= esc($b['type_nom']) ?></span></td>
                                <td>De <?= number_format($b['montant_min'], 0, ',', ' ') ?> à <?= number_format($b['montant_max'], 0, ',', ' ') ?> Ar</td>
                                <td class="fw-bold"><?= number_format($b['frais'], 0, ',', ' ') ?> Ar</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
</body>
</html>