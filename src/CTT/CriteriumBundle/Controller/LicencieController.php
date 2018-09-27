<?php

namespace CTT\CriteriumBundle\Controller;

use CTT\CriteriumBundle\Entity\Participation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Mobile_Detect;
use CTT\CriteriumBundle\Entity\Liste;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class LicencieController extends Controller
{
    public function connexionAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repositoryListe = $em->getRepository('CTTCriteriumBundle:Liste');
        $repositoryPart = $em->getRepository('CTTCriteriumBundle:Participation');

        if($request->isMethod('POST'))
        {
            // Récupération du numéro de licence saisi
            $licence = $_POST['numero-licence'];

            // Récupération de la Datetime du jour
            $now = new \DateTime();

            /*
             * Comparaison par rapport aux dates limites des Tours
             */

            // Dates limites TOUR 1
            $tour1debut = new \DateTime('2018-09-20 00:01:00');
            $tour1fin1 = new \DateTime('2018-10-10 11:00:00');
            $tour1fin2 = new \DateTime('2018-10-11 23:59:00');

            // Dates limites TOUR 2
            $tour2debut = new \DateTime('2018-11-24 00:01:00');
            $tour2fin1 = new \DateTime('2018-12-05 11:00:00');
            $tour2fin2 = new \DateTime('2018-12-06 23:59:00');

            // Dates limites TOUR 3
            $tour3debut = new \DateTime('2019-01-25 00:01:00');
            $tour3fin1 = new \DateTime('2019-02-06 11:00:00');
            $tour3fin2 = new \DateTime('2019-02-07 23:59:00');

            // Dates limites TOUR 4
            $tour4debut = new \DateTime('2019-03-01 00:01:00');
            $tour4fin1 = new \DateTime('2019-03-13 11:00:00');
            $tour4fin2 = new \DateTime('2019-03-14 23:59:00');

            // Dates limites FINALES
            $tour5debut = new \DateTime('2019-05-25 00:01:00');
            $tour5fin1 = new \DateTime('2019-06-05 11:00:00');
            $tour5fin2 = new \DateTime('2019-06-06 23:59:00');

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

            $inscrit = $repositoryListe->getListeLnatt($licence, $tour);

            $dejaRepondu = $repositoryPart->getParticipants($licence, $tour);

            if($dejaRepondu === null)
            {
                switch($now)
                {
                    case ($now >= $tour1debut && $now <= $tour1fin2):
                        return $this->redirectToRoute('licencie_validation', array('licence' => $licence, 'tour' => $tour));
                        break;
                    case ($now >= $tour2debut && $now <= $tour2fin2):
                        return $this->redirectToRoute('licencie_validation', array('licence' => $licence, 'tour' => $tour));
                        break;
                    case ($now >= $tour3debut && $now <= $tour3fin2):
                        return $this->redirectToRoute('licencie_validation', array('licence' => $licence, 'tour' => $tour));
                        break;
                    case ($now >= $tour4debut && $now <= $tour4fin2):
                        return $this->redirectToRoute('licencie_validation', array('licence' => $licence, 'tour' => $tour));
                        break;
                    case ($now >= $tour5debut && $now <= $tour5fin2):
                        return $this->redirectToRoute('licencie_validation', array('licence' => $licence, 'tour' => $tour));
                        break;
                    default:
                        $request->getSession()->getFlashBag()->add('notice', 'Les validations ne sont pas encore ouvertes ou sont closes pour le tour auquel vous essayez de vous inscrire.');
                        return $this->render('@CTTCriterium/licencie/connexion.html.twig');
                        break;
                }
            }
            elseif($dejaRepondu !== null)
            {
                switch($now)
                {
                    case ($now >= $tour1debut && $now <= $tour1fin1):
                        return $this->redirectToRoute('licencie_validation', array('licence' => $licence, 'tour' => $tour));
                        break;
                    case ($now >= $tour2debut && $now <= $tour2fin1):
                        return $this->redirectToRoute('licencie_validation', array('licence' => $licence, 'tour' => $tour));
                        break;
                    case ($now >= $tour3debut && $now <= $tour3fin1):
                        return $this->redirectToRoute('licencie_validation', array('licence' => $licence, 'tour' => $tour));
                        break;
                    case ($now >= $tour4debut && $now <= $tour4fin1):
                        return $this->redirectToRoute('licencie_validation', array('licence' => $licence, 'tour' => $tour));
                        break;
                    case ($now >= $tour5debut && $now <= $tour5fin1):
                        return $this->redirectToRoute('licencie_validation', array('licence' => $licence, 'tour' => $tour));
                        break;
                    default:
                        $request->getSession()->getFlashBag()->add('notice', 'Les validations ne sont pas encore ouvertes ou sont closes pour ce tour.');
                        return $this->render('@CTTCriterium/licencie/connexion.html.twig');
                        break;
                }
            }
            if($inscrit == null)
            {
                $request->getSession()->getFlashBag()->add('notice','Vous n’êtes pas inscrit à ce tour ou participez à un niveau supérieur');
                return $this->render('@CTTCriterium/licencie/connexion.html.twig');
            }
        }
        return $this->render('@CTTCriterium/licencie/connexion.html.twig');
    }

    ///**
     //* @ParamConverter("liste", options={"mapping": {"licence": "licence"}})
     //*/
    public function validationAction($licence, $tour)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('CTTCriteriumBundle:Liste');
        $inscrit = $repository->findOneBy(array(
            'licence' => $licence,
            'tour' => $tour
        ));
        return $this->render('@CTTCriterium/licencie/validation.html.twig', array(
            'licencie' => $inscrit
        ));
    }

    public function traitementAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('CTTCriteriumBundle:Liste');

        if($request->isMethod('GET'))
        {
            $numeroLicence = $_GET['licence'];
            $dateSaisie = $_GET['naissance'];
            $participation = $_GET['participation'];
            $tour = $_GET['tour'];

            $dateFormat = preg_replace('#([0-9]{2})/([0-9]{2})/([0-9]{4})#', '$3', $dateSaisie);

            $licencie = $repository->findOneBy(array(
                'licence' => $numeroLicence,
                'tour' => $tour
            ));

            $categorie = $licencie->getCategorie();

            switch ($categorie)
            {
                case 'P':
                    $anneeMoins = 2009;
                    $anneePlus = 2018;
                    break;

                case 'B1':
                    $anneeMoins = 2008;
                    $anneePlus = 2008;
                    break;

                case 'B2':
                    $anneeMoins = 2007;
                    $anneePlus = 2007;
                    break;

                case 'M1':
                    $anneeMoins = 2006;
                    $anneePlus = 2006;
                    break;

                case 'M2':
                    $anneeMoins = 2005;
                    $anneePlus = 2005;
                    break;

                case 'C1':
                    $anneeMoins = 2004;
                    $anneePlus = 2004;
                    break;

                case 'C2':
                    $anneeMoins = 2003;
                    $anneePlus = 2003;
                    break;

                case 'J1':
                    $anneeMoins = 2002;
                    $anneePlus = 2002;
                    break;

                case 'J2':
                    $anneeMoins = 2001;
                    $anneePlus = 2001;
                    break;

                case 'J3':
                    $anneeMoins = 2000;
                    $anneePlus = 2000;
                    break;

                case 'S':
                    $anneeMoins = 1978;
                    $anneePlus = 1999;
                    break;

                case 'V1':
                    $anneeMoins = 1968;
                    $anneePlus = 1977;
                    break;

                case 'V2':
                    $anneeMoins = 1958;
                    $anneePlus = 1967;
                    break;

                case 'V3':
                    $anneeMoins = 1848;
                    $anneePlus = 1957;
                    break;

                case 'V4':
                    $anneeMoins = 1938;
                    $anneePlus = 1947;
                    break;

                case 'V5':
                    $anneeMoins = 1900;
                    $anneePlus = 1937;
                    break;
            }

            if($dateFormat >= $anneeMoins && $dateFormat <= $anneePlus)
            {
                $validation = new Participation();

                $validation->setLicence($numeroLicence);
                $validation->setNom($licencie->getNom());
                $validation->setPrenom($licencie->getPrenom());
                $validation->setSexe($licencie->getSexe());
                $validation->setPoints($licencie->getPoints());
                $validation->setCategorie($licencie->getCategorie());
                $validation->setEchelon($licencie->getEchelon());
                $validation->setClub($licencie->getClub());
                $validation->setTour($licencie->getTour());

                if($participation == 1)
                {
                    $validation->setParticipation(true);
                }else{
                    $validation->setParticipation(false);
                }

                $em->persist($validation);
                $em->flush();
            }
            else
            {
                $request->getSession()->getFlashBag()->add('notice','La date de naissance renseignée ne correspond pas à celle de ce licencié.');
                return $this->redirectToRoute('licencie_validation', array(
                    'licence' => $numeroLicence,
                    'licencie' => $licencie
                ));
            }

            $request->getSession()->getFlashBag()->add('valid','Votre choix a été enregistré !');
            return $this->redirectToRoute('licencie_connexion');
        }
    }
}