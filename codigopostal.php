<?php

  // Database connection settings
  $inicio = $_REQUEST['cp1'];
  $final = $_REQUEST['cp2'];
  $servidorBD = "localhost";
	$usuario = "postgres";
	$clave = "proyecto";
	$BD = "osm";
	$enlace = 0;

  $counter = $pathlength = 0;
  
// nos conectamos a nuestra base de datos
$enlace = pg_connect("host=".$servidorBD." port= 5432"." dbname=".$BD." user=".$usuario." password=".$clave)
or die("Existio un error al intentar conectarse al servidor de base de datos"); 


//consulta sencilla
$query = "select x,y from centroides where codigo='$inicio';";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());

$rows = pg_numrows($result);

while ($row = pg_fetch_row($result)) {
  
  $lat = $row[1];
  $lon = $row[0];
  
}
//consulta sencilla
$query = "select x,y from centroides where codigo='$final';";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());

$rows = pg_numrows($result);

while ($row = pg_fetch_row($result)) {
  
  $lat1 = $row[1];
  $lon1 = $row[0];
  
}


$xml ='<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>';
$xml .='<coords>';

     $xml .='<lat>';
     $xml .=$lat;
     $xml .='</lat>';
	 $xml .='<lon>';
     $xml .= $lon;
     $xml .= '</lon>';
	 $xml .= '<lat1>';
     $xml .= $lat1;
     $xml .= '</lat1>';
	 $xml .= '<lon1>';
     $xml .= $lon1;
     $xml .= '</lon1>';
	 
header('Content-type: text/xml',true);
$xml .='</coords>';

echo $xml;
?> 



