<h1 class="container-lg text-center">Fiche frais <?= $laFiche->nom . ' ' .  $laFiche->prenom ?> - <?= $periode ?></h1>

<!-- Détail des frais forfait -->
<section class="container-lg col-lg-7 mb-5">
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
            <p>Montant validé</p>
        </div>
        <div>
            <p>: <?= number_format($laFiche->montantFraisForfait, 2) ?> €</p>
            <p>: <?= $laFiche->libelleStatutFiche ?></p>
            <p>: <?= number_format($laFiche->montantFraisForfait + $laFiche->montantFraisHorsForfait, 2) ?> €</p>
            <p>: <?= number_format($laFiche->montantFraisForfait, 2) ?> €</p>
            <p>: <?= number_format($laFiche->montant_valide, 2) ?> €</p>
        </div>
    </div>
    </table>
</section>

<!-- Validation -->
<section class="container-lg col-lg-7 mb-5">
    <div class="row mb-3">
        <div class="offset-3 offset-md-3">
            <a class="d-inline-block ps-3" href="voirFichesFraisRegion&mois=<?= $mois ?>">Retour liste</a>
        </div>
    </div>
</section>