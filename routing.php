<?php
  // Database connection settings
  define("PG_DB"  , "OSMSPAIN");
  define("PG_HOST", "localhost");
  define("PG_USER", "postgres");
  define("PG_PASS", "proyecto");
  define("PG_PORT", "5432");
  define("TABLE",   "cr3");
  $counter = $pathlength = 0;
// Retrieve start point
$start = split(' ',$_REQUEST['startpoint']);

$startPoint = array($start[0], $start[1]);


// Retrieve end point
$end = split(' ',$_REQUEST['finalpoint']);

$endPoint = array($end[0], $end[1]);

// Find the nearest edge
$startEdge = findNearestEdge($startPoint);


$endEdge   = findNearestEdge($endPoint);

// FUNCTION findNearestEdge
function findNearestEdge($lonlat) {

// Connect to database
$con = pg_connect("dbname=".PG_DB." host=".PG_HOST." password=".PG_PASS." user=".PG_USER);

$sql = "SELECT gid, source, target, the_geom,
                 distance(the_geom, GeometryFromText(
                  'POINT(".$lonlat[0]." ".$lonlat[1].")', 900913)) AS dist
            FROM ".TABLE."
            WHERE the_geom && setsrid(
                  'BOX3D(".($lonlat[0]-200)."
                         ".($lonlat[1]-200).",
                         ".($lonlat[0]+200)."
                         ".($lonlat[1]+200).")'::box3d, 900913)
            ORDER BY dist LIMIT 1";

$query = pg_query($con,$sql);

$edge['gid']      = pg_fetch_result($query, 0, 0);
$edge['source']   = pg_fetch_result($query, 0, 1);
$edge['target']   = pg_fetch_result($query, 0, 2);
$edge['the_geom'] = pg_fetch_result($query, 0, 3);

// Close database connection
pg_close($con);

return $edge;
}

// Select the routing algorithm
switch($_REQUEST['method']) {

case 'SPD' : // Shortest Path Dijkstra
  $sql = "SELECT rt.gid, AsText(rt.the_geom) AS wkt,
                   length(rt.the_geom) AS length, ".TABLE.".gid
                FROM ".TABLE.",
                    (SELECT gid, the_geom
                        FROM dijkstra_sp_delta(
                            '".TABLE."',
                            ".$startEdge['source'].",
                            ".$endEdge['target'].",
                            3000)
                     ) as rt
                WHERE ".TABLE.".gid=rt.gid;";

  break;

case 'SPA' : // Shortest Path A*

  $sql = "SELECT rt.gid, AsText(rt.the_geom) AS wkt,
                     length(rt.the_geom) AS length, ".TABLE.".id
                  FROM ".TABLE.",
                      (SELECT gid, the_geom
                          FROM astar_sp_delta(
                              '".TABLE."',
                              ".$startEdge['source'].",
                              ".$endEdge['target'].",
                              3000)
                       ) as rt
                  WHERE ".TABLE.".gid=rt.gid;";
  break;

case 'SPS' : // Shortest Path Shooting*

  $sql = "SELECT rt.gid, AsText(rt.the_geom) AS wkt,
                     length(rt.the_geom) AS length, ".TABLE.".id
                  FROM ".TABLE.",
                      (SELECT gid, the_geom
                          FROM shootingstar_sp(
                              '".TABLE."',
                              ".$startEdge['gid'].",
                              ".$endEdge['gid'].",
                              3000, 'length', false, false)
                       ) as rt
                  WHERE ".TABLE.".gid=rt.gid;";
  break;

} // close switch


//  echo $sql;
// Database connection and query
$dbcon = pg_connect("dbname=".PG_DB." host=".PG_HOST." password=".PG_PASS." user=".PG_USER);

$query = pg_query($dbcon,$sql);

// Return route as XML
$xml  = '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>'."\n";
$xml .= "<route>\n";

// Add edges to XML file
while($edge=pg_fetch_assoc($query)) {

$pathlength += $edge['length'];

$xml .= "\t<edge id='".++$counter."'>\n";
$xml .= "\t\t<id>".$edge['id']."</id>\n";
$xml .= "\t\t<wkt>".$edge['wkt']."</wkt>\n";
$xml .= "\t\t<length>".round(($pathlength),3)."</length>\n";
$xml .= "\t</edge>\n";
}

$xml .= "</route>\n";

// Close database connection
pg_close($dbcon);

// Return routing result
header('Content-type: text/xml',true);
echo $xml;

?>