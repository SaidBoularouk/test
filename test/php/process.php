<?php
require_once $_SERVER['DOCUMENT_ROOT']."/test/class/sqlClass.php";

if(!isset($sqlClass)){
$sqlObj = new sqlClass();
}

$mpost = isset($_POST) ? $_POST: array();
$post = filter_input_array(INPUT_POST, $mpost);

if(isset($_POST["action"])){
	switch ( $_POST["action"] ) {
		case 'save':
			# Save to Db...
			
			if($sqlObj->save()){
				$sqlObj->printMsg();
			}
			die;
		break;
		
		case 'delete':
			# delete from Db...
			if($sqlObj->delete() ){
				$sqlObj->printMsg();
			}
			die;
		break;

		case 'update':
			# delete from Db...
			if($sqlObj->update() ){
				$sqlObj->printMsg();
			}
			die;
		break;

		default:
			# code...
			break;
	}


}


?>