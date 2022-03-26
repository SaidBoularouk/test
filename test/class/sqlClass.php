<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/test/config/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/test/class/dbClass.php';

class sqlClass{
    /** @var object $pdo Copy of PDO connection */
    private $pdo;
   
    /** @var string error msg */
    private $msg;
    public $lastId;

    private $items_table = "banque";


    public function __construct(){
       // echo 'The class "' . __CLASS__ . '" was initiated!<br>';
     
        $dbclass = new dbclass();
        $this->pdo = $dbclass->getPdo();
        if($this->pdo !==null){
         //   echo 'cool';
        }else{
         //   echo 'null';
        }
    }

  

    /**
    * Print error msg function
    * @return void.
    */
    public function printMsg(){
        print $this->msg;
    }
  
    /**
    * Return the logged in user.
    * @return user array data
    */

    public function getPdo(){
        return $this->pdo;
    }


/**
    * Delete function
    * @param int $id from post data.
    * @return boolean of success.
*/

public function delete(){
    if(is_null($this->pdo)){
        $this->msg = Connection_did_not_work_out;
    }else{
        $pdo = $this->pdo;
        $post = isset($_POST) ? $_POST: array();
        $id = isset($post['id']) ? $post['id'] : "";  

        $stmt = $pdo->prepare(  " DELETE FROM $this->items_table WHERE id=:id " );
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if($stmt->execute()){
            return true;
        }else{
            $this->msg = 'Error..';
            return false;
        }
    
    }
}



/**
    * Update function
    * @param int $id from post data.
    * @return boolean of success.
*/
    public function update(){
        if(is_null($this->pdo)){
            $this->msg = Connection_did_not_work_out;
        }else{
        $pdo = $this->pdo;
        $post = isset($_POST) ? $_POST: array();
        $id = isset($post['id']) ? $post['id'] : ""; 
        $montant = isset($post['montant']) ? $post['montant'] : "";  
        try{   
            $query = "UPDATE $this->items_table SET json=JSON_SET(json, '$.montant', $montant) WHERE id=$id ";
            $stmt = $pdo->prepare( $query );
            //   $stmt->bindParam(':montant', $montant, PDO::PARAM_STR );
            //   $stmt->bindParam(':id', $id, PDO::PARAM_INT);
               if($stmt->execute()){
                    return true;
                }else{
                    $this->msg = 'Error..';
                }

            } catch (PDOException $e) {
              $this->msg = 'Error..';
        }   
        }

    }




    /**
    * Fetch records function
    *
    * @return array between limits.
    */
    public function fetchRecords($limit, $offset){
        $result =[];
        if(is_null($this->pdo)){
            $this->msg = Connection_did_not_work_out;
            return [];
        }else{
            $pdo = $this->pdo;
            $stmt = $pdo->prepare("SELECT * FROM  $this->items_table ORDER BY id DESC LIMIT  $limit , $offset" );
            if($stmt->execute()){
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }else{
                $result =[];
            }
           
        }
         return $result; 
    }




public function save() {  
    if(is_null($this->pdo)){
        $this->msg =Connection_did_not_work_out;
        return [];
    }else{ 
    $pdo = $this->pdo;      
    $post = isset($_POST) ? $_POST: array();
    unset($post["action"]);
    $date = isset($post['date']) ? $post['date'] : "";
    $json = json_encode($post, JSON_UNESCAPED_UNICODE);
    $stmt = $pdo->prepare('INSERT INTO '. $this->items_table. ' (date, json) VALUES (?,?)');

    if($stmt->execute([$date ,$json])){
            return true;
    }else{
            //print_r($stmt->errorInfo());
        $this->msg ="Problème d'insertion";
        return false;
        }
    
}

}  


function countRows(){

    if(is_null($this->pdo)){
      $this->msg =Connection_did_not_work_out;
        return 0;
    }else{ 
    $pdo = $this->pdo; 
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM $this->items_table");
    $result = $stmt->execute();
    $number_of_rows = $result->fetchColumn();
    return $number_of_rows;
    }

}
   

}
?>