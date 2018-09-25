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
            $licence = $_POST['numero-licence'];

            $inscrit = $repositoryListe->findOneBy(array(
                'licence' => $licence
            ));

            if($inscrit == null)
            {
                $request->getSession()->getFlashBag()->add('notice','Vous n’êtes pas inscrit au critérium ou participez à un niveau supérieur');
                return $this->render('@CTTCriterium/licencie/connexion.html.twig');
            }

            $now = new \DateTime();

            // Dates limites TOUR 1
            $tour1debut = new \DateTime('2018-09-28 00:01:00');
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

            $dejaRepondu = $repositoryPart->findOneBy(array(
                'licence' => $licence
            ));

            if($dejaRepondu === null)
            {
                switch($now)
                {
                    case ($now >= $tour1debut && $now <= $tour1fin2):
                        return $this->redirectToRoute('licencie_validation', array('licence' => $licence));
                        break;
                    case ($now >= $tour2debut && $now <= $tour2fin2):
                        return $this->redirectToRoute('licencie_validation', array('licence' => $licence));
                        break;
                    case ($now >= $tour3debut && $now <= $tour3fin2):
                        return $this->redirectToRoute('licencie_validation', array('licence' => $licence));
                        break;
                    case ($now >= $tour4debut && $now <= $tour4fin2):
                        return $this->redirectToRoute('licencie_validation', array('licence' => $licence));
                        break;
                    case ($now >= $tour5debut && $now <= $tour5fin2):
                        return $this->redirectToRoute('licencie_validation', array('licence' => $licence));
                        break;
                    default:
                        $request->getSession()->getFlashBag()->add('notice', 'Les validations ne sont pas encore ouvertes ou sont closes pour ce tour.');
                        return $this->render('@CTTCriterium/licencie/connexion.html.twig');
                        break;
                }
            }
            elseif($dejaRepondu !== null)
            {
                switch($now)
                {
                    case ($now >= $tour1debut && $now <= $tour1fin1):
                        return $this->redirectToRoute('licencie_validation', array('licence' => $licence));
                        break;
                    case ($now >= $tour2debut && $now <= $tour2fin1):
                        return $this->redirectToRoute('licencie_validation', array('licence' => $licence));
                        break;
                    case ($now >= $tour3debut && $now <= $tour3fin1):
                        return $this->redirectToRoute('licencie_validation', array('licence' => $licence));
                        break;
                    case ($now >= $tour4debut && $now <= $tour4fin1):
                        return $this->redirectToRoute('licencie_validation', array('licence' => $licence));
                        break;
                    case ($now >= $tour5debut && $now <= $tour5fin1):
                        return $this->redirectToRoute('licencie_validation', array('licence' => $licence));
                        break;
                    default:
                        $request->getSession()->getFlashBag()->add('notice', 'Les validations ne sont pas encore ouvertes ou sont closes pour ce tour.');
                        return $this->render('@CTTCriterium/licencie/connexion.html.twig');
                        break;
                }
            }
        }
        return $this->render('@CTTCriterium/licencie/connexion.html.twig');
    }

    /**
     * @ParamConverter("liste", options={"mapping": {"licence": "licence"}})
     */
    public function validationAction(Liste $liste, Request $request)
    {
        return $this->render('@CTTCriterium/licencie/validation.html.twig', array(
            'licencie' => $liste
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

            $dateFormat = preg_replace('#([0-9]{2})/([0-9]{2})/([0-9]{4})#', '$3$2$1', $dateSaisie);

            $licencie = $repository->findOneBy(array(
                'licence' => $numeroLicence
            ));

            $dateBase = explode("-", $licencie->getNaissance()->format('Y-m-d'));
            $dateBDD = $dateBase[0] . $dateBase[1] . $dateBase[2];

            $interval = $dateBDD - $dateFormat;

            if($interval !== 0)
            {
                $request->getSession()->getFlashBag()->add('notice','La date de naissance renseignée ne correspond pas à celle de ce licencié.');
                return $this->redirectToRoute('licencie_validation', array(
                    'licence' => $numeroLicence,
                    'licencie' => $licencie
                ));
            }
            else
            {
                $nomLicencie = $licencie->getNom();
                $prenomLicencie = $licencie->getPrenom();
                $naissanceLicencie = $licencie->getNaissance();
                $sexeLicencie = $licencie->getSexe();
                $pointsLicencie = $licencie->getPoints();
                $categorieLicencie = $licencie->getCategorie();
                $echelonLicencie = $licencie->getEchelon();
                $clubLicencie = $licencie->getClub();

                $validation = new Participation();

                $validation->setLicence($numeroLicence);
                $validation->setNom($nomLicencie);
                $validation->setPrenom($prenomLicencie);
                $validation->setDateNaissance($naissanceLicencie);
                $validation->setSexe($sexeLicencie);
                $validation->setPoints($pointsLicencie);
                $validation->setCategorie($categorieLicencie);
                $validation->setEchelon($echelonLicencie);
                $validation->setClub($clubLicencie);

                if($participation == 1)
                {
                    $validation->setParticipation(true);
                }else{
                    $validation->setParticipation(false);
                }

                $em->persist($validation);
                $em->flush();
            }
            $request->getSession()->getFlashBag()->add('valid','Votre choix a été enregistré !');
            return $this->redirectToRoute('licencie_connexion');
        }
    }
}