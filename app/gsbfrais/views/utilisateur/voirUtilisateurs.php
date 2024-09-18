<h1 class="container-lg text-center"><?= $title ?></h1>

<!-- LISTE DES UTILISATEURS -->
<section class="container-lg col-lg-9 mb-5">
    <h2 class="text-info  border-bottom border-info">Liste des utilisateurs</h2>
    <div class=" row">
        <?php if (count($utilisateurs) > 0) : ?>
            <table class=" table mb-4 ">
                <tr class=" table-primary">
                    <th class="id">ID</th>
                    <th class="nom">Nom</th>
                    <th class="prenom">Prenom</th>
                    <th class="login">Login</th>
                    <th class="date_embauche">Date d'mbauche</th>
                    <th class="date_depart">Date départ</th>
                    <th class="region">Region ID</th>
                    <th class="profil">Profil</th>
                    <th></th>
                    <th></th>
                </tr>
                <?php
                foreach ($utilisateurs as $utilisateur) : ?>
                    <tr class="">
                        <td><?= $utilisateur->id ?></td>
                        <td><?= $utilisateur->nom ?></td>
                        <td><?= $utilisateur->prenom ?></td>
                        <td><?= $utilisateur->login ?></td>
                        <td><?= $utilisateur->date_embauche ?></td>
                        <td><?= $utilisateur->date_depart ?></td>
                        <td><?= $utilisateur->id_region ?></td>
                        <?php foreach ($profils as $profil) : ?>
                            <?php if ($profil->id == $utilisateur->id_profil) : ?>
                                <td><?= $profil->nom ?></td>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <td>
                            <a href="modifierUtilisateur&idUtilisateur=<?= $utilisateur->id ?>">
                                <i class="fas fa-pen"></i>
                            </a>
                        </td>
                        <td>
                            <a href="supprimerUtilisateur&idUtilisateur=<?= $utilisateur->id ?>"
                               onclick="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?');">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>


</section>