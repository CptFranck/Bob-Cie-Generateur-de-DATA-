<?php

//////////////////////////////////////
//         GET_ou_POST()            //
//////////////////////////////////////

function GET_ou_POST($string = "nom_model"){                     // renvoie la methode utilisé
    if (isset($_GET["click"])) {
        $methode = "GET";
        return $methode;
    } elseif ($_POST[$string]){
        $methode = "POST";
        return $methode;
    }
}

//////////////////////////////////////
//     formulaire_p1_valide         //
//////////////////////////////////////
                                            // renvoie true si au moins un champ a été selectionné dnas le formulaire p1
function formulaire_p1_valide(){
    if(!isset($_POST["nb_tiny_int"])){
        $_POST["nb_tiny_int"]= null;
    }
    if(!isset($_POST["nb_int"])){
        $_POST["nb_int"]= null;
    }
    if(!isset($_POST["nb_double"])){
        $_POST["nb_double"]= null;
    }
    if(!isset($_POST["nb_char"])){
        $_POST["nb_char"]= null;
    }
    if(!isset($_POST["nb_varchar"])){
        $_POST["nb_varchar"]= null;
    }
    if(!isset($_POST["nb_bool"])){
        $_POST["nb_bool"]= null;
    }
    if(!isset($_POST["nb_date"])){
        $_POST["nb_date"]= null;
    }
    if(!isset($_POST["nb_time"])){
        $_POST["nb_time"]= null;
    }
    if(!isset($_POST["nb_datetime"])){
        $_POST["nb_tiny_int"]= null;
    }
    if($_POST["nb_tiny_int"] == null && $_POST["nb_int"] == null && $_POST["nb_double"] == null && $_POST["nb_char"] == null && $_POST["nb_varchar"] == null && $_POST["nb_bool"] == null && $_POST["nb_date"] == null && $_POST["nb_time"] == null && $_POST["nb_datetime"] == null){
        return false;               
    }
    if(($_POST["nb_tiny_int"] < 0) || ($_POST["nb_int"] < 0) || ($_POST["nb_double"] < 0) || ($_POST["nb_char"] < 0) || ($_POST["nb_varchar"] < 0) || ($_POST["nb_bool"] < 0) || ($_POST["nb_date"] < 0) || ($_POST["nb_time"] < 0) ||  ($_POST["nb_datetime"] < 0) ){
        return false;               
    } else {
        return true;
    }
}

//////////////////////////////////////
//             erreur               //
//////////////////////////////////////
                                            // Affiche un message d'erreur contenue dans la chaine stringue
function erreur_champ($stringue, $retour = "formulaire_p1.php" ){ 
    $string =   '<div class="alert alert-danger" role="alert">'. $stringue .'</div><a class="btn btn-primary" href="'. $retour .'"role="button">Retour</a>';
    echo $string;                   
}

//////////////////////////////////////
//      récuperation_donnee_GET     //
//////////////////////////////////////

function recuperation_donnee_GET($libelle,$cnxPDO){

    $var = array("nb_tiny_int" => array(),  // Création d'un var contenant 9 autres tableaux ayant pour clé le type des objets qu'il vont contenir
        "nb_int" => array(),
        "nb_double" => array(),
        "nb_char" => array(),
        "nb_varchar" => array(),
        "nb_bool" => array(),
        "nb_date" => array(),
        "nb_time" => array(),
        "nb_datetime" => array(),
        );
                                            // On effectue un 2e requête pour initialiser le tableau var qui va contenir les informations liés aux différents champs précédemment générés grace au libelle (id du model)
        $reponse = $cnxPDO->prepare('SELECT * FROM champ a JOIN type_champ t ON t.type_champ = a.type_champ JOIN modele e ON e.libelle = a.libelle WHERE e.libelle =?;');
        $reponse->execute(array($libelle));
        
    $j = 0;
                    
    while($donnees = $reponse->fetch()){    // Pour chacun des champs récupéré en GET on créé un objet du type du champ contenant toutes les informations relatifs à ce champ, que l'on insert dans le tableau var

        switch($donnees['type_champ']){
            
            case "Tinyint":
                $objet = new TinyInt ($donnees['nom_champ'], $donnees['val_max_nb'], $donnees['val_min_nb']);
                array_push($var["nb_tiny_int"], $objet);
                break;

            case "Integer":
                $objet = new Entier ($donnees['nom_champ'], $donnees['val_max_nb'], $donnees['val_min_nb']);
                array_push($var["nb_int"], $objet);
                break;

            case "Double":
                $objet = new Double ($donnees['nom_champ'], $donnees['val_max_nb'], $donnees['val_min_nb']);
                array_push($var["nb_double"], $objet);
                break;
            
            case "Char":
                $objet = new Char ($donnees['nom_champ'], $donnees['longueur'], $donnees['fichier']);
                array_push($var["nb_char"], $objet);
                break;

            case "Varchar":
                $objet = new Varchar ($donnees['nom_champ'], $donnees['longueur'], $donnees['fichier']);
                array_push($var["nb_varchar"], $objet);
                break;

            case "Boolean":
                $objet = new boulean ($donnees['nom_champ']);
                array_push($var["nb_bool"], $objet);
                break;

            case "Date":
                $objet = new Date ($donnees['nom_champ'], substr($donnees['val_max_date'], 0, strpos($donnees['val_max_date']," ")), substr($donnees['val_min_date'], 0, strpos($donnees['val_max_date']," ")));
                array_push($var["nb_date"], $objet);
                break;

            case "Time":
                $objet = new Time ($donnees['nom_champ'], substr($donnees['val_max_date'], strpos($donnees['val_max_date']," ")+1), substr($donnees['val_min_date'], strpos($donnees['val_max_date']," ")+1));
                array_push($var["nb_time"], $objet);
                break;

            case "DateTimes":
                $objet = new date_time ($donnees['nom_champ'], $donnees['val_max_date'], $donnees['val_min_date']);
                array_push($var["nb_datetime"], $objet);
                break;
        }
        $j+=1;
    }

    return $var;
}

//////////////////////////////////////
//         affichage_entete         //
//////////////////////////////////////
                                            // On affiche l'entête du formulaire de la page 1
function affichage_entete($nom_model,$nom_table){
    $string = 
        '<div class="form-group">           
            <label>nom du model :</label>
            <input type="text" class="form-control" name="nom_model" required value="' . $nom_model . '">                    
            <br>
            <label>nom de la table:</label>
            <input type="text" class="form-control" name="nom_table" required value="' . $nom_table . '">
        </div>
        
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="model" name="bool_conserve">
            <label class="form-check-label" for="defaultCheck1">
                conserver le modèle
            </label>              
        </div>

        <br>';

        echo $string;
}

//////////////////////////////////////
//           affichage_bas          //
//////////////////////////////////////
                                        // On affiche la partie basse du formulaire de la page 2 que l'on remplie si l'on recharge les données déjà enregistrée ( si on utilise la methode GET)
function affichage_bas($nom_fichier,$methode){
    $string = '                
        <div class="form-group">
            <label>nombre d\'enregistrement :</label>
            <input type="text" class="form-control" required name="nb_enregistrement">
        </div>

        <div class="form-group">
            <label>nom du fichier :</label>
            <input type="text" required class="form-control" ';

    if($methode == "GET"){

        $string .='value="'. $nom_fichier .'"';

    };

    $string .=' name="nom_fichier">
        </div>
        
        <div class="form-group">
            <label>type de sortie :</label>
            <select class="browser-default custom-select" name="type_sortie">
            <option value=".csv">.csv</option>
            <option value=".sql">.sql</option>
            </select>
            <div class="form-group" id="sql"></div>
        </div>';
    
    echo $string;

    $string ='<div class="form-row">
                    <a class="btn btn-primary" ';
    if($methode == "GET"){
       
        $string .= 'href="modele_enregistre.php"';
    
    }else {
    
        $string .= 'href="formulaire_p1.php"';
    
    }
    
        $string .= 'role="button">Retour</a>
                    <button type="submit" class="btn btn-primary" href="formulaire_p3.php">Suivant</button>
                </div>';
    echo $string;
}

//////////////////////////////////////
//    case_tiny_int_int_double      //
//////////////////////////////////////
                                            // On affiche la partie du formulaire associé au type (int tiny_int doubloe) de champ que l'on remplie si l'on recharge les données déjà enregistrée ( si on utilise la methode GET)
function case_tiny_int_int_double ($methode, $i, $j, $objet, $type){
    $string = '
        <div class="form-row">
            <div class="container">
                <label>'.$type.' :</label>
                <br>
                <label>nom de la variable:</label>
                <input type="text" required class="form-control"';
    if($methode == "GET"){
        $string .='value="'. $objet->getNom() .'"';
    }

    $string .= ' name="'.$j.$i .'_nom">
                <label>valeur basse:</label>
                <input type="text" required class="form-control"';
    if($methode == "GET"){
        $string .='value="'. $objet->getValeurBasse().'"';
    }

    $string .= 'name="'.$j.$i.'_valeur_basse">
                <label>valeur haute:</label>
                <input type="text" required class="form-control"';
    if($methode == "GET"){
        $string .='value="'. $objet->getValeurHaute().'"';
    }

    $string .= ' name="'.$j.$i.'_valeur_haute">
            </div>
        </div>';

    echo $string;
}

//////////////////////////////////////
//         case_char_varchar        //
//////////////////////////////////////
                                            // On affiche la partie du formulaire associé au type (char et varchar) de champ que l'on remplie si l'on recharge les données déjà enregistrée ( si on utilise la methode GET)
function case_char_varchar ($methode, $i, $j, $objet, $type){
    if(isset($objet)){
        $isset = true;
    } else {
        $isset = false;
    }

    $string = '
        <div class="form-row">
            <div class="container">
                <label>'.$type.' :</label>
                <br>
                <label>nom de la variable:</label>
                <input type="text" required class="form-control"';
    if($methode == "GET"){
        $string .='value="'. $objet->getNom() .'"';
    }

    $string .= ' name="'.$j.$i .'_nom">
                <label>taille du champ:</label>
                <input type="text" required class="form-control"';
    if($methode == "GET"){
        $string .='value="'. $objet->getTailleChamp() .'"';
    }

    $string .= ' name="'.$j.$i.'_taille">

                <label>fichier:</label>
                <select class="browser-default custom-select" name="'.$j.$i.'_fichier">
                <option value="nom"';
    if ($isset){
        if($objet->getFichier() == "nom"){
        $string .=" selected ";
    }}
    

    $string .= '>nom</option>
                <option value="prenom"';
    if ($isset){
        if($objet->getFichier() == "prenom"){
        $string .=" selected ";
    }}
    

    $string .= '>prénom</option>
                <option value="ville"';
    if ($isset){
        if($objet->getFichier() == "ville"){
        $string .=" selected ";
    }}
    
       
    $string .= '>ville</option>
                <option value="departement"';
    if ($isset){
        if($objet->getFichier() == "departement"){
        $string .=" selected ";
    }}
    
                   
    $string .= '>département</option>
                <option value="region"';
    if ($isset){
        if($objet->getFichier() == "region"){
        $string .=" selected ";
    }}
    
                               
    $string .= '>région</option>
                <option value="pays"';
    if ($isset){
        if($objet->getFichier() == "pays"){
        $string .=" selected ";
    }
    }
                                           
    $string .= '>pays</option>pays</option>
                </select>
            </div>
            <br>
            <br>
        </div>
        ';

    echo $string;
}

//////////////////////////////////////              <input type="file" class="custom-file-input">                <label class="custom-file-label"> electionner un fichier</label>
//           case_bool              //
//////////////////////////////////////
                                            // On affiche la partie du formulaire associé au type (bool) de champ que l'on remplie si l'on recharge les données déjà enregistrée ( si on utilise la methode GET)
function case_bool ($methode, $i, $j, $objet){
    $string = '
        <div class="form-row">
            <div class="container">
                <label>Bool :</label>
                <br>
                <label>nom de la variable:</label>
                <input type="text" required class="form-control"';
    if($methode == "GET"){
        $string .='value="'. $objet->getNom() .'"';
    }

    $string .= ' name="'.$j.$i .'_nom">
            </div>
        </div>';

    echo $string;
}

//////////////////////////////////////
//    case_time_date_datetime       //
//////////////////////////////////////
                                            // On affiche la partie du formulaire associé au type (time date datetime) de champ que l'on remplie si l'on recharge les données déjà enregistrée ( si on utilise la methode GET)
function case_time_date_datetime ($methode, $i, $j, $objet, $type){
    $string = '
        <div class="form-row">
            <div class="container">
                <label>'.$type.' :</label>
                <br>
                <label>nom de la variable:</label>
                <input type="text" required class="form-control"';
    if($methode == "GET"){
        $string .='value="'. $objet->getNom() .'"';
    }

    $string .= ' name="'.$j.$i .'_nom">
                <label>valeur basse:</label>
                <input type="text" required class="form-control"';
    if($methode == "GET"){
        $string .='value="'. $objet->getValeurBasse() .'"';
    }

    if($type == "Time"){
        $string .='placeholder="h:m:s"';
    }
    if($type == "Date"){
        $string .='placeholder="aaaa-mm-jj"';
    }
    if($type == "DateTime"){
        $string .='placeholder="aaaa-mm-jj h:m:s"';
    }

    $string .= ' name="'.$j.$i.'_valeur_basse">
                <label>valeur haute:</label>
                <input type="text" required class="form-control"';
    if($methode == "GET"){
        $string .='value="'. $objet->getValeurHaute() .'"';
    }
    if($type == "Time"){
        $string .='placeholder="h:m:s"';
    }
    if($type == "Date"){
        $string .='placeholder="aaaa-mm-jj"';
    }
    if($type == "DateTime"){
        $string .='placeholder="aaaa-mm-jj_h:m:s"';
    }

    $string .= ' name="'.$j.$i.'_valeur_haute">
            </div>
        </div>';

    echo $string;
}
/*
//////////////////////////////////////
//     formulaire_p2_valide         //
//////////////////////////////////////

function formulaire_p2_valide(){

    $j = null;
    $i = null;
    
    if($_POST["nom_model"] == null || $_POST["nom_fichier"] == null || $_POST["nom_table"] == null){
        return false;
    }

    for($j = 0; $j < 8; $j++){
        $i = 0;

        while(isset($_POST[$j.$i.'_nom'])){

            if($_POST[$j.$i.'_nom'] == null){
                return false;
            }
            if(isset($_POST[$j.$i.'_valeur_haute'])){
                if($_POST[$j.$i.'_valeur_haute'] == null){
                    return false;
                }
            }

            if(isset($_POST[$j.$i.'_valeur_basse'])){
                if($_POST[$j.$i.'_valeur_basse'] == null){
                    return false;
                }    
            }
            
            $i+=1;
        }
    }
    return true;
}
*/
//////////////////////////////////////
//     formulaire_nettoyage         //          preg_match('`^\d{4}-\d{1,2}-\d{1,2}_\d{1,2}:\d{1,2}:\d{1,2}$`', $valMin)
//////////////////////////////////////
                                            //filtrage des données enfoyé par l'utilisateur, et vérification du format utilisé par l'utilisateur, si celui-ci est incorrecte la fonction renvoie false
function formulaire_nettoyage($nb_enregistrement){

    $j = null;
    $i = null;

    if($nb_enregistrement <= 0){
        return false;
    }

    for($j = 0; $j < 9; $j++){
        $i = 0;

        while(isset($_POST[$j.$i.'_nom'])){

            if($_POST[$j.$i.'_nom']){
                $_POST[$j.$i.'_nom'] = filter_var($_POST[$j.$i.'_nom'],FILTER_SANITIZE_STRING);
                if(strlen($_POST[$j.$i.'_nom']) == 20 ){
                    echo "pb: taille char ou varchar";
                    return false;
                }
            }

            if(isset($_POST[$j.$i.'_valeur_basse'])){

                if($j == 7){
                    $_POST[$j.$i.'_valeur_basse'] = filter_var($_POST[$j.$i.'_valeur_basse'], FILTER_SANITIZE_STRING);
                    if(!preg_match('`^\d{1,2}:\d{1,2}:\d{1,2}$`', $_POST[$j.$i.'_valeur_basse'])){
                        echo  $_POST[$j.$i.'_valeur_basse'];
                        echo "pb: heure min";
                        return false;
                    }
                } elseif($j == 6){
                    $_POST[$j.$i.'_valeur_basse'] = filter_var($_POST[$j.$i.'_valeur_basse'], FILTER_SANITIZE_STRING);
                    if(!preg_match('`^\d{4}-\d{1,2}-\d{1,2}$`', $_POST[$j.$i.'_valeur_basse'])){
                        echo "pb: date min";
                        return false;
                    }
                } elseif($j == 8){
                    $_POST[$j.$i.'_valeur_basse'] = filter_var($_POST[$j.$i.'_valeur_basse'], FILTER_SANITIZE_STRING);
                    if(!preg_match('`^\d{4}-\d{1,2}-\d{1,2} \d{1,2}:\d{1,2}:\d{1,2}$`', $_POST[$j.$i.'_valeur_basse'])){
                        echo "pb: datetime min";
                        return false;
                        
                    }
                    
                } else {
                    $_POST[$j.$i.'_valeur_basse'] = filter_var($_POST[$j.$i.'_valeur_basse'],FILTER_SANITIZE_NUMBER_INT);
                }
            }

            if(isset($_POST[$j.$i.'_valeur_haute'])){
                if($j == 7){
                    $_POST[$j.$i.'_valeur_basse'] = filter_var($_POST[$j.$i.'_valeur_basse'], FILTER_SANITIZE_STRING);
                    if(!preg_match('`^\d{1,2}:\d{1,2}:\d{1,2}$`', $_POST[$j.$i.'_valeur_haute'])){
                        echo "pb: heure max";
                        return false;
                    }
                } elseif($j == 6){
                    $_POST[$j.$i.'_valeur_basse'] = filter_var($_POST[$j.$i.'_valeur_basse'], FILTER_SANITIZE_STRING);
                    if(!preg_match('`^\d{4}-\d{1,2}-\d{1,2}$`', $_POST[$j.$i.'_valeur_haute'])){
                        echo "pb: date max";
                        return false;
                    }
                } elseif($j == 8){
                    $_POST[$j.$i.'_valeur_basse'] = filter_var($_POST[$j.$i.'_valeur_basse'], FILTER_SANITIZE_STRING);
                    if(!preg_match('`^\d{4}-\d{1,2}-\d{1,2} \d{1,2}:\d{1,2}:\d{1,2}$`', $_POST[$j.$i.'_valeur_haute'])){
                        echo "pb: datetime max";
                        return false;
                        
                    }

                } else {
                    $_POST[$j.$i.'_valeur_haute'] = filter_var($_POST[$j.$i.'_valeur_haute'],FILTER_SANITIZE_NUMBER_INT);
                }
            }

            if(isset($_POST[$j.$i.'_taille'])){
                $_POST[$j.$i.'_taille'] = filter_var($_POST[$j.$i.'_taille'],FILTER_SANITIZE_NUMBER_INT);
            }

            $i+=1;
        }
    }
    return true;
}
/*
//////////////////////////////////////
//     string_to_date               //
//////////////////////////////////////

function string_to_date($string){
    $time_input = strtotime($string);  
    return getDate($time_input);
}

//////////////////////////////////////
//     formulaire_teste_champ       //
//////////////////////////////////////

function string_dateTime($string){
    $date = strtotime($string); 
    return date('D/M/Y H:i:s', $date); 
}
*/

//////////////////////////////////////
//     formulaire_teste_champ       //
//////////////////////////////////////
                                            // La fonction vérifie si le nom d'un champ n'est pas utilisé plusieur fois est renvoie true si tel est le cas
function formulaire_teste_champ(){

    $var_nom = array();

    for($j = 0; $j < 9; $j++){
        $i = 0;
        while(isset($_POST[$j.$i.'_nom'])){
            array_push($var_nom, $_POST[$j.$i.'_nom']);
            $i+=1;
        }
    }

    for($j = 0; $j < 9; $j++){
        if (isset($var_nom[$j])){
            $nom = $var_nom[$j];
            $i = 0;
            while(isset($var_nom[$i])){
                if($var_nom[$i] == $nom && $j != $i){
                    return false;
                }
                $i+=1;
            }
        }
    }
    return true;
}

//////////////////////////////////////
//              reqUpdate           //
//////////////////////////////////////

function reqUpdate($actif, $type_champ, $cnxPDO){
    $requete = 'UPDATE type_champ SET actif = :actif WHERE type_champ = :type_champ';
    $req = $cnxPDO->prepare($requete);
    $req->bindParam(':actif', $actif, PDO::PARAM_INT);
    $req->bindParam(':type_champ', $type_champ, PDO::PARAM_STR);
    $req->execute();
}