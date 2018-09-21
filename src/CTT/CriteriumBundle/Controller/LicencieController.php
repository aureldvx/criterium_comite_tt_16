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
        $repository = $em->getRepository('CTTCriteriumBundle:Liste');

        if($request->isMethod('POST'))
        {
            $licence = $_POST['numero-licence'];

            $inscrit = $repository->findOneBy(array(
                'licence' => $licence
            ));

            if($inscrit == null)
            {
                $request->getSession()->getFlashBag()->add('notice','Vous n’êtes pas inscrit au critérium ou participez à un niveau supérieur');
            }
            else
            {
                return $this->redirectToRoute('licencie_validation', array('licence' => $licence));
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

                $validation = new Participation();

                $validation->setLicence($numeroLicence);
                $validation->setNom($nomLicencie);
                $validation->setPrenom($prenomLicencie);
                $validation->setDateNaissance($naissanceLicencie);

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