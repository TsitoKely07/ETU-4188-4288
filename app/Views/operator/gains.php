<?= $this->extend('operator/layout') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <span class="brand-dot"></span>
    <div>
        <p class="eyebrow">Backoffice</p>
        <h1>Situation des gains</h1>
    </div>
</div>

<div class="grid-2">
    <div class="panel">
        <div class="panel-header tone-pink">Situation des gains par type d'opération</div>
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
</div>
<?= $this->endSection() ?>
