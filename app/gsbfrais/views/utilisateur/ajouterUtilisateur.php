<h1 class="container-lg text-center">Ajouter l'utilisateur</h1>

<section class="container-lg col-lg-9 mb-5">
    <h2 class="text-info border-bottom border-info">Formulaire d'ajout</h2>

    <?php if (empty($errorMessage) == false): ?>
        <div class="alert alert-dismissible alert-danger">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            <?= $errorMessage ?>
        </div>
    <?php endif; ?>

    <form action="ajouterUtilisateur" method="POST" class="mb-4" novalidate>
        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" class="form-control" id="nom" name="nom" required>
        </div>

        <div class="mb-3">
            <label for="prenom" class="form-label">Prénom</label>
            <input type="text" class="form-control" id="prenom" name="prenom" required>
        </div>

        <div class="mb-3">
            <label for="login" class="form-label">Login</label>
            <input type="text" class="form-control" id="login" name="login" required>
        </div>

        <div class="mb-3">
            <label for="date_embauche" class="form-label">Date d'embauche</label>
            <input type="date" class="form-control" id="date_embauche" name="date_embauche" required>
        </div>

        <div class="mb-3">
            <label for="date_depart" class="form-label">Date de départ</label>
            <input type="date" class="form-control" id="date_depart" name="date_depart">
        </div>

        <div class="mb-3">
            <label for="id_region" class="form-label">Région ID</label>
            <select class="form-select" id="id_region" name="id_region" required>
                <?php foreach ($regions as $region): ?>
                    <option value="<?= $region->id ?>">
                        <?= $region->nom ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="id_profil" class="form-label">Profil</label>
            <select class="form-select" id="id_profil" name="id_profil" required>
                <?php foreach ($profils as $profil) : ?>
                    <option value="<?= $profil->id ?>">
                        <?= $profil->nom ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Ajouter</button>
        <a href="voirUtilisateurs" class="btn btn-secondary">Annuler</a>
    </form>
</section>
