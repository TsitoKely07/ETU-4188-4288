<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Espace Opérateur Money</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container my-5">
    <h1 class="mb-4">Backoffice Opérateur Mobile Money</h1>

    <div class="row mb-5">
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-success text-white">Situation des Gains</div>
                <div class="card-body">
                    <table class="table table-striped align-middle">
                        <thead><tr><th>Type d'Opération</th><th>Total des Gains</th></tr></thead>
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
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">Situation des Comptes Clients</div>
                <div class="card-body" style="max-height: 250px; overflow-y: auto;">
                    <table class="table table-hover align-middle">
                        <thead><tr><th>Numéro de Téléphone</th><th>Solde Actuel</th></tr></thead>
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
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">Prefixe Valable</div>
                <div class="card-body">
                    <form action="/operator/addPrefix" method="post" class="d-flex mb-3">
                        <input type="text" name="prefixe" class="form-control me-2" placeholder="Ex: 032" required maxlength="5">
                        <button type="submit" class="btn btn-dark">Ajouter</button>
                    </form>
                    <ul class="list-group">
                        <?php foreach($prefixes as $p): ?>
                            <li class="list-group-item d-flex justify-content-between align-middle">
                                <span>📱 Numéros commençant par <strong><?= esc($p['prefixe']) ?></strong></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>


        <div class="col-md-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">Barèmes des Frais par Tranche</div>
                <div class="card-body">
   
                    <form action="/operator/saveBareme" method="post" class="row g-2 mb-4">
                        <div class="col-md-3">
                            <select name="id_type_operation" class="form-select" required>
                                <option value="2">Retrait</option>
                                <option value="3">Transfert</option>
                            </select>
                        </div>
                        <div class="col-md-3"><input type="number" name="montant_min" class="form-control" placeholder="Min" required></div>
                        <div class="col-md-3"><input type="number" name="montant_max" class="form-control" placeholder="Max" required></div>
                        <div class="col-md-2"><input type="number" name="frais" class="form-control" placeholder="Frais" required></div>
                        <div class="col-md-1"><button type="submit" class="btn btn-primary w-100">+</button></div>
                    </form>

                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr><th>Type</th><th>Tranche Montant</th><th>Frais</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach($baremes as $b): ?>
                                <tr>
                                    <td class="text-capitalize text-danger fw-bold"><?= esc($b['type_nom']) ?></td>
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
</div>
</body>
</html>