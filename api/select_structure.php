<?php
error_reporting(0);
# Connection à la base de données
include_once('config.php');

#Requete pour sélectionner les entités cartographiques et les données attributaires
$type = $_GET["type"];
if ($type=='*')

$sql = "select name,type,st_asgeojson(sante.geom) as geojson from sante";
else
$sql = "select name,type,st_asgeojson(sante.geom) as geojson from sante WHERE type='$type'";


$rs = $conn->query($sql);
if (!$rs) {
    echo 'Erreur SQL.\n';
    exit;
}

# Préparer une variable pour le formatage des données en Geojson
$geojson = array(
   'type'      => 'FeatureCollection',
   'features'  => array()
);

# Formater les données de la requete en GEOJSON
while ($row = $rs->fetch(PDO::FETCH_ASSOC)) {
    $properties = $row;
     
    unset($properties['geojson']);
    unset($properties['geom']);
    $feature = array(
         'type' => 'Feature',
         'geometry' => json_decode($row['geojson'], true),
         'properties' => $properties
    );
    # Ajouter le feature array à la collection
    array_push($geojson['features'], $feature);
}

header('Content-type: application/json');
echo json_encode($geojson, JSON_NUMERIC_CHECK);
$conn = NULL;
?>
