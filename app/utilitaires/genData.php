<?php
include("include/fct.inc.php");

echo "Programme d'actualisation des lignes des tables,
cette mise à jour peut prendre plusieurs minutes...<br/><br/>\n";
flush();

//============== PARAMETRES A MODIFIER ==============
/* Paramètres de connexion à la BDD */
$serveur   = 'mysql:host=database:3306';
$bdd       = 'dbname=gsbfrais';
$user      = 'USR_APPLI_GSBFRAIS' ;
$mdp       = '@p05plGF!!ytgr47' ;

/* Détermine le mois (AAAAMM) pour début génération des fiches de frais 
   Par défaut, 4 mois d'historique ( + le mois courant) ==> à modifier éventuellement 
*/
   $nbMoisHistorique = 4;

   $nbMoisATraiter = $nbMoisHistorique + 1;
   $an = date("Y");
   $mm  = date("m");
   if ($mm <= $nbMoisATraiter) {
       $mm = 12 - $nbMoisATraiter + $mm;
       $an--;
   } else {
       $mm -= $nbMoisATraiter;
   }
   if ($mm < 10) {
      $mm = "0" . $mm;
  }

$moisDebut =  $an.$mm;

/* mot de passe pour les utilisateurs (sera crypté avec l'algorithme Blowfish (BCRYPT) */
$motPasseUtilisateurs = 'password';

//============== FIN PARAMÈTRES ==============

$pdo       = new PDO($serveur.';'.$bdd, $user, $mdp);
$pdo->query("SET CHARACTER SET utf8");

set_time_limit(0);
echo "DEBUT PERIODE : $moisDebut<br/><br/>\n";
flush();

sleep(2);
echo "- Suppression des fiches frais<br/>\n";
$nb = suppressionFichesFrais($pdo);
echo "&nbsp;&nbsp;&nbsp;Fiches frais supprimées<br/><br/>\n";
flush();

sleep(2);
echo "- Création fiches frais<br/>\n";
$nb = creationFichesFrais($pdo, $moisDebut);
echo "&nbsp;&nbsp;&nbsp;$nb fiches créées<br/><br/>\n";
flush();

sleep(2);
echo "- Création frais forfaitisés<br/>\n";
$nb = creationFraisForfait($pdo);
echo "&nbsp;&nbsp;&nbsp;$nb frais forfaitisés créés<br/><br/>\n";
flush();

sleep(2);
echo "- Création frais hors forfait<br/>\n";
$nb = creationFraisHorsForfait($pdo);
echo "&nbsp;&nbsp;&nbsp;$nb frais hors forfait créés<br/><br/>\n";

sleep(2);
echo "- Mise à jour des fiches frais<br/>\n";
$nb = majFicheFrais($pdo);
echo "&nbsp;&nbsp;&nbsp;$nb fiches frais modifiées<br/><br/>\n";
flush();

sleep(2);
echo "- Mise à jour du mot de passe des utilisateurs (ARGON2I)<br/>\n";
$nb = updateMdpVisiteur($pdo, $motPasseUtilisateurs);
echo "&nbsp;&nbsp;&nbsp;$nb utilisateurs modifiés<br/><br/>\n";
flush();

echo 'Terminé';