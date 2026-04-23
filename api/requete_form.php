<?php
// Connexion à la base de données
$host = 'localhost';
$dbname = 'L3_geom';
$dbuser = 'postgres';
$dbpass = 'kd776475461';

try{
    $conn = new PDO("pgsql:dbname=$dbname;host=$host", $dbuser, $dbpass);
}
catch (PDOException $e) { echo "Erreur : " . $e->getMessage();}


// // Récupération des données du formulaire
// $id = $_POST['id'];
// $nom = $_POST['nom'];
// $latitude = $_POST['latitude'];
// $longitude = $_POST['longitude'];
// $altitude = $_POST['altitude'];
// $ordre = $_POST['ordre'];
// $localite = $_POST['altitude'];


// // Vérification si un ID a été fourni
// if (empty($id)) {
//     die("L'ID est obligatoire.");
// }

// // Préparer la requête SQL
// $sql = "UPDATE points_geodesique SET 
//             nom = COALESCE(:nom, nom), 
//             latitude = COALESCE(:latitude, latitude), 
//             longitude = COALESCE(:longitude, longitude), 
//             altitude = COALESCE(:altitude, altitude),
//             ordre = COALESCE(:ordre,ordre),
//             localite = COALESCE(:localite,localite)
//         WHERE id = :id";

// $stmt = $pdo->prepare($sql);

// // Exécuter la requête
// try {
//     $stmt->execute([
//         ':id' => $id,
//         ':nom' => !empty($nom) ? $nom : null,
//         ':latitude' => !empty($latitude) ? $latitude : null,
//         ':longitude' => !empty($longitude) ? $longitude : null,
//         ':altitude' => !empty($altitude) ? $altitude : null,
//         ':ordre' => !empty($ordre) ? $ordre : null,
//         ':localite' => !empty($localite) ? $localite : null
//     ]);
//     echo "Point géodésique mis à jour avec succès.";
// } catch (Exception $e) {
//     echo "Erreur lors de la mise à jour : " . $e->getMessage();
// }



// Récupération des données avec vérification
// $id = isset($_POST['id']) ? $_POST['id'] : null;
// $nom = isset($_POST['nom']) ? $_POST['nom'] : null;
// $latitude = isset($_POST['latitude']) ? $_POST['latitude'] : null;
// $longitude = isset($_POST['longitude']) ? $_POST['longitude'] : null;
// $altitude = isset($_POST['altitude']) ? $_POST['altitude'] : null;
// $ordre = isset($_POST['ordre']) ? $_POST['ordre'] : null;


$id = $_POST['id'];
$nom = $_POST['nom'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];
$ordre = $_POST['ordre'];
$localite = $_POST['localite'];



// Vérification si l'ID est fourni
if (empty($id)) {
    die("L'ID est obligatoire.");
}

// Affiche les données pour débogage (optionnel)
echo "Données reçues :<br>";
echo "ID : $id<br>";
echo "Nom : $nom<br>";
echo "Latitude : $latitude<br>";
echo "Longitude : $longitude<br>";
echo "Ordre : $ordre<br>";

//mettre a jour les donnees dans la base
$sql = "UPDATE points_geodesique SET 
            nom = :nom, 
            x_coor = :latitude, 
            y_coor = :longitude, 
            ordre = :ordre,
            localite = :localite,

        WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute([
    ':id' => $id,
    ':nom' => $nom,
    ':x_coor' => $latitude,
    ':y_coor' => $longitude,
    ':ordre' => $ordre,
    ':localite' => $localite,
]);

echo "Les données ont été mises à jour avec succès.";

// Continuer avec la logique de mise à jour...
?>
