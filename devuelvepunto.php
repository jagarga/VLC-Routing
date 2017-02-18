<?php
define("PG_DB" , "OSMSPAIN");
define("PG_HOST", "localhost");
define("PG_USER", "postgres");
define("PG_PASS", "proyecto");
define("PG_PORT", "5432");
define("TABLE" , "puntosturisticos");
$nom = $_REQUEST['nom']; // Recibimos el identificador del punto recibido como parámetro

// conectamos con la base de datos:
$con = pg_connect("dbname=".PG_DB." host=".PG_HOST." password=".PG_PASS." user=".PG_USER);
// sentencia sql para obtener la información de ese punto:
$sql = "select nombre, longitud as X, latitud as Y, descripcion from ".TABLE." where
(gid = '".$nom."')";
$query = pg_query($con,$sql);

// guardamos el resultado:
$coordenades = pg_fetch_row($query);
// Convertimos la codificación para que se muestren los acentos correctamente :
$coordenades[0] = mb_convert_encoding($coordenades[0], "ISO-8859-1", "UTF-8");
$coordenades[3] = mb_convert_encoding($coordenades[3], "ISO-8859-1", "UTF-8");
// XML a devolver:
$xml = '<?xml version="1.0" encoding="iso-8859-1" standalone="yes" ?>'."\n";
$xml .= "<puntTuristic>\n";
$xml .= "<nom>".$coordenades[0]."</nom>\n";
$xml .= "<x>".$coordenades[1]."</x>\n";
$xml .= "<y>".$coordenades[2]."</y>\n";
$xml .= "<desc>".$coordenades[3]."</desc>\n";
$xml .= "</puntTuristic>\n";

pg_close($con);
// Devolvemos el XML
header('Content-type: text/xml');
echo $xml;
?>