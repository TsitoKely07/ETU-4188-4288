<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Espace Client</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
        <span class="navbar-brand">Mobile Money Client</span>
        <span class="navbar-text text-white me-auto ms-3">
            N° : <strong><?= esc($client['numero']) ?></strong>
        </span>
        <a href="<?= base_url('client/logout') ?>" class="btn btn-outline-light btn-sm">Déconnexion</a>
    </div>
</nav>

<div class="container">

    <!-- Messages Flash -->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <!-- Solde -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card text-white bg-success">
                <div class="card-body text-center">
                    <h5 class="card-title">Solde Actuel</h5>
                    <h2 class="display-4"><?= number_format($client['solde'], 2, ',', ' ') ?> Ar</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaires d'actions -->
    <div class="row mb-4">
        <!-- Dépôt -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">Dépôt (Automatique)</div>
                <div class="card-body">
                    <form action="<?= base_url('client/depot') ?>" method="post">
                        <div class="mb-3">
                            <label class="form-label">Montant (Ar)</label>
                            <input type="number" step="0.01" name="montant" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Déposer</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Retrait -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-warning text-dark">Retrait (Automatique)</div>
                <div class="card-body">
                    <form action="<?= base_url('client/retrait') ?>" method="post">
                        <div class="mb-3">
                            <label class="form-label">Montant (Ar)</label>
                            <input type="number" step="0.01" name="montant" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-warning w-100">Retirer</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Transfert -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-info text-white">Transfert</div>
                <div class="card-body">
                    <form action="<?= base_url('client/transfert') ?>" method="post">
                        <div class="mb-3">
                            <label class="form-label">N° Destinataire</label>
                            <input type="text" name="numero_dest" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Montant (Ar)</label>
                            <input type="number" step="0.01" name="montant" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-info text-white w-100">Transférer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Historique des opérations -->
    <div class="card mb-4">
        <div class="card-header">Historique des Opérations</div>
        <div class="card-body">
            <table class="table table-striped">
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
                            <td colspan="4" class="text-center">Aucune opération effectuée.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($historiques as $h): ?>
                            <tr>
                                <td><?= $h['date_operation'] ?></td>
                                <td><span class="badge bg-secondary"><?= ucfirst($h['type_nom']) ?></span></td>
                                <td><?= number_format($h['montant'], 2, ',', ' ') ?> Ar</td>
                                <td><?= number_format($h['frais'], 2, ',', ' ') ?> Ar</td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
</body>
</html>