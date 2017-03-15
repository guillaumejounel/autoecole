<?php
header('Pragma: no-cache');
header('Expires: 0');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-cache, must-revalidate');
include 'secret.php';
$connect=mysqli_connect($dbhost,$dbuser,$dbpass,$dbname) or die ('Error connecting to mysql');

$nomtable = htmlspecialchars($_GET['table'], ENT_QUOTES);
if($nomtable=='init') {
  $sql = file_get_contents('localhost.sql');
  $query_array = explode('£', $sql);
  for($i=0;$i<sizeof($query_array);$i++) {
    $result = mysqli_multi_query($connect, $query_array[$i]);
  }
  echo "BDD réinitialisée. Que la force soit avec vous !";
} else {
  if($nomtable=='vider') {
    $result = mysqli_query($connect, "TRUNCATE eleves");
    $result = mysqli_query($connect, "TRUNCATE inscription");
    $result = mysqli_query($connect, "TRUNCATE seances");
    $result = mysqli_query($connect, "TRUNCATE themes");
    if(!$result) { echo "Erreur lors du vidage de BDD"; } else { echo "BDD vidée !"; }
  } else {
    $query = "select * from $nomtable";
    $result = mysqli_query($connect, $query);
    $nb = mysqli_num_rows($result);
    if(!$result) { echo "X"; } else { echo $nb; }
  }
}
mysqli_close($connect); ?>
