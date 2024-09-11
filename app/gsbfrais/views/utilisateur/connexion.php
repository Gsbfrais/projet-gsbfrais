<div class="mt-4 mb-5 container-lg col-lg-10 text-center"><img src="<?= ROOT_URL ?>assets/images/logo.png" alt="logo GSB" class="w-25"></div>

<section class="container-lg col-lg-7 mb-5">

    <h2 class="text-info">Identifiez-vous</h2>
    
    <form method="post" action="login">

        <?php if (empty($errorMessage) == false): ?>
            <div class="alert alert-dismissible alert-danger">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <?= $errorMessage ?>
            </div>
        <?php endif; ?>

        <div class="row mb-3">
            <label for="" class="col-4 col-lg-3 col-form-label">Login</label>
            <div class="col-8 col-lg-9">
                <input type="text" class="form-control" id=login" name="login" required autofocus />
            </div>
        </div>

        <div class="row mb-3">
            <label for="" class="col-4 col-lg-3 col-form-label">Mot de passe</label>
            <div class="col-8 col-lg-9">
                <input type="password" class="form-control" id=mdp" name="mdp" required />
            </div>
        </div>

        <div class="row mb-3">
            <div class="offset-3 offset-md-3">
                <button type="submit" class="btn btn-primary">Connexion</button>
            </div>
        </div>
    </form>
</section>