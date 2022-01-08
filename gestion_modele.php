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

        <?php require "html/headerback.html"; ?>

        <?php
            include 'include/fonction.php';
            include 'include/cnxMysql.php';
            $reponse = $cnxPDO->query("SELECT * FROM type_champ");
            
        ?>
        
        <div class="container d-flex justify-content-center .bg-warning">
            <br>
            <h2> Gestion du modèle: </h2>
            <br>
        </div>
        <br>
        <div class="container">
            <form action="back.php" method="post">
                <?php
                    $string = "";
                    while($donnees = $reponse->fetch()){
                        $string = '<br>
                                <input class="form-check-input" type="checkbox" name="'.$donnees['type_champ'].'"';
                        if($donnees['actif'] == 1){
                            $string .='checked';
                        }
                        $string .= '>
                                <label class="form-check-label" for="defaultCheck1">
                                    Activer le champ '.$donnees['type_champ'].'
                                </label>
                                <br>';

                        echo $string;
                    }

                ?>
                    <br>
                    <br>
                    <div class="container">
                        <button type="submit" class="btn btn-primary" href="back.php">Suivant</button>
                    </div>
                </div>
            </form>

        <?php require "html/footerback.html"; ?>

    </body>
</html>