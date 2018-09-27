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
    $explosion = explode(',', $import);

    $imports[$i]['nom'] = preg_replace('#\n|\t|\r| #', '',$explosion[0]);
    $imports[$i]['prenom'] =  preg_replace('#\n|\t|\r| #', '',$explosion[1]);
    $imports[$i]['licence'] =  preg_replace('#\n|\t|\r| #', '',$explosion[2]);
    $imports[$i]['points'] =  preg_replace('#\n|\t|\r| #', '',$explosion[3]);
    $imports[$i]['club'] =  preg_replace('#\n|\t|\r|#', '',$explosion[4]);
    $imports[$i]['echelon'] =  preg_replace('#\n|\t|\r| #', '',$explosion[5]);
    $imports[$i]['tour'] =  preg_replace('#\n|\t|\r|Tour #', '',$explosion[6]);
    $imports[$i]['categorie'] =  preg_replace('#\n|\t|\r| #', '',$explosion[7]);
    $imports[$i]['sexe'] =  preg_replace('#\n|\t|\r| #', '',$explosion[8]);
    $i++;
}

$db = \Database::connect();
$statement = $db->query('DELETE FROM liste');
\Database::disconnect();

$db = \Database::connect();
foreach($imports as $inscrit)
{
    $statement = $db->prepare('INSERT INTO liste (licence,nom,prenom,points,categorie,echelon,club,sexe,tour) values(?,?,?,?,?,?,?,?,?)');
    $statement->execute(array($inscrit['licence'], $inscrit['nom'], $inscrit['prenom'], $inscrit['points'], $inscrit['categorie'], $inscrit['echelon'], $inscrit['club'], $inscrit['sexe'], $inscrit['tour']));
}
\Database::disconnect();
