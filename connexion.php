<?php

class connect extends PDO{

    const host = "localhost";
    const db = "gestion_commande";
    const user = "root";
    const psw = "";

    public function __construct(){
        try{
            parent::__construct("mysql:dbname=".self::db.";host=".self::host.";user=".self::user.";psw=".self::psw);
            // echo "DONE";
        }catch(PDOException $e){
            echo $e->getMessage()." ".$e->getFile()." ".$e->getLine();
        }
    }
}

?>