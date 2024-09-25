<h1 class="container-lg text-center">Saisir mes frais forfaitisés pour la période <?= $periode ?></h1>

<section class="container-lg col-lg-7 mb-5">
<p class="text-warning"> Plafond kilometrique MAX: <?=$plafondKm ?></p>

</section>

<!-- LISTE DES FRAIS FORFAIT DÉJÀ SAISIS -->
<section class="container-lg col-lg-7 mb-5">
    <h2 class="text-info  border-bottom border-info">Descriptif des frais forfaitisés déjà saisis</h2>
    <div class=" row">
        <?php if (count($lesFraisForfait) > 0) : ?>
            <table class=" table mb-4 ">
                <tr class=" table-primary">
                    <th class="libelle">Categorie</th>
                    <th class="quantite">Quantité</th>
                    <th class="action">&nbsp;</th>
                </tr>
                <?php
                foreach ($lesFraisForfait as $unFraisForfait) :
                    $unCodeCategorie = $unFraisForfait->code_categorie;
                    $unLibelle = $unFraisForfait->libelle;
                    $uneQuantite = $unFraisForfait->quantite;
                ?>
                    <tr class="">
                        <td><?= $unCodeCategorie ?>&nbsp;<?= $unLibelle ?></td>
                        <td><?= $uneQuantite ?></td>
                        <td>
                            <a href="supprimerFraisForfait&codeCategorie=<?= $unCodeCategorie ?>" onclick="return confirm('Voulez-vous vraiment supprimer ce frais?');">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>


</section>

<!-- SAISIE DES FRAIS FORFAIT -->
<section class="container-lg col-lg-7 mb-5">
    <h2 class="text-info  border-bottom border-info">Nouveau frais forfaitisé</h2>

    <form action=" saisirFraisForfait" method="post" novalidate>

        <?php if (empty($errorMessage) == false) : ?>
            <div class="alert alert-dismissible alert-danger">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <?= $errorMessage ?>
            </div>
        <?php endif; ?>

        <div class="row mb-3">
            <label class="col-3 col-lg-2 col-form-label">Catégorie</label>
            <div class="col-9 col-lg-10">
                <select class="form-select" id="codeCategorie" name="codeCategorie">
                    <option value=""></option>
                    <?php foreach ($lesCategories as $uneCategorie) :
                        $codeCategorie = $uneCategorie->code;
                        $libelle = $codeCategorie . ' - ' . $uneCategorie->libelle;
                        if ($codeCategorie == $codeCategorieSelectionnee) :
                            echo '<option selected value="' . $codeCategorie . '">' . htmlspecialchars($libelle) . '</option>';
                        else :
                            echo '<option value="' . $codeCategorie . '">' . htmlspecialchars($libelle) . '</option>';
                        endif;
                    endforeach; ?>
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <label for="quantite" class="col-3 col-lg-2 col-form-label">Quantité</label>
            <div class="col-9 col-lg-10">
                <input type="text" class="form-control" id="quantite" name="quantite" value="<?php if (isset($quantite)) {
                                                                                                    echo $quantite;
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


