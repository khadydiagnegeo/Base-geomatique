<?php
error_reporting(0);
# Connection à la base de données
include_once('config.php');

#Requete pour sélectionner les entités cartographiques et les données attributaires
$structure = $_GET["structure"];
if ($structure=='*')

$sql= "SELECT nom,code,structure,label,superficie,ST_asgeojson(st_transform((geom),4326)) as geojson FROM surface2";
else
$sql = "SELECT nom,code,structure,label,superficie,ST_asgeojson(st_transform((geom),4326)) as geojson FROM surface2 WHERE structure='$structure'";


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
