<h1 class="container-lg text-center">Saisir mes frais hors forfait pour la période <?= $periode ?></h1>

<!-- LISTE DES FRAIS HORS FORFAIT DÉJÀ SAISIS -->
<section class="container-lg col-lg-7 mb-5">
    <h2 class="text-info  border-bottom border-info">Descriptif des éléments hors forfait</h2>
    <div class=" row">
        <?php if (count($lesFraisHorsForfait) > 0) : ?>
            <table class=" table mb-4 ">
                <tr class=" table-primary">
                    <th class="date">Date</th>
                    <th class="libelle">Libellé</th>
                    <th class="montant">Montant</th>
                    <th class="action">&nbsp;</th>
                </tr>
                <?php
                foreach ($lesFraisHorsForfait as $unFraisHorsForfait) :
                    $unLibelle = $unFraisHorsForfait->libelle;
                    $uneDate = $unFraisHorsForfait->date;
                    $unMontant = $unFraisHorsForfait->montant;
                    $num = $unFraisHorsForfait->num;
                ?>
                    <tr class="">
                        <td> <?php echo $uneDate ?></td>
                        <td><?php echo $unLibelle ?></td>
                        <td><?php echo $unMontant ?></td>
                        <td>
                            <a href="supprimerFraisHorsForfait&numFrais=<?php echo $num ?>" onclick="return confirm('Voulez-vous vraiment supprimer ce frais?');">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>


</section>

<!-- SAISIE DES FRAIS HORS FORFAIT -->
<section class="container-lg col-lg-7 mb-5">
    <h2 class="text-info  border-bottom border-info">Nouvel élément hors forfait</h2>

    <form action=" saisirFraisHorsForfait" method="post" novalidate>

        <?php if (empty($errorMessage) == false) : ?>
            <div class="alert alert-dismissible alert-danger">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <?= $errorMessage ?>
            </div>
        <?php endif; ?>

        <div class="row mb-3">
            <label for="dateFrais" class="col-3 col-lg-2 col-form-label">Date</label>
            <div class="col-9 col-lg-10">
                <input type="date" class="form-control" id="dateFrais" name="dateFrais" value="<?php if (isset($dateFrais)) {
                                                                                                    echo $dateFrais;
                                                                                                } ?>" required>
            </div>
        </div>

        <div class="row mb-3">
            <label for="libelle" class="col-3 col-lg-2 col-form-label">Libellé</label>
            <div class="col-9 col-lg-10">
                <input type="text" class="form-control" id="libelle" name="libelle" value="<?php if (isset($libelle)) {
                                                                                                echo htmlspecialchars($libelle);
                                                                                            } ?>" required>
            </div>
        </div>

        <div class="row mb-3">
            <label for="montant" class="col-3 col-lg-2 col-form-label">Montant</label>
            <div class="col-9 col-lg-10">
                <input type="text" class="form-control" id="montant" name="montant" value="<?php if (isset($montant)) {
                                                                                                echo $montant;
                                                                                            } ?>" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="offset-3 offset-md-2 col-9 col-lg-10">
                <button type="reset" class="btn btn-primary">Effacer</button>
                <button type="submit" class="btn btn-primary">Valider</button>
            </div>
        </div>
    </form>
</section>