select nom, prenom, fichefrais.code_statut, fichefrais.date_modif,  
                    (select ifnull(sum(fraisforfait.quantite * categoriefraisforfait.prix_unitaire), 0)
                        from fraisforfait
                        join categoriefraisforfait on fraisforfait.code_categorie = categoriefraisforfait.code 
                        where fraisforfait.id_visiteur = fichefrais.id_visiteur
                        and fraisforfait.mois = fichefrais.mois) as montantFraisForfait,
                    (select ifnull(sum(fraishorsforfait.montant),0)
                        from fraishorsforfait 
                        where fraishorsforfait.id_visiteur = fichefrais.id_visiteur
                        and fraishorsforfait.mois = fichefrais.mois) as montantFraisHorsForfait,
                        fichefrais.nb_justificatifs, statutfichefrais.libelle as libelleStatutFiche, fichefrais.montant_valide
                from  fichefrais 
                join statutfichefrais on fichefrais.code_statut = statutfichefrais.code 
                join utilisateur on fichefrais.id_visiteur = utilisateur.id
              where fichefrais.id_visiteur =:id_visiteur and fichefrais.mois = :mois"