<h1 class="container-lg col-lg-7">Mes fiches de frais</h1>

<!-- SELECTION DU MOIS -->
<section class="container-lg col-lg-7 mb-5">
    <h2 class="text-info border-bottom border-info"">Mois à afficher</h2>
    <form class=" row align-items-center" action="voirFicheFrais" method="POST">
        <div class="col-auto">
            <select class="form-select" id="lstMois" name="lstMois">
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

<?php if (isset($_POST['lstMois']) == true ) : ?>
    <!-- FRAIS FORFAIT -->
    <section class="container-lg col-lg-7 mb-5">
        <h2 class="text-info border-bottom border-info"">Eléments forfaitisés <small>(exprimés en « quantité »)</small></h2>
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

            <p class="mt-5 text-warning">
                Statut de la fiche de frais : <?= $laFicheFrais->code_statut ?><br>
                Montant validé : <?= $laFicheFrais->montant_valide ?> €
            </p>
    </section>

    <!-- FRAIS HORS FORFAIT -->
    <section class="container-lg col-lg-7 mb-5">
        <h2 class="text-info border-bottom border-info">
            Descriptif des éléments hors forfait - <small><?= $laFicheFrais->nb_justificatifs ?> justificatifs reçus -</small>
        </h2>
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

<?php endif; ?>