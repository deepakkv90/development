<?php
include('includes/configure.php');
include('includes/filenames.php');
include('includes/database_tables.php');
include('includes/functions/database.php');
include('includes/functions/general.php');        
tep_db_connect();

$page = $_GET['page']; // get the requested page
$limit = $_GET['rows']; // get how many rows we want to have into the grid
$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
$sord = $_GET['sord']; // get the direction
$searchTerm = $_GET['searchTerm'];

//extra parameter in URL
$country_id = $_GET["cid"];

if(!$sidx) $sidx =1;
if ($searchTerm=="") {
	$searchTerm="%";
} else {
	$searchTerm = "%" . $searchTerm . "%";
}

//$result = mysql_query("SELECT COUNT(*) AS count FROM postcode WHERE country_id='".$country_id."' AND  postcode like '$searchTerm'");
$result = mysql_query("SELECT COUNT(*) AS count FROM postcode WHERE postcode like '$searchTerm'");
$row = mysql_fetch_array($result,MYSQL_ASSOC);
$count = $row['count'];

if( $count >0 ) {
	$total_pages = ceil($count/$limit);
} else {
	$total_pages = 0;
}
if ($page > $total_pages) $page=$total_pages;
$start = $limit*$page - $limit; // do not put $limit*($page - 1)

/*if($total_pages!=0) $SQL = "SELECT * FROM postcode WHERE country_id='".$country_id."' AND postcode like '$searchTerm'  ORDER BY $sidx $sord LIMIT $start , $limit";
else $SQL = "SELECT * FROM postcode WHERE country_id='".$country_id."' AND postcode like '$searchTerm'  ORDER BY $sidx $sord";
*/
if($total_pages!=0) $SQL = "SELECT * FROM postcode WHERE postcode like '$searchTerm'  ORDER BY $sidx $sord LIMIT $start , $limit";
else $SQL = "SELECT * FROM postcode WHERE postcode like '$searchTerm'  ORDER BY $sidx $sord";

$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

$response->page = $page;
$response->total = $total_pages;
$response->records = $count;
$i=0;

while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {

    $response->rows[$i]['postcode']=$row['postcode'];
    $response->rows[$i]['state']=$row['state'];
    $response->rows[$i]['city']=$row['city'];
	$response->rows[$i]['country_id']=$row['country_id'];

    $i++;
} 
echo json_encode($response);
?>
