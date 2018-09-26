<?php

namespace importCron;

require 'database.php';

// Mise en mémoire du fichier CSV distant
$data = file('http://services.lnatt.fr/cd16-liste-inscrits-csv.php');
// Initialisation du tableau qui contiendra les données
$imports = [];

$i = 0;
unset($data[0]);
unset($data[1]);
$data = array_values($data);

foreach($data as $import)
{
    $import = utf8_encode($import);
    $explosion = explode(',', $import);
    if(in_array($explosion[2], $imports))
    {
        unset($import);
    }else
    {
        $imports[$i]['nom'] = $explosion[0];
        $imports[$i]['prenom'] = $explosion[1];
        $imports[$i]['licence'] = $explosion[2];
        $imports[$i]['points'] = $explosion[3];
        $imports[$i]['club'] = $explosion[4];
        $imports[$i]['echelon'] = $explosion[5];
        $imports[$i]['tour'] = $explosion[6];
        $imports[$i]['categorie'] = $explosion[7];
        $imports[$i]['sexe'] = $explosion[8];
    }
    $i++;
}

$db = \Database::connect();
$statement = $db->query('DELETE * FROM liste');
foreach($imports as $inscrit)
{

    $statement = $db->prepare('INSERT INTO liste (licence,nom,prenom,points,categorie,echelon,club,sexe,tour) values(?,?,?,?,?,?,?,?,?)');
    $statement->execute(array($inscrit['licence'], $inscrit['nom'], $inscrit['prenom'], $inscrit['points'], $inscrit['categorie'], $inscrit['echelon'], $inscrit['club'], $inscrit['sexe'], $inscrit['tour']));
}
\Database::disconnect();