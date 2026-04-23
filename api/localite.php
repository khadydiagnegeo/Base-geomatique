<?php
error_reporting(0);
# Connection à la base de données
include_once('config.php');

#Requete pour sélectionner les entités cartographiques et les données attributaires
$sql="WITH indice_values AS (
    SELECT
        c.name_4, 
        c.pop_2024, 
        COUNT(s.geom) AS nb_structures,
        -- Gérer la division par zéro
        CASE
            WHEN COUNT(s.geom) > 0 THEN c.pop_2024 / COUNT(s.geom)
            ELSE NULL
        END AS indice,
        ST_AsGeoJSON(c.geom) AS geojson
    FROM 
        commune c
    LEFT JOIN
        sante s ON ST_Within(s.geom, c.geom)
    WHERE
        c.geom IS NOT NULL
    GROUP BY 
        c.name_4, c.pop_2024, c.geom
),
min_max AS (
    SELECT 
        MIN(indice) AS indice_min, 
        MAX(indice) AS indice_max
    FROM 
        indice_values
)
SELECT 
    iv.name_4,
    iv.pop_2024, 
    iv.nb_structures, 
    iv.indice,
    -- Gérer la division par zéro dans la normalisation
    CASE
        WHEN mm.indice_max > mm.indice_min THEN (iv.indice - mm.indice_min) / (mm.indice_max - mm.indice_min)
        ELSE NULL
    END AS indice_norm,
    iv.geojson
FROM 
    indice_values iv
CROSS JOIN 
    min_max mm 
-- Décommentez pour trier les résultats par indice normalisé
-- ORDER BY indice_norm ASC";

 // $sql="select ST_AsGeoJSON(geom) as geojson ,name_4,pop_2024 from commune";

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
