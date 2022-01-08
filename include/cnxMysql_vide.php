<?php

// Définition des éléments de connexion à la BDD :
$host 		= "127.0.0.1";			// IP du serveur hébergeant la BDD
$port 		= "3306";			// port de connexion
$charset 	= "UTF8";			// encodage du jeu de caractères :
// 										- français : ISO-8859-1 ou ISO-8859-15
//										- international : UTF8

$bddName 	= "";			// nom de la BDD
$user 		= "";			// utilisateur (login / pwd) autorisé à se connecter à cette BDD.
$pwd 		= "";			// (onglet Privilèges dans PhpMyAdmin)


// Synthèse des paramètres pour une BDD de type mySql :
$paramMySql = "mysql:host=" . $host . ";port=" . $port . ";" .
			  "dbname=" . $bddName . ";charset=" . $charset .  ";";

// Paramètres PDO [ optionnels ], voici quelques exemples :
$dbOptions = array(

	    	PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, 		// activation des warnings
	    	PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 		// activation des exceptions
		PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", 	// forçage du jeu de caractères UTF8
		PDO::ATTR_PERSISTENT		 =>	true,		// connexion persistante à la BDD
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC  	// résultats des requêtes sous forme de tableau associatif
);

// Connexion en utilisant la classe PDO
try{

	$cnxPDO = new PDO( $paramMySql, $user, $pwd, $dbOptions );
}
catch( Exception $e ){

	echo 'Erreur de connexion : ' . $e->getMessage();
}

