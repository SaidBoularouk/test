 <?php
require_once $_SERVER['DOCUMENT_ROOT']."/test/class/sqlClass.php";


if(!isset($sqlClass)){
    $sqlClass = new sqlClass();
}

$page = 0;
if(isset($_GET['page'])  ){
$page = $_GET['page'];
}


$start = $page*15;
$end = $start+15;
// Calculate total pages
$all_Items = $sqlClass->fetchRecords($start, $end);

echo json_encode($all_Items);

?>