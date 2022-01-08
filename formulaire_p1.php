<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title> Projet PHP</title>

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
        
        <?php require "html/header.html";

        include 'include/fonction.php';
        include 'include/cnxMysql.php';
        $reponse = $cnxPDO->query("SELECT * FROM type_champ");
        
        $var = array("Tinyint",
            "Integer",
            "Double",
            "Char",
            "Varchar",
            "Boolean",
            "Date",
            "Time",
            "DateTimes",
        );

        $i = 0;

        ?>

        <div class="container">
            <br>
            <h2>Formulaire 1/3 </h2>
            <br>
            <form action="formulaire_p2.php" method="post">
                <div class="form-group">           
                    <label>nom du model :</label>
                    <input type="text" class="form-control" name="nom_model" required> 
                    <br>
                    <label>nom de la table:</label>
                    <input type="text" class="form-control" name="nom_table" required>
                </div>

                <label>nombre de chaque type :</label>
                <br>

                <?php while($donnees = $reponse->fetch()): ?>

                    <div class="form-row">     <!-- formulaire basic pour obtenir le nombre de champ, leurs type, et le nom du model et de la table concernée tout cela en methode POST-->                

                        <?php if ($donnees['type_champ'] == $var[0] && $donnees['actif'] == 1): ?>
                            <label>Tiny Int :</label>
                            <input type="text" class="form-control" name="nb_tiny_int">
                        <?php endif;?>

                        <?php if ($donnees['type_champ'] == $var[1] && $donnees['actif'] == 1): ?>
                            <label>Int :</label>
                            <input type="text" class="form-control" name="nb_int">
                        <?php endif;?>
                    
                        <?php if ($donnees['type_champ'] == $var[2] && $donnees['actif'] == 1): ?>
                            <label>Double :</label>
                            <input type="text" class="form-control" name="nb_double">
                        <?php endif;?>

                        <?php if ($donnees['type_champ'] == $var[3] && $donnees['actif'] == 1): ?>
                            <label>Char :</label>
                            <input type="text" class="form-control" name="nb_char">
                        <?php endif;?>
                            
                        <?php if ($donnees['type_champ'] == $var[4] && $donnees['actif'] == 1): ?>
                            <label>Varchar :</label>
                            <input type="text" class="form-control" name="nb_varchar">
                        <?php endif;?>
                            
                        <?php if ($donnees['type_champ'] == $var[5] && $donnees['actif'] == 1): ?>
                            <label>Bool :</label>
                            <input type="text" class="form-control" name="nb_bool">
                        <?php endif;?>

                        <?php if ($donnees['type_champ'] == $var[6] && $donnees['actif'] == 1): ?>
                            <label>Date :</label>
                            <input type="text" class="form-control" name="nb_date">
                        <?php endif;?>
                    
                        <?php if ($donnees['type_champ'] == $var[7] && $donnees['actif'] == 1): ?>
                            <label>Time :</label>
                            <input type="text" class="form-control" name="nb_time">
                        <?php endif;?>

                        <?php if ($donnees['type_champ'] == $var[8] && $donnees['actif'] == 1): ?>
                            <label>DateTime :</label>
                            <input type="text" class="form-control" name="nb_datetime">
                        <?php endif;?>

                    </div>

                    <br>

                <?php endwhile;?>

                <button type="submit" class="btn btn-primary" href="formulaire_p2.php">Suivant</button>
            
            </form>
        </div>
        
        <?php require "html/footer.html";?>
    </body>
</html>