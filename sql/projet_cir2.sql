#------------------------------------------------------------
# Delete database
#------------------------------------------------------------
DROP DATABASE IF EXISTS mini_projet;
DROP USER IF EXISTS 'lucas'@'localhost';

#------------------------------------------------------------
# Create database
#------------------------------------------------------------
CREATE DATABASE mini_projet;

#------------------------------------------------------------
# Create user
#------------------------------------------------------------
CREATE USER 'lucas'@'localhost' IDENTIFIED BY 'lucas';
GRANT ALL PRIVILEGES ON mini_projet.* TO 'lucas'@'localhost';
FLUSH PRIVILEGES;

USE mini_projet;

#------------------------------------------------------------
# Table: modele
#------------------------------------------------------------

CREATE TABLE modele(
        libelle          Varchar (50) NOT NULL ,
		nom_fichier		 Varchar (50) NOT NULL ,
    	nom_table        Varchar (50) NOT NULL ,
        date_creation    Date NOT NULL
	,CONSTRAINT modele_PK PRIMARY KEY (libelle)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: type_champ
#------------------------------------------------------------
CREATE TABLE type_champ(
        type_champ Varchar (512) NOT NULL ,
        actif      tinyint(1) NOT NULL
	,CONSTRAINT type_champ_PK PRIMARY KEY (type_champ)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: champ
#------------------------------------------------------------
CREATE TABLE champ(
        id               Int  Auto_increment  NOT NULL ,
        nom_champ        Varchar (50) NOT NULL ,
        longueur         Double NULL ,
        val_min_nb       Double NULL ,
        val_max_nb       Double NULL ,
        val_min_date     DateTime NULL ,
        val_max_date     DateTime NULL ,
		liste_txt        Varchar (512) NULL ,
    	fichier        Varchar (512) NULL ,
   		libelle      Varchar (512) NOT NULL ,
        type_champ   Varchar (512) NOT NULL
	,CONSTRAINT champ_PK PRIMARY KEY (id)

	,CONSTRAINT champ_modele_FK FOREIGN KEY (libelle) REFERENCES modele(libelle)
	,CONSTRAINT champ_type_champ0_FK FOREIGN KEY (type_champ) REFERENCES type_champ(type_champ)
)ENGINE=InnoDB;

#------------------------------------------------------------
# Table: type_champ
#------------------------------------------------------------
INSERT INTO type_champ VALUES ('Integer', 1);
INSERT INTO type_champ VALUES ('Double', 1);
INSERT INTO type_champ VALUES ('Tinyint', 1);
INSERT INTO type_champ VALUES ('Varchar', 1);
INSERT INTO type_champ VALUES ('Char', 1);
INSERT INTO type_champ VALUES ('Boolean', 1);
INSERT INTO type_champ VALUES ('Date', 1);
INSERT INTO type_champ VALUES ('Time', 1);
INSERT INTO type_champ VALUES ('DateTimes', 1);

SET autocommit = 0;
#SET names utf8; Si on l'active empêche les requêtes sql de se faire