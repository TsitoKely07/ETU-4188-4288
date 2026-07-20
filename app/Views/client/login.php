<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mobile Money - Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container my-5 d-flex justify-content-center align-items-center" style="min-height: calc(100vh - 4rem);">
    <div class="card p-4 shadow-sm" style="width: 360px;">
        <div class="card-header bg-primary text-white text-center mb-3">Connexion Client Mobile Money</div>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger p-2 text-center">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('client/loginProcess') ?>" method="post">
            <div class="mb-3">
                <label for="numero" class="form-label">Numéro de téléphone</label>
                <input type="text" name="numero" class="form-control" placeholder="ex: 0331234567" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Se Connecter</button>
        </form>
    </div>
</div>
</body>
</html>