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

            <body>
            <div class="container">
                <br>
                <h2>Formulaire 3/3 </h2>

                <br>
                <?php
                    include('include/cnxMysql.php');            // On jouint nos fichiers php necessaire à la connection à la bdd, à la génération de classe, et à l'appelle de nos fonctions
                    include('include/class.php');
                    include('include/fonction.php');

                    $nom_model = filter_var($_POST["nom_model"],FILTER_SANITIZE_STRING);
                    $nom_table = filter_var($_POST["nom_table"],FILTER_SANITIZE_STRING);
                    $nom_fichier = filter_var($_POST["nom_fichier"],FILTER_SANITIZE_STRING);
                    $nb_enregistrement = filter_var($_POST['nb_enregistrement'],FILTER_SANITIZE_NUMBER_INT);
                    $type_fichier = $_POST['type_sortie'];

                    
                    if(!formulaire_nettoyage($nb_enregistrement)){                // On nettoit les informations rentrés par l'utilisateurs si un problème est rencontré en envoie un message d'erreur
                        erreur_champ('Erreur: valeur de l\'un des champs remplis est incorrecte (attention, il s\'agit peut être d\'un espace dans une var de type time, date, datetime)');
                    }elseif(!formulaire_teste_champ()){         // On teste si l'un des champs ne possède un nom déjà utilisé, si c'est le cas un message d'erreur est envoyé
                        erreur_champ('Erreur: vos champs ne peuvent avoir le même nom');
                    } else {
                        
                        $date = date("Y-m-d H:i:s");            // On récupère la date du jour
                        $libelle = $nom_model.$date;            // On l'utilise pour formé la clé du model

                        if(isset($_POST['bool_conserve'])){     // Si l'utilisateur souhaite conservé le modele, on initialise à true la valuer de cette variable
                            $bool_conserve = true;
                        } else {
                            $bool_conserve = false;
                        }

                        if ($bool_conserve){                    //On ajoute à la table modele les données 
                                
                            $requete = $cnxPDO->prepare("INSERT INTO modele(libelle, nom_fichier, nom_table, date_creation) VALUES (?,?,?,?)");
                            $requete->execute([$libelle, $nom_fichier, $nom_table, $date]);

                        }

                        $var = array("tiny_int" => array(),     // On créé un tableau var contenant 9 autres tableaux ayant pour clé le type des objets qu'il vont contenir 
                                    "int" => array(),
                                    "double" => array(),
                                    "char" => array(),
                                    "varchar" => array(),
                                    "bool" => array(),
                                    "date" => array(),
                                    "time" => array(),
                                    "datetime" => array(),
                                    );

                        for($j = 0; $j < 9 ; $j++){
                            $i = 0;
                            while(isset($_POST[$j.$i.'_nom'])){ // On teste si le nom du champ du type associé  à la variable j existe, sinon au passe au type suivant 
                                switch($j){

                                                                // Pour chacun des champs envoyé en POST on créé un objet du type du champ contenant toutes les informations relatifs à ce champ, ue l'on insert dans
                                                                // le tableau tab, une fois le champ créé on traite les valeurs de manière sécurisé si l'utilisateur souhaite les conservés

                                    case 0:
                                        $var[$j][$i] = new TinyInt ($_POST[$j.$i.'_nom'],$_POST[$j.$i.'_valeur_haute'],$_POST[$j.$i.'_valeur_basse']);
                                        if($bool_conserve){           
                                            $req = $cnxPDO->prepare("INSERT INTO champ (nom_champ,val_min_nb,val_max_nb,libelle, type_champ) VALUES(?,?,?,?,?)");
                                            $req->execute([$var[$j][$i]->getNom(),$var[$j][$i]->getValeurBasse(),$var[$j][$i]->getValeurHaute(),$libelle, "Tinyint"]);
                                        }
                                        break;
                                    case 1:
                                        $var[$j][$i] = new Entier ($_POST[$j.$i.'_nom'],$_POST[$j.$i.'_valeur_haute'],$_POST[$j.$i.'_valeur_basse']);
                                        if($bool_conserve){
                                            $req = $cnxPDO->prepare("INSERT INTO champ (nom_champ,val_min_nb,val_max_nb,libelle, type_champ) VALUES(?,?,?,?,?)");
                                            $req->execute([$var[$j][$i]->getNom(),$var[$j][$i]->getValeurBasse(),$var[$j][$i]->getValeurHaute(),$libelle, "Integer"]);
                                        }
                                        break;
                                    case 2:
                                        $var[$j][$i] = new Double($_POST[$j.$i.'_nom'],$_POST[$j.$i.'_valeur_haute'],$_POST[$j.$i.'_valeur_basse']);
                                        if($bool_conserve){
                                            $req = $cnxPDO->prepare("INSERT INTO champ (nom_champ,val_min_nb,val_max_nb,libelle, type_champ) VALUES(?,?,?,?,?)");
                                            $req->execute([$var[$j][$i]->getNom(),$var[$j][$i]->getValeurBasse(),$var[$j][$i]->getValeurHaute(),$libelle, "Double"]);
                                        }
                                        break;
                                    case 3:
                                        $var[$j][$i] = new Char($_POST[$j.$i.'_nom'], $_POST[$j.$i.'_taille'], $_POST[$j.$i.'_fichier']);
                                        if($bool_conserve){
                                            $req = $cnxPDO->prepare("INSERT INTO champ (nom_champ,longueur,libelle, type_champ, fichier) VALUES(?,?,?,?,?)");
                                            $req->execute([$var[$j][$i]->getNom(),$var[$j][$i]->getTailleChamp(),$libelle, "Char", $var[$j][$i]->getFichier()]);
                                        }
                                        break;
                                    case 4:
                                        $var[$j][$i] = new Varchar($_POST[$j.$i.'_nom'], $_POST[$j.$i.'_taille'], $_POST[$j.$i.'_fichier']);
                                        if($bool_conserve){
                                            $req = $cnxPDO->prepare("INSERT INTO champ (nom_champ,longueur,libelle, type_champ, fichier) VALUES(?,?,?,?,?)");
                                            $req->execute([$var[$j][$i]->getNom(),$var[$j][$i]->getTailleChamp(),$libelle, "Varchar", $var[$j][$i]->getFichier()]);
                                        }
                                        break;
                                    case 5:
                                        $var[$j][$i] = new boulean ($_POST[$j.$i.'_nom']);
                                        if($bool_conserve){
                                            $req = $cnxPDO->prepare("INSERT INTO champ (nom_champ,libelle, type_champ) VALUES(?,?,?)");
                                            $req->execute([$var[$j][$i]->getNom(),$libelle, "Boolean"]);
                                        }
                                        break;
                                    case 6:
                                        $date_haute = new datetime($_POST[$j.$i.'_valeur_haute']);
                                        $date_basse = new datetime($_POST[$j.$i.'_valeur_basse']);
                                        $var[$j][$i] = new Date ($_POST[$j.$i.'_nom'], $date_haute, $date_basse);
                                        if($bool_conserve){
                                            $req = $cnxPDO->prepare("INSERT INTO champ (nom_champ,val_min_date,val_max_date,libelle, type_champ) VALUES(?,?,?,?,?)");
                                            $req->execute([$var[$j][$i]->getNom(),$var[$j][$i]->getValeurBasse()->format('Y-m-d H:i:s'),$var[$j][$i]->getValeurHaute()->format('Y-m-d H:i:sP'),$libelle, "Date"]);
                                        }
                                        break;
                                    case 7:

                                        $date_haute = new datetime($_POST[$j.$i.'_valeur_haute']);
                                        $date_basse = new datetime($_POST[$j.$i.'_valeur_basse']);
                                        $var[$j][$i] = new Time ($_POST[$j.$i.'_nom'], $date_haute, $date_basse);
                                        if($bool_conserve){
                                            $req = $cnxPDO->prepare("INSERT INTO champ (nom_champ,val_min_date,val_max_date,libelle, type_champ) VALUES(?,?,?,?,?)");
                                            $req->execute([$var[$j][$i]->getNom(),$var[$j][$i]->getValeurBasse()->format('H:i:s'),$var[$j][$i]->getValeurHaute()->format('Y-m-d H:i:sP'),$libelle, "Time"]);
                                        }
                                        break;
                                    case 8:

                                        $date_haute = new datetime($_POST[$j.$i.'_valeur_haute']);
                                        $date_basse = new datetime($_POST[$j.$i.'_valeur_basse']);
                                        $var[$j][$i] = new date_time ($_POST[$j.$i.'_nom'], $date_haute, $date_basse);
                                        if($bool_conserve){
                                            $req = $cnxPDO->prepare("INSERT INTO champ (nom_champ,val_min_date,val_max_date,libelle, type_champ) VALUES(?,?,?,?,?)");
                                            $req->execute([$var[$j][$i]->getNom(),$var[$j][$i]->getValeurBasse()->format('Y-m-d H:i:s'),$var[$j][$i]->getValeurHaute()->format('Y-m-d H:i:sP'),$libelle, "DateTimes"]);
                                        }
                                        break;
                                }
                                $i++;
                            }
                                
                        }

                    
                ?>
                
                <?php
                $nom_fichier = $nom_fichier.$type_fichier;
                $fichier = fopen($nom_fichier, 'w+');
                if($type_fichier == '.sql'){
                    for($ligne = 0; $ligne < $nb_enregistrement; $ligne++){
                        $ligne_fichier = "INSERT INTO ".$nom_table." (";
                        for($j = 0; $j < 9 ; $j++){
                            $i = 0;
                            while(isset($_POST[$j.$i.'_nom'])){
                                $ligne_fichier .= $var[$j][$i]->getNom();
                                $val_suiv = $i + 1;
                                $tab_suiv = $j + 1;
                                if(isset($_POST[$j.$val_suiv.'_nom'])){
                                    $ligne_fichier .= ', ';
                                }else if(isset($_POST[$tab_suiv.'0'.'_nom'])){
                                    $ligne_fichier .= ', ';
                                }
                            $i++;
                            }
                        }
                        $ligne_fichier .= ") VALUES (";
                            for($j = 0; $j < 9 ; $j++){
                                $i = 0;
                                while(isset($_POST[$j.$i.'_nom'])){
                                    $ligne_fichier .= $var[$j][$i]->getRandomVal();
                                    $val_suiv = $i + 1;
                                    $tab_suiv = $j + 1;
                                    if(isset($_POST[$j.$val_suiv.'_nom'])){
                                        $ligne_fichier .= ', ';
                                    }else if(isset($_POST[$tab_suiv.'0'.'_nom'])){
                                        $ligne_fichier .= ', ';
                                    }
                                $i++;
                                }
                            }
                        $ligne_fichier .= ");\n";
                        fwrite($fichier, $ligne_fichier);                    
                    }
                }else{
                    $tab = array();
                    $tab_nom = array();
                    for($j = 0; $j < 9 ; $j++){
                        $i = 0;
                        while(isset($_POST[$j.$i.'_nom'])){
                            array_push($tab_nom, $var[$j][$i]->getNom());
                            $i++;
                        }
                    }
                    array_push($tab, $tab_nom);

                    for($ligne=0; $ligne < $nb_enregistrement ;$ligne++){
                        $tab_temp = array();
                        for($j = 0; $j < 9 ; $j++){
                            $i = 0;
                            while(isset($_POST[$j.$i.'_nom'])){
                                array_push($tab_temp, $var[$j][$i]->getRandomVal());
                                $i++;
                            }
                        }
                    array_push($tab, $tab_temp);
                    }
                    foreach($tab as $value){
                        fputcsv($fichier, $value);
                    }
                }

                fclose($fichier);
                echo '<div class="alert alert-danger" role="alert">/!\ Attention si vous avez choisi le .csv pour votre fichier, ouvrez votre document avec libre office et non exel /!\ </div>';
                echo '<div class="text-center">
                    <br><br>
                    <h2>télécharger votre fichier</h2>
                    <br><br><br><br>
                    <a class="btn btn-primary btn-block" href="'.$nom_fichier.'">
                        <i class="fa fa-download fa-lg"></i>
                    </a></div>';
            }
                ?>

                
        </div>
        <?php require "html/footer.html"; ?>   
        </body>
</html>