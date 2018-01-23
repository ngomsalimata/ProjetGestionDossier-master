<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class DefaultController extends Controller {

    /**
     * @Route("/", name="homepage")
     * 
     */
    public function indexAction(Request $request) {
        return $this->render('default/index.html.twig', array(
        ));
    }

    /**
     * @Route("statistique/entiteuser", options={"expose": true},name="user_entite_statistique")
     * 
     */
    public function entiteAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $entites = $em->createQuery('select e from AppBundle:Entite e, AppBundle:UserEntite ue where '
                        . 'ue.entite=e and ue.user=?1')
                ->setParameter(1, $this->getUser())
                ->getResult();
        $serializer = $this->get('serializer');
        $entitesJson = $serializer->serialize($entites, 'json');

        return new \Symfony\Component\HttpFoundation\JsonResponse(
                $entitesJson
        );
    }

}
