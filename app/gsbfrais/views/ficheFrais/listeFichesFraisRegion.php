<h1 class="container-lg col-lg-7">Fiches frais de la région</h1>

<!-- SELECTION DU MOIS -->

<section class="container-lg col-lg-10 mb-5">
    <h2 class="text-info border-bottom border-info"">Mois à afficher</h2>
    <form class=" row align-items-center" action="voirFichesFraisRegion" method="POST">
        <div class="col-auto">
            <select class="form-select" id="lstMois" name="lstMois">
            <option value=""></option>
                <?php foreach ($lesMois as $unMois) :
                    $mois = $unMois->mois;
                    $libelle = $unMois->libelle;
                    if ($mois == $moisSelectionne) :
                        echo '<option selected value="' . $mois . '">' . htmlspecialchars($libelle) . '</option>';
                    else :
                        echo '<option value="' . $mois . '">' . htmlspecialchars($libelle) . '</option>';
                    endif;
                endforeach; ?>
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Ok</button>
        </div>
        </form>
</section>

<!-- Liste des fiches de frais du mois sélectionné -->
<?php if (empty($moisSelectionne) == false) : ?>

    <?php if (count($lesFichesFrais) == 0) : ?>
        <p class="container-lg col-lg-10 text-warning ">Plus aucune fiche à valider pour la période  <?= $periode ?></p>
    <?php else : ?>
        <!-- Détail des frais forfait -->
        <section class="container-lg col-lg-10 mb-5">
            <h2 class="text-info border-bottom border-info"">Fiches frais en attente de validation</h2>
        <table class=" table">
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Nb justificatifs</th>
                    <th>Date dernière modification</th>
                    <th>Montant frais forfait</th>
                    <th>Montant frais hors forfait</th>
                    <th>Détail</th>
                </tr>
                <?php foreach ($lesFichesFrais as $fiche) : ?>
                    <tr class="table-primary">
                        <td><?= htmlspecialchars($fiche->nom) ?></td>
                        <td><?= htmlspecialchars($fiche->prenom) ?></td>
                        <td><?= $fiche->nb_justificatifs ?></td>
                        <td><?= $fiche->date_modif ?></td>
                        <td><?= $fiche->montant_forfait ?></td>
                        <td><?= $fiche->montant_horsforfait ?></td>
                        <td>
                            <a href="voirDetailFicheFrais&idVisiteur=<?= $fiche->id_visiteur ?>&mois=<?= $fiche->mois ?>">
                            <i class="far fa-eye"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </table>
        </section>
    <?php endif; ?>
<?php endif; ?>