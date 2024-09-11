<h1 class="container-lg col-lg-7">Clôture des fiches de frais</h1>

<section class="container-lg col-lg-7 mb-5">

    <h2 class="text-info">Période concernée : <?= $periode ?></h2>
    
    <form method="post" action="cloturerFichesFrais">

        <?php if (empty($infoMessage) == false): ?>
            <div class="alert alert-dismissible alert-success">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <?= $infoMessage ?>
            </div>
        <?php endif; ?>

        <div class="row mb-3">
            <div class="col-3">
                <button type="submit" class="btn btn-primary" name="ok" value="cloture">Clôturer</button>
            </div>
        </div>
    </form>
</section>