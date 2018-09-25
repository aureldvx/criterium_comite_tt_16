<?php
/**
 * Created by PhpStorm.
 * User: pongisteau
 * Date: 15/09/2018
 * Time: 14:28
 */

namespace CTT\CriteriumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Mobile_Detect;
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
        $repository = $em->getRepository('CTTCriteriumBundle:Participation');
            $handle = fopen('php://memory', 'r+');

            fputcsv($handle, ['id', 'nom', 'prenom', 'naissance', 'participation', 'date-choix', 'licence', 'points', 'categorie', 'echelon', 'club', 'sexe'], ';');

            $results = $repository->findInscrits();
            for ($i=0;$i<count($results);$i++) {
                fputcsv(
                    $handle,
                    [$results[$i]['id'], $results[$i]['nom'], $results[$i]['prenom'], $results[$i]['dateNaissance']->format('d-m-Y H:i:s'), $results[$i]['participation'], $results[$i]['dateChoix']->format('d-m-Y H:i:s'), $results[$i]['licence'], $results[$i]['points'], $results[$i]['categorie'], $results[$i]['echelon'], $results[$i]['club'], $results[$i]['sexe']],
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