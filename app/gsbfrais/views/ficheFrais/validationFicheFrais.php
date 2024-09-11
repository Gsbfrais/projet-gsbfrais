<h1 class="container-lg text-center">Validation fiche frais <?= $laFiche->nom . ' ' .  $laFiche->prenom ?> - <?= $periode ?></h1>

<!-- Détail des frais forfait -->
<section class="container-lg col-lg-7 mb-5">
    <?php if (empty($errorMessage) == false) : ?>
        <div class="alert alert-dismissible alert-danger">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            <?= $errorMessage ?>
        </div>
    <?php endif; ?>
    <h2 class="text-info border-bottom border-info"">Frais forfaitisés</h2>
        <table class=" table">
        <tr class="table-primary">
            <?php foreach ($lesFraisForfait as $unFraisForfait) : ?>
                <th><?= htmlspecialchars($unFraisForfait->libelle) ?></th>
            <?php endforeach; ?>
        </tr>

        <tr>
            <?php foreach ($lesFraisForfait as $unFraisForfait) : ?>
                <td><?= $unFraisForfait->quantite ?> </td>
            <?php endforeach; ?>
        </tr>
        </table>
</section>

<!-- Détail des frais hors forfait -->
<section class="container-lg col-lg-7 mb-5">
    <h2 class="text-info border-bottom border-info">Frais hors forfait</h2>
    <table class="table">
        <tr class="table-primary">
            <th>Date</th>
            <th>Libellé</th>
            <th>Montant</th>
        </tr>
        <?php foreach ($lesFraisHorsForfait as $unFraisHorsForfait) : ?>
            <tr>
                <td><?= $unFraisHorsForfait->date ?></td>
                <td><?= htmlspecialchars($unFraisHorsForfait->libelle) ?></td>
                <td><?= $unFraisHorsForfait->montant ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</section>

<!-- Récapitulatif fiche frais -->
<section class="container-lg col-lg-7 mb-5">
    <h2 class="text-info border-bottom border-info">Récapitulatif de la fiche de frais</h2>
    <div class="d-flex flex-row">
        <div class="col-auto pe-5">
            <p>Montant total frais forfait</p>
            <p>Montant total frais hors forfait</p>
            <p>Statut</p>
            <p>Montant total</p>
        </div>
        <div>
            <p>: <?= number_format($laFiche->montantFraisForfait, 2) ?> €</p>
            <p>: <?= number_format($laFiche->montantFraisHorsForfait, 2) ?> €</p>
            <p>: <?= $laFiche->libelleStatutFiche ?></p>
            <p>: <?= number_format($laFiche->montantFraisForfait + $laFiche->montantFraisHorsForfait, 2) ?> €</p>
        </div>
    </div>
    </table>
</section>

<!-- Validation -->
<section class="container-lg col-lg-7 mb-5">
    <form action="validerFicheFrais&idVisiteur=<?= $idVisiteur ?>&mois=<?= $mois ?>" method="POST" novalidate>
        <div class="row mb-3">
            <label for="nbJustificatifs" class="col-4 col-lg-3 col-form-label">Nombre de justificatifs reçus</label>
            <div class="col-8 col-lg-4">
                <input type="text" class="form-control" id="nbJustificatifs" name="nbJustificatifs" value="<?= $nbJustificatifs ?>" />
            </div>
        </div>

        <div class="row mb-3">
            <label for="montantValide" class="col-4 col-lg-3 col-form-label">Montant validé</label>
            <div class="col-8 col-lg-4">
                <input type="text" class="form-control" id="montantValide" name="montantValide" value="<?= $montantValide ?>" />
            </div>
        </div>

        <div class="row mb-3">
            <div class="offset-3 offset-md-3">
                <button type="reset" class="btn btn-primary">Effacer</button>
                <button type="submit" class="btn btn-primary">Valider</button>
                <a class="d-inline-block ps-3" href="voirFichesFraisEnAttenteValidation&mois=<?= $mois ?>">Retour liste</a>
            </div>
        </div>
        <div>
            
        </div>
    </form>
</section>