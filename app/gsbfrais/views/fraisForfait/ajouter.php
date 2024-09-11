<h1 class="container-lg text-center">Saisir mes frais forfaitisés pour la période <?= $periode ?></h1>

<!-- SAISIE DES FRAIS FORFAITISÉS -->
<section class="container-lg col-lg-7 mb-5">
    <h2 class="text-info border-bottom border-info"">Eléments forfaitisés (exprimés en « quantité »)</h2>

    <form action="saisirFraisForfait" method="POST" novalidate>

        <?php if (empty($errorMessage) == false): ?>
            <div class="alert alert-dismissible alert-danger">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <?= $errorMessage ?>
            </div>
        <?php endif; ?>
        <?php if (empty($infoMessage) == false): ?>
            <div class="alert alert-dismissible alert-success">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <?= $infoMessage ?>
            </div>
        <?php endif; ?>
        
        <?php foreach ($lesFraisForfait as $unFrais) : ?>
            <div class="row mb-3">
                <label for="<?= $unFrais->code_categorie ?>" class="col-4 col-lg-3 col-form-label"><?= htmlspecialchars($unFrais->libelle) ?></label>
                <div class="col-8 col-lg-9">
                    <input type="text" class="form-control" id="<?= $unFrais->code_categorie ?>" 
                    name="lesFrais[<?= $unFrais->code_categorie ?>]" 
                    value="<?= $unFrais->quantite ?>" required />
                </div>
            </div>
        <?php endforeach; ?>
        <div class="row mb-3">
            <div class="offset-3 offset-md-3">
                <button type="reset" class="btn btn-primary">Effacer</button>
                <button type="submit" class="btn btn-primary">Valider</button>
            </div>
        </div>
    </form>

</section>
