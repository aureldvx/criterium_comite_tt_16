<?php

namespace CTT\CriteriumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Mobile_Detect;
use CTT\CriteriumBundle\Entity\Liste;
use CTT\CriteriumBundle\Entity\Participation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ImportCsv extends Controller
{
    public function importAction()
    {
        $data = file_get_contents('http://services.lnatt.fr/cd16-liste-inscrits-csv.php');
        file_put_contents('/src/CTT/CriteriumBundle/Resources/import-liste.csv',$data);
        unset($data);

        $imports = array(); // Tableau qui va contenir les éléments extraits du fichier CSV
        $row = 0; // Représente la ligne
        // Import du fichier CSV
        if (($handle = fopen('/src/CTT/CriteriumBundle/Resources/import-liste.csv', "r")) !== FALSE) { // Lecture du fichier, à adapter
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) { // Eléments séparés par un point-virgule, à modifier si necessaire
                $num = count($data); // Nombre d'éléments sur la ligne traitée
                $row++;
                for ($c = 0; $c < $num; $c++) {
                    $imports[$row] = array(
                        "id" => $data[0],
                        "nom" => $data[1],
                        "prenom" => $data[2],
                        "password" => $data[3]
                    );
                }
            }
            fclose($handle);
        }

        $em = $this->getDoctrine()->getManager(); // EntityManager pour la base de données
        $repository = $em->getRepository('CTTCriteriumBundle:Participation');

        // Lecture du tableau contenant les utilisateurs et ajout dans la base de données
        foreach ($imports as $import) {

            // On crée un objet utilisateur
            $participant = new Participation();

            // Hydrate l'objet avec les informations provenants du fichier CSV
            $participant->setNom($import["nom"]);
            $participant->setPrenom($import["prenom"]);
            $participant->setEmail($import["mail"]);

            // Enregistrement de l'objet en vu de son écriture dans la base de données
            $em->persist($participant);

        }

        // Ecriture dans la base de données
        $em->flush();
    }

//        $handle = fopen('/src/CTT/CriteriumBundle/Resources/import-liste.csv', 'r+');
//
//        fputcsv($handle, ['id', 'nom', 'prenom', 'naissance', 'participation', 'date-choix', 'licence', 'points', 'categorie', 'echelon', 'club', 'sexe'], ';');
//
//        $em = $this->getDoctrine()->getManager();
//        $repository = $em->getRepository('CTTCriteriumBundle:Participation');
//        $results = $repository->findInscrits();
//        for ($i=0;$i<count($results);$i++) {
//            fputcsv(
//                $handle,
//                [$results[$i]['id'], $results[$i]['nom'], $results[$i]['prenom'], $results[$i]['dateNaissance']->format('d-m-Y H:i:s'), $results[$i]['participation'], $results[$i]['dateChoix']->format('d-m-Y H:i:s'), $results[$i]['licence'], $results[$i]['points'], $results[$i]['categorie'], $results[$i]['echelon'], $results[$i]['club'], $results[$i]['sexe']],
//                ';'
//            );
//        }
//        rewind($handle);
//        $content = utf8_decode(stream_get_contents($handle));
//        fclose($handle);
}