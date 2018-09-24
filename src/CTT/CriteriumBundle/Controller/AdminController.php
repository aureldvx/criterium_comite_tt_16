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
//        if(!ini_get("auto_detect_line_endings"))
//        {
//            ini_set("auto_detect_line_endings", '1');
//        }
//
//        $em = $this->getDoctrine()->getManager();
//        $repository = $em->getRepository('CTTCriteriumBundle:Participation');
//
//        $liste = $repository->findInscrits();
//
//        $export = stream_get_contents(fopen('', 'w+'));

        $conn = $this->get('database_connection');

        $stmt = $conn->prepare('SELECT * FROM participation');
        $stmt->execute();

        $response = new StreamedResponse();
        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'text/csv');
        $response->setCallback(function() use($stmt) {
            $config = new ExporterConfig();
            $exporter = new Exporter($config);

            $exporter->export('php://output', new PdoCollection($stmt->getIterator()));
        });
        $response->send();

        return $response;
    }
}