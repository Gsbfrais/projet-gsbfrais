<section class="container-lg col-lg-7 mb-5">
    <h2 class="text-info mt-5">Modifier votre mot de passe</h2>

    <?php if ($changed == false): ?>
        <form method="post" action="changerMotPasse" novalidate>

            <?php if (empty($errorMessage) == false): ?>
                <div class="alert alert-dismissible alert-danger">
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    <?= $errorMessage ?>
                </div>
            <?php endif; ?>

            <div class="row mb-3">
                <label for="currentPassword" class="col-4 col-lg-3 col-form-label">Mot de passe actuel</label>
                <div class="col-8 col-lg-9">
                    <input type="password" class="form-control" id="currentPassword" name="currentPassword" required />
                </div>
            </div>

            <div class="row mb-3">
                <label for="new-password" class="col-4 col-lg-3 col-form-label">Nouveau mot de passe</label>
                <div class="col-8 col-lg-9">
                    <input type="password" class="form-control" id="newPassword" name="newPassword" required />
                </div>
            </div>

            <div class="row mb-3">
                <label for="confirm-password" class="col-4 col-lg-3 col-form-label">Confirmer le nouveau mot de passe</label>
                <div class="col-8 col-lg-9">
                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required />
                </div>
            </div>

            <div class="row mb-3">
                <div class="offset-3 offset-md-3">
                    <button type="submit" class="btn btn-success">Modifier le mot de passe</button>
                </div>
            </div>
        </form>
    <?php else: ?>
        <div class="alert alert-dismissible alert-primary">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            Votre mot de passe a été modifié
        </div>
    <?php endif; ?>
</section>