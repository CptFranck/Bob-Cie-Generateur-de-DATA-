<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title> Projet PHP </title>

        <!-- Meta tags -->
        
        <meta charset="utf-8">
        <meta name="author" content="François CAILLET">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- CSS Styles -->       

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css"
            integrity="sha256-+N4/V/SbAFiW1MPBCXnfnP9QSN3+Keu+NlB+0ev/YKQ=" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap.min.css"
            integrity="sha256-L/W5Wfqfa0sdBNIKN9cG6QA5F2qx4qICmU2VgLruv9Y=" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" 
            integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" 
            integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" 
            integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

        
        <style>
            html {height: 100%;}
            body {
            min-height: 100%;
            margin: 0;
            padding: 0;}
            body {position: relative;}
            footer {position: absolute; bottom: 0;}
            body {position: relative;}
            footer {position: absolute; bottom: 0; left: 0; right: 0}
        </style>

    </head>

    

    <body>  
        <?php require "html/header.html"; ?>
        <div class="container">
            
            <br>
            <h2>Formulaire 2/3 </h2>
            <br>

            <form action="formulaire_p3.php" method="post"> <!-- formulaire complexe pour obtenir les informations complémentaires à chacun des champs, le nombre d'enregistrement à générer, -->
                <?php                                       /* le nom du fichier en sortie, et son extension toujours en methode POST*/

                include('include/cnxMysql.php');            // On jouint nos fichiers php necessaire à la connection à la bdd, à la génération de classe, et à l'appelle de nos fonctions
                include('include/class.php');
                include('include/fonction.php');

                $methode = GET_ou_POST();                   // On teste si les valeurs envoyées sont en POST ou en GET

                if($methode == "POST"){

                    $valide = formulaire_p1_valide();       // Si aucun champ du model n'est remplit alors la fonction retourne false

                }

                if ($methode == "GET"){                     // Si la methode d'envoie est GET, on récupère l'id transmis, puis via une requete à la bdd on récupère les informations relatifs au model
                
                    $libelle = $_GET["id"];                   
                    $reponse = $cnxPDO->prepare('SELECT * FROM modele WHERE libelle = ?;');
                    $reponse->execute(array($libelle));
                    
                    $donnees = $reponse->fetch();                   
                    $nom_table = $donnees['nom_table'];
                    $nom_fichier = $donnees['nom_fichier'];
                    $nom_model = "";
                    $valide = true;
                                                            // Une fois les informations récupérés, on effectue un 2e requête pour initialiser le tableau var qui va contenir les informations liés aux différents champs précédemment générés
                    $var = recuperation_donnee_GET($libelle,$cnxPDO); 
                    
                } else {                                    // Sinon on filtre les valeurs des inputS de la pages précédentes (nom et model)
         
                    $nom_model = filter_var($_POST["nom_model"],FILTER_SANITIZE_STRING);
                    $nom_table = filter_var($_POST["nom_table"],FILTER_SANITIZE_STRING);
                    $var = null;
                    $nom_fichier = null;

                }
                                                            // Si le formulaire précédent est invalide affiche
                if(!$valide){

                    erreur_champ('Erreur: Aucun champ selectioné');

                }else{
                                                            // Sinon le formulaire précédent est valide on affiche l'entête de la page
                    affichage_entete($nom_model,$nom_table);
                                                            // On continue en créant un tableau tab;
                    $tab = array("nb_tiny_int",
                        "nb_int",
                        "nb_double",
                        "nb_char",
                        "nb_varchar",
                        "nb_bool",
                        "nb_date",
                        "nb_time",
                        "nb_datetime",
                        );
                    
                    echo '<div class="form-row">';

                    for($j = 0; $j< 9 ; $j++){              // boucle for dont le but et de gérer l'affichage dynamique des champs

                        if($methode == "POST"){
                            if (isset($_POST[$tab[$j]])){
                                $k = $_POST[$tab[$j]];      // k est ici le nombre champ du même type récupéré par la methode POST
                            } else {
                                $k = 0;
                            }                                    
                        } elseif($methode == "GET"){
                            $k = sizeof($var[$tab[$j]]);    // idem, à la différence que k est la taille du tableau qui contient les élémentsS de même type
                        }
                        
                        for($i = 0; $i < $k; $i++){         //Pour chaque champ on affiche l'input correspondant
                            
                            if($j == 0){
                                
                                case_tiny_int_int_double ($methode, $i, $j, $var[$tab[$j]][$i], "Tiny_Int");

                            }elseif($j == 1){

                                case_tiny_int_int_double ($methode,$i,$j, $var[$tab[$j]][$i], "Int");

                            }elseif($j == 2){                       
                                
                                case_tiny_int_int_double ($methode, $i, $j, $var[$tab[$j]][$i], "Double");

                            }elseif($j == 3){
                                
                                case_char_varchar($methode, $i, $j, $var[$tab[$j]][$i], "Char");
                        
                            }elseif($j == 4){
                                
                                case_char_varchar($methode, $i, $j, $var[$tab[$j]][$i], "Varchar");
                            
                            }elseif($j == 5){
                                
                                case_bool ($methode, $i, $j, $var[$tab[$j]][$i]);

                            }elseif($j == 6){ 
                                
                                case_time_date_datetime($methode, $i, $j, $var[$tab[$j]][$i], "Date");

                            }elseif($j == 7){
                               
                                case_time_date_datetime($methode, $i, $j, $var[$tab[$j]][$i], "Time");
                            
                            }elseif($j == 8){

                                case_time_date_datetime($methode, $i, $j, $var[$tab[$j]][$i], "DateTime");
                            }
                        }
                    }

                    echo "</div>";
                    affichage_bas($nom_fichier, $methode);

                }?> 
            </form>    
        </div>
        <?php require "html/footer.html"; ?>
    </body>          
</html>