<?php

class TinyInt {
    private $nom;
    private $val_haute;
    private $val_basse;
    
    public function __construct($nom, $val_haute, $val_basse){
        $this->nom = $nom;   
        $this->val_haute = $val_haute;
        $this->val_basse = $val_basse;
    }
    
    public function getNom(){
        return $this->nom;
    }

    public function getValeurHaute(){
        return $this->val_haute;
    }

    public function getValeurBasse(){
        return $this->val_basse;
    }

    public function getRandomVal(){
        return random_int($this->getValeurBasse(), $this->getValeurHaute());
    }
}   

class Entier {
    private $nom;
    private $val_haute;
    private $val_basse;
    
    public function __construct($nom, $val_haute, $val_basse){
        $this->nom = $nom;   
        $this->val_haute = $val_haute;
        $this->val_basse = $val_basse;
    }
    
    public function getNom(){
        return $this->nom;
    }

    public function getValeurHaute(){
        return $this->val_haute;
    }

    public function getValeurBasse(){
        return $this->val_basse;
    } 

    public function getRandomVal(){
        return random_int($this->getValeurBasse(), $this->getValeurHaute());
    }
}  

class Double {
    private $nom;
    private $val_haute;
    private $val_basse;
    
    public function __construct($nom, $val_haute, $val_basse){
        $this->nom = $nom;   
        $this->val_haute = $val_haute;
        $this->val_basse = $val_basse;
    }
    
    public function getNom(){
        return $this->nom;
    }

    public function getValeurHaute(){
        return $this->val_haute;
    }

    public function getValeurBasse(){
        return $this->val_basse;
    }

    public function getRandomVal(){
        return random_int($this->getValeurBasse()*100, $this->getValeurHaute()*100) / 100;
    }
}   

class Char {
    private $nom;
    private $taille_champ;
    private $fichier;

    public function __construct($nom, $taille_champ, $fichier){
        $this->nom = $nom;   
        $this->taille_champ = $taille_champ;
        $this->fichier = $fichier;
    }
    
    public function getNom(){
        return $this->nom;
    }

    public function getTailleChamp(){
        return $this->taille_champ;
    }

    public function getFichier(){
        return $this->fichier;
    }

    public function getRandomVal(){
        $mot = file($this->getFichier().'.txt');
        $mot = str_replace("\r\n", "", $mot[rand(0, count($mot) - 1)]);
        return '"'.str_replace("'","''", $mot).'"';
    }
}  

class Varchar {
    private $nom;
    private $taille_champ;
    private $fichier;
    
    public function __construct($nom, $taille_champ, $fichier = ""){
        $this->nom = $nom;   
        $this->taille_champ = $taille_champ;
        $this->fichier = $fichier;
    }
    
    public function getNom(){
        return $this->nom;
    }

    public function getTailleChamp(){
        return $this->taille_champ;
    }

    public function getFichier(){
        return $this->fichier;
    }

    public function getRandomVal(){
        $mot = file($this->getFichier().'.txt');
        $mot = str_replace("\r\n", "", $mot[rand(0, count($mot) - 1)]);
        return '"'.str_replace("'","''", $mot).'"';
    }
}    

class boulean {
    
    private $bool;
    
    public function __construct($bool){
        $this->bool = $bool;   
    }
    
    public function getNom(){
        return $this->bool;
    }

    public function getRandomVal(){
        return rand(0,1);
    }
}    

class Date {
    private $nom;
    private $val_haute;
    private $val_basse;
    
    public function __construct($nom, $val_haute, $val_basse){
        $this->nom = $nom;   
        $this->val_haute = $val_haute;
        $this->val_basse = $val_basse;
    }
    
    public function getNom(){
        return $this->nom;
    }

    public function getValeurHaute(){
        return $this->val_haute;
    }

    public function getValeurBasse(){
        return $this->val_basse;
    }
    
    public function getRandomVal(){
        $result = date_format($this->val_basse,"Y/m/d H:i:s");
        $date_min = explode('-', $result);
        $result = date_format($this->val_haute,"Y/m/d H:i:s");
        $date_max = explode('-', $result);
        $annee = random_int((int)$date_min[0], (int)$date_max[0]);
        $mois = rand(1,12);
        $jour = rand(1,30);
        return '"'.$annee.'-'.$mois.'-'.$jour.'"';
    }
}    

class Time {
    private $nom;
    private $val_haute;
    private $val_basse;
    
    public function __construct($nom, $val_haute, $val_basse){
        $this->nom = $nom;   
        $this->val_haute = $val_haute;
        $this->val_basse = $val_basse;
    }
    
    public function getNom(){
        return $this->nom;
    }

    public function getValeurHaute(){
        return $this->val_haute;
    }

    public function getValeurBasse(){
        return $this->val_basse;
    }
    
    public function getRandomVal(){
        $result = $this->val_basse->format('H:i:s');
        $time_min = explode(" ", $result);
        $result = $this->val_haute->format('H:i:s');
        $time_max = explode(" ", $result);
        $heure = random_int((int)$time_min[0], (int)$time_max[0]);
        $minute = rand(0,59);
        $seconde = rand(0,59);
        return '"'.$heure.':'.$minute.':'.$seconde.'"';
    }
}    

class date_time {
    private $nom;
    private $val_haute;
    private $val_basse;
    
    public function __construct($nom, $val_haute, $val_basse){
        $this->nom = $nom;   
        $this->val_haute = $val_haute;
        $this->val_basse = $val_basse;
    }
    
    public function getNom(){
        return $this->nom;
    }

    public function getValeurHaute(){
        return $this->val_haute;
    }

    public function getValeurBasse(){
        return $this->val_basse;
    }
    
    public function getRandomVal(){
        $result = $this->val_basse->format('Y-m-d H:i:s');
        $time_min = explode(" ", $result);
        $result = $this->val_haute->format('Y-m-d H:i:s');
        $time_max = explode(" ", $result);
        $date_time_min = new DateTime($time_min[0]." ".$time_min[1]);
        $date_time_max = new DateTime($time_max[0]." ".$time_max[1]);
        $new = rand($date_time_min->getTimestamp(), $date_time_max->getTimestamp());
        $newDate = new Datetime();
        $newDate->setTimestamp($new);
        return '"'.$newDate->format("Y-m-d H:i:s").'"';
    }
}