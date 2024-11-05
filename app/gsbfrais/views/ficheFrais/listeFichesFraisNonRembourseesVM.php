<h1 class="container-lg col-lg-7">Fiches Frais Non Remboursées</h1>

<!-- Liste des fiches de frais du mois sélectionné -->

<?php if (count($lesFichesFrais) == 0) : ?>
    <p class="container-lg col-lg-10 text-warning ">Pas de fiche frais non remboursées<?= $periode ?></p>
<?php else : ?>
    <!-- Détail des fiches de frais non remboursées -->
    <section class="container-lg col-lg-10 mb-5">
        <h2 class="text-info border-bottom border-info">Fiches Frais Non Remboursées</h2>
        <table class=" table">
            <tr>
                <th>Période</th>
                <th>Statut</th>
                <th>Nb justificatifs</th>
                <th>Montant validé</th>
                <th>Montant frais forfait</th>
                <th>Montant frais hors forfait</th>
                <th>Date dernière modification</th>
            </tr>
            <?php foreach ($lesFichesFrais as $fiche) : ?>
                <tr class="table-primary">
                    <td><?= $fiche->mois ?></td>
                    <td><?= $fiche->code_statut ?></td>
                    <td><?= $fiche->nb_justificatifs ?></td>
                    <td><?= $fiche->montant_valide ?></td>
                    <td><?= $fiche->montantFraisForfait ?></td>
                    <td><?= $fiche->montantFraisHorsForfait ?></td>
                    <td><?= $fiche->date_modif ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>
<?php endif; ?>