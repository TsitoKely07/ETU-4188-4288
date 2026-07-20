<?= $this->extend('operator/layout') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <span class="brand-dot"></span>
    <div>
        <p class="eyebrow">Backoffice</p>
        <h1>Comptes clients</h1>
    </div>
</div>

<div class="grid-2">
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
<?= $this->endSection() ?>
