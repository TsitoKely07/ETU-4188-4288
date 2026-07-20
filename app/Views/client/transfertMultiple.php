<div class="card">
    <div class="card-header">
        <h3>Transfert Multiple</h3>
    </div>
    <div class="card-body">

        <!-- Affichage des messages d'erreur ou de succès -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('client/transfert-multiple') ?>" method="post">
            <?= csrf_field() ?>

            <!-- Liste des numéros -->
            <div class="form-group mb-3">
                <label for="numeros" class="form-label">Numéros des destinataires :</label>
                <textarea 
                    name="numeros" 
                    id="numeros" 
                    class="form-control" 
                    rows="3" 
                    placeholder="Ex: 0331234567, 0379876543 (séparés par des virgules, espaces ou retours à la ligne)" 
                    required></textarea>
                <small class="form-text text-muted">
                    Saisissez plusieurs numéros séparés par une virgule, un espace ou un retour à la ligne.
                </small>
            </div>

            <!-- Montant total global -->
            <div class="form-group mb-3">
                <label for="montant_total" class="form-label">Montant total global à distribuer (Ar) :</label>
                <input 
                    type="number" 
                    step="0.01" 
                    name="montant_total" 
                    id="montant_total" 
                    class="form-control" 
                    placeholder="Ex: 50000" 
                    required>
                <small class="form-text text-muted">
                    Ce montant sera divisé équitablement entre tous les destinataires valides.
                </small>
            </div>

            <!-- Case à cocher pour les frais de retrait -->
            <div class="form-check mb-4">
                <input 
                    type="checkbox" 
                    name="inclure_frais_retrait" 
                    id="inclure_frais_retrait" 
                    value="1" 
                    class="form-check-input">
                <label for="inclure_frais_retrait" class="form-check-label">
                    Prendre en charge (offrir) les frais de retrait pour chaque destinataire
                </label>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                Envoyer le transfert multiple
            </button>
        </form>

    </div>
</div>