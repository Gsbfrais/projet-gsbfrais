<!DOCTYPE html>
<html>

<head>
    <title><?= $title ?></title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="../assets/images/favicon.png" type="image/png">
    <!-- Thème bootswach Slate (bootstrap 5) -->
    <link rel="stylesheet" href="<?= ROOT_URL ?>assets/css/bootswatch_slate.min.css">
    <!-- fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" type="text/css" href="<?= ROOT_URL ?>assets/css/gsb.css">
</head>

<body class="<?= $title ?>">

    <?php if (isset($_SESSION['idUtil'])) : ?>
        <div id="connected-user" class="d-flex align-items-center">
            <div>
                <?= $_SESSION['prenomUtil'] . "  " . $_SESSION['nomUtil']; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="navbar navbar-expand-lg fixed-top navbar-dark bg-primary">
        <div class="container-fluid">

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <?php if (isset($_SESSION['idUtil'])) : ?>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <a href="accueil" class="navbar-brand">Galaxy Swiss Bourdin</a>
                    <ul class="navbar-nav">
                        <?php if ($_SESSION['profilUtil'] == "visiteur médical") : ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Mes frais</a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="saisirFraisForfait">Saisie frais forfait</a>
                                    <a class="dropdown-item" href="saisirFraisHorsForfait">Saisie frais hors forfait</a>
                                    <a class="dropdown-item" href="voirFicheFrais" title="Consultation de mes fiches de frais">Voir mes fiches de frais</a>
                                </div>
                            </li>
                        <?php endif; ?>

                        <?php if ($_SESSION['profilUtil'] == "administrateur") : ?>
                            <li class="nav-item">
                                <a class="nav-link" href="" title="Gestion des utilisateurs">Gérer les utilisateurs</a>
                            </li>
                        <?php endif; ?>

                        <?php if ($_SESSION['profilUtil'] == "comptable") : ?>
                            <li class="nav-item">
                                <a class="nav-link" href="cloturerFichesFrais" title="Clôturer les fiches de frais">Clôturer les fiches de frais</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="voirFichesFraisEnAttenteValidation" title="Valider les fiches de frais">Valider les fiches de frais</a>
                            </li>
                        <?php endif; ?>

                        <?php if ($_SESSION['profilUtil'] == "délégué régional") : ?>
                            <li class="nav-item">
                                <a class="nav-link" href="voirFichesFraisRegion" title="Fiches frais des collaborateurs">Fiches frais des collaborateurs</a>
                            </li>
                        <?php endif; ?>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Mon compte</a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" a href="changerMotPasse" title="Se déconnecter">Changer mon mot de passe</a>
                                <a class="dropdown-item" a href="logout" title="Se déconnecter">Déconnexion</a>
                            </div>
                        </li>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>


    <main class="container-lg">
        <?= $content ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>





</body>

</html>