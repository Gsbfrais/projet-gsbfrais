<h1 class="container-lg text-center">Modifier l'utilisateur</h1>

<section class="container-lg col-lg-9 mb-5">
    <h2 class="text-info border-bottom border-info">Formulaire de modification</h2>

    <form action="modifierUtilisateur&idUtilisateur=<?= $utilisateur->id ?>" method="POST" class="mb-4">
        <input type="hidden" name="id" value="<?= $utilisateur->id ?>">

        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" class="form-control" id="nom" name="nom" value="<?= $utilisateur->nom ?>" required>
        </div>

        <div class="mb-3">
            <label for="prenom" class="form-label">Prénom</label>
            <input type="text" class="form-control" id="prenom" name="prenom" value="<?= $utilisateur->prenom ?>"
                   required>
        </div>

        <div class="mb-3">
            <label for="login" class="form-label">Login</label>
            <input type="text" class="form-control" id="login" name="login" value="<?= $utilisateur->login ?>" required>
        </div>

        <div class="mb-3">
            <label for="date_embauche" class="form-label">Date d'embauche</label>
            <input type="date" class="form-control" id="date_embauche" name="date_embauche"
                   value="<?= $utilisateur->date_embauche ?>" required>
        </div>

        <div class="mb-3">
            <label for="date_depart" class="form-label">Date de départ</label>
            <input type="date" class="form-control" id="date_depart" name="date_depart"
                   value="<?= $utilisateur->date_depart ?>">
        </div>

        <div class="mb-3">
            <label for="id_region" class="form-label">Région ID</label>
            <select class="form-select" id="id_region" name="id_region" required>
                <?php foreach ($regions as $region): ?>
                    <option value="<?= $region->id ?>" <?= $region->id == $utilisateur->id_region ? 'selected' : '' ?>>
                        <?= $region->nom ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="id_profil" class="form-label">Profil</label>
            <select class="form-select" id="id_profil" name="id_profil" required>
                <?php foreach ($profils as $profil) : ?>
                    <option value="<?= $profil->id ?>" <?= $profil->id == $utilisateur->id_profil ? 'selected' : '' ?>>
                        <?= $profil->nom ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="voirUtilisateurs" class="btn btn-secondary">Annuler</a>
    </form>
</section>
