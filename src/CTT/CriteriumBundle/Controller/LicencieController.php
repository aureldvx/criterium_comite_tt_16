<?php

namespace CTT\CriteriumBundle\Controller;

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
        return new Response('Votre numéro de licence est :' . $liste->getLicence() . '.');
        //return $this->render('@CTTCriterium/licencie/validation.html.twig');
    }
}