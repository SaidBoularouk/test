<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/test/config/config.php';


class dbclass{

     /** @var object $pdo Copy of PDO connection */
    private $pdo;

    /** @var string error msg */
    private $msg;
     /**
    * Connection init function
    * @param string $conString DB connection string.
    * @param string $user DB user.
    * @param string $pass DB password.
    *
    * @return bool Returns connection success.
    */

        public function __construct(){
        $this->pdo = $this->dbconnect(conString, dbUser, dbPass);
        if($this->dbconnect(conString, dbUser, dbPass) !==null){
         //   echo 'cool';
        }else{
         //   echo 'null';
        }


   }
   

    public function dbconnect($conString, $user, $pass){
            try {
                    $pdo = new PDO($conString, $user, $pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
                    $this->pdo = $pdo;
                    return true;
                 }catch(PDOException $e) { 
                    $this->msg = 'Connection did not work out!';
                    header('location:'. 'index.html');
                    //die();
                    return false;
                }
    }

    /**
    * Return the logged in user.
    * @return user array data
    */

    public function getPdo(){
        return $this->pdo;
    }

}
?>