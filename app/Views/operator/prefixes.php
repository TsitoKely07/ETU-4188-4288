<?= $this->extend('operator/layout') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <span class="brand-dot"></span>
    <div>
        <p class="eyebrow">Backoffice</p>
        <h1>Préfixes valables</h1>
    </div>
</div>

<div class="grid-3">
    <div class="panel">
        <div class="panel-header tone-black">Ajouter un préfixe</div>
        <div class="panel-body">
            <form action="<?= base_url('operator/addPrefix') ?>" method="post" class="inline-form">
                <input type="text" name="prefixe" class="field" placeholder="Ex : 032" required maxlength="5">
                <button type="submit" class="btn">Ajouter</button>
            </form>
        </div>
    </div>

    <div class="panel">
        <div class="panel-header tone-black">Liste des préfixes</div>
        <div class="panel-body">
            <ul class="prefix-list">
                <?php foreach($prefixes as $p): ?>
                    <li class="prefix-item">Numéros commençant par <strong><?= esc($p['prefixe']) ?></strong></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
