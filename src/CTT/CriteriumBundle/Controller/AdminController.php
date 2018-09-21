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
use League\Csv\Writer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
class AdminController extends Controller
{
    public function dashboardAction(Request $request)
    {
        return $this->render('@CTTCriterium/admin/dashboard-admin.html.twig');
    }

    public function exportAction()
    {
        if(!ini_get("auto_detect_line_endings"))
        {
            ini_set("auto_detect_line_endings", '1');
        }

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('CTTCriteriumBundle:Participation');

        $sth = $repository->findInscrits();
        $csv = Writer::createFromFileObject(new \SplTempFileObject());
        //$csv->insertOne(['id', 'nom', 'prenom', 'dateNaissance', 'participation', 'dateChoix', 'licence', 'points', 'categorie', 'echelon', 'club']);
        $csv->insertAll(new \ArrayIterator($sth));
        return $csv->output('participants-criterium.csv');



//        return new Response($csv->getContent(), 200, [
//            'Content-Encoding' => 'none',
//            'Content-Type' => 'text/csv; charset=UTF-8',
//            'Content-Disposition' => 'attachment; filename="participants-criterium.csv"',
//            'Content-Description' => 'File Transfer',
//        ]);

//        $flush_threshold = 1000;
//        $content_callback = function() use($csv, $flush_threshold) {
//            foreach ($csv->chunk(1024) as $offset => $chunk)
//            {
//                echo $chunk;
//                if($offset % $flush_threshold === 0)
//                {
//                    flush();
//                }
//            }
//        };
//
//        $response = new StreamedResponse();
//        $response->headers->set('Content-Encoding', 'none');
//        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
//
//        $disposition = $response->headers->makeDisposition(
//            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
//            'participants-criterium.csv'
//        );
//
//        $response->headers->set('Content-Disposition', $disposition);
//        $response->headers->set('Content-Description', 'File Transfer');
//        $response->setCallback($content_callback);
//        $response->send();
    }
}