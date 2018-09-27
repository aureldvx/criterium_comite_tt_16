<?php
/**
 * Created by PhpStorm.
 * User: pongisteau
 * Date: 15/09/2018
 * Time: 14:28
 */

namespace CTT\CriteriumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Goodby\CSV\Export\Standard\ExporterConfig;
use Goodby\CSV\Export\Standard\Exporter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
class AdminController extends Controller
{
    public function dashboardAction(Request $request)
    {
        return $this->render('@CTTCriterium/admin/dashboard-admin.html.twig');
    }

    public function exportAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repositoryInscrits = $em->getRepository('CTTCriteriumBundle:Participation');
        $repositoryListe = $em->getRepository('CTTCriteriumBundle:Liste');

        // Création du fichier CSV en mémoire
        $handle = fopen('php://memory', 'r+');
        // Insertion des lignes d'en-têtes du fichier
        fputcsv($handle, ['licences-LNATT', null, null, 'sexe', 'nom', 'prenom', 'licence', 'points', 'categorie', 'club', 'echelon', 'tour', 'participation', 'date-choix'], ';');

        // Récupération de la Datetime du jour
        $now = new \DateTime();

        /*
         * Création des dates limites de récupération du fichier
         */

        // Dates limites TOUR 1
        $tour1debut = new \DateTime('2018-09-01');
        $tour1fin2 = new \DateTime('2018-10-31');

        // Dates limites TOUR 2
        $tour2debut = new \DateTime('2018-11-10');
        $tour2fin2 = new \DateTime('2018-12-15');

        // Dates limites TOUR 3
        $tour3debut = new \DateTime('2019-01-15');
        $tour3fin2 = new \DateTime('2019-02-20');

        // Dates limites TOUR 4
        $tour4debut = new \DateTime('2019-02-20');
        $tour4fin2 = new \DateTime('2019-03-24');

        // Dates limites FINALES
        $tour5debut = new \DateTime('2019-05-15');
        $tour5fin2 = new \DateTime('2019-06-16');

        /*
         * Comparaison des dates pour trouver le TOUR correspondant
         */
        $tour = 0;

        switch($now)
        {
            case ($now >= $tour1debut && $now <= $tour1fin2):
                $tour = 1;
                break;
            case ($now >= $tour2debut && $now <= $tour2fin2):
                $tour = 2;
                break;
            case ($now >= $tour3debut && $now <= $tour3fin2):
                $tour = 3;
                break;
            case ($now >= $tour4debut && $now <= $tour4fin2):
                $tour = 4;
                break;
            case ($now >= $tour5debut && $now <= $tour5fin2):
                $tour = 5;
                break;
        }

        $participants = $repositoryInscrits->findInscrits($tour);
        $inscrits = $repositoryListe->findListe($tour);

        $countPart = count($participants);
        $countLigue = count($inscrits);
        //$repriseCount = $countPart;

        for($i=0;$i<$countPart;$i++)
        {
            fputcsv(
                $handle,
                [$inscrits[$i]['licence'], null, null, $participants[$i]['sexe'], $participants[$i]['nom'], $participants[$i]['prenom'], $participants[$i]['licence'], $participants[$i]['points'], $participants[$i]['categorie'], $participants[$i]['club'], $participants[$i]['echelon'], $participants[$i]['tour'], $participants[$i]['participation'], $participants[$i]['dateChoix']->format('d-m-Y H:i:s')],
                ';'
            );
        }
        for($c=$countPart;$c<$countLigue;$c++)
        {
            fputcsv(
                $handle,
                [$inscrits[$c]['licence'], null, null, null, null, null, null, null, null, null, null, null, null, null],
                ';'
            );
        }

        rewind($handle);
        $content = utf8_decode(stream_get_contents($handle));
        fclose($handle);
        return new Response($content, 200, array(
            'Content-Type' => 'application/force-download',
            'Content-Disposition' => 'attachment; filename="participants-criterium.csv"'
        ));
    }
}