<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Entite;
use AppBundle\Form\EntiteType;

/**
 * Entite controller.
 *
 * @Route("/entite")
 */
class EntiteController extends Controller {

    /**
     * Recupère la liste de toutes les occurences de Entite.
     *
     * @Route("/", name="entite_index")
     * @Method("GET")
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();


        $entites = $em->createQuery('select e from AppBundle:Entite e, AppBundle:UserEntite ue '
                        . 'where ue.user=?1 and ue.entite=e')
                ->setParameter(1, $this->getUser())
                ->getResult();


        return $this->render('entite/index.html.twig', array(
                    'entites' => $entites,
        ));
    }

    /**
     * Crée une nouvelle instance de Entite.
     *
     * @Route("/new", name="entite_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {
        $entite = new Entite();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm('AppBundle\Form\EntiteType', $entite);
        $form->handleRequest($request);

        $mes_entites = $em->createQuery('select e from AppBundle:Entite e, AppBundle:UserEntite ee '
                        . 'where ee.entite=e and ee.user=?1 ')
                ->setParameter(1, $this->getUser())
                ->getResult();

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($entite);
            $userentite=new \AppBundle\Entity\UserEntite();
            $userentite->setEntite($entite);
            $userentite->setUser($this->getUser());
            $em->persist($userentite);
            
            $em->flush();
            $request->getSession()->getFlashBag()
                    ->set('success', 'Ajout effectué avec succés');
            $source = $request->get('source');
            if ($source) {
                return $this->redirectToRoute('entite_show', array('id' => $entite->getEntite()->getId()));
            }
            return $this->redirectToRoute('entite_show', array('id' => $entite->getId()));
        }

        return $this->render('entite/new.html.twig', array(
                    'entite' => $entite,
                    'form' => $form->createView(),
                    'mes_entites' => $mes_entites,
        ));
    }

    /**
     * Recupère et affiche un élément Entite.
     *
     * @Route("/{id}", name="entite_show")
     * @Method("GET")
     */
    public function showAction(Entite $entite) {
        $deleteForm = $this->createDeleteForm($entite);
        $em = $this->getDoctrine()->getManager();
        $sous_entites = $em->getRepository('AppBundle:Entite')->findByEntite($entite);
        $sousentite = new Entite();
        $sousentite->setEntite($entite);
        $form = $this->createForm('AppBundle\Form\EntiteType', $sousentite);
        $liste_user = $em->createQuery('select u from AppBundle:User u, AppBundle:UserEntite ue '
                        . 'where ue.user=u and ue.entite=?1 ')
                ->setParameter(1, $entite)
                ->getResult();
        $editForm = $this->createForm('AppBundle\Form\EntiteType', $entite);
        $mes_entites = $em->createQuery('select e from AppBundle:Entite e, AppBundle:UserEntite ee '
                        . 'where ee.entite=e and ee.user=?1 ')
                ->setParameter(1, $this->getUser())
                ->getResult();
        return $this->render('entite/show.html.twig', array(
                    'entite' => $entite,
                    'sous_entites' => $sous_entites,
                    'delete_form' => $deleteForm->createView(),
                    'form' => $form->createView(),
                    'users' => $liste_user,
                    'edit_form' => $editForm->createView(),
                    'mes_entites' => $mes_entites,
        ));
    }

    /**
     * Affiche un formulaire de modification pour un element Entite existant.
     *
     * @Route("/{id}/edit", name="entite_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Entite $entite) {
        $deleteForm = $this->createDeleteForm($entite);
        $editForm = $this->createForm('AppBundle\Form\EntiteType', $entite);
        $editForm->handleRequest($request);


        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entite);
            $em->flush();
            $request->getSession()->getFlashBag()
                    ->set('success', 'Modification effectuée avec succés');

            return $this->redirectToRoute('entite_show', array('id' => $entite->getId()));
        }

        return $this->render('entite/edit.html.twig', array(
                    'entite' => $entite,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Supprime une occurence de Entite.
     *
     * @Route("/{id}", name="entite_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Entite $entite) {
        $form = $this->createDeleteForm($entite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($entite);
            $em->flush();
            $request->getSession()->getFlashBag()
                    ->set('dangers', 'Suppression reussie!!!');
        }

        return $this->redirectToRoute('entite_index');
    }

    /**
     * Supprime la selection de Entite.
     *
     * @Route("/deleteSelection", name="entite_deleteSelection")
     * @Method("POST")
     */
    public function deleteSelectionAction(Request $request) {
        $selections = $request->get('selection');
        $em = $this->getDoctrine()->getManager();
        foreach ($selections as $id => $valeur) {
            $element = $em->getRepository('AppBundle:Entite')->find($id);
            $em->remove($element);
        }
        $em->flush();

        return $this->redirectToRoute('entite_index');
    }

    /**
     * Crée un formulaire de suppression de Entite.
     *
     * @param Entite $entite The Entite entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Entite $entite) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('entite_delete', array('id' => $entite->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

}
