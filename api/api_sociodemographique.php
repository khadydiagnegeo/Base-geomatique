<?php
error_reporting(0);
# Connection à la base de données
include_once('config.php');

#Requete pour sélectionner les entités cartographiques et les données attributaires
/*$region = $_GET["region"];
if ($region=='*')
$sql = "select ST_AsGeoJSON(geom) as geojson ,nomreg,admi01_id,superfice_ from region_sn";
else
$sql= "select ST_AsGeoJSON(geom) as geojson ,nomreg,admi01_id,superfice_ from region_sn where nomreg='$region'";

*/

$sql = "select ST_AsGeoJSON(geom) as geojson ,densité_k,homme,femme,total_u,nomreg FROM reg_sn";
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
