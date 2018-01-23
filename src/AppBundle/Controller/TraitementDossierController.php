<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\TraitementDossier;
use AppBundle\Form\TraitementDossierType;

/**
 * TraitementDossier controller.
 *
 * @Route("/traitementdossier")
 */
class TraitementDossierController extends Controller {

    /**
     * Recupère la liste de toutes les occurences de TraitementDossier.
     *
     * @Route("/", name="traitementdossier_index")
     * @Method("GET")
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $traitementDossiers = $em->getRepository('AppBundle:TraitementDossier')->findAll();
        if ($this->getUser()->getIdgroup()->getCodegroupe() != 'SA') {
            $traitementDossiers = $em->getRepository('AppBundle:TraitementDossier')->findByUser($this->getUser());
        }

        return $this->render('traitementdossier/index.html.twig', array(
                    'traitementDossiers' => $traitementDossiers,
        ));
    }

    /**
     * Crée une nouvelle instance de TraitementDossier.
     *
     * @Route("/new", name="traitementdossier_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {
        $traitementDossier = new TraitementDossier();
        $form = $this->createForm('AppBundle\Form\TraitementDossierType', $traitementDossier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $autreTraitements = $em->getRepository('AppBundle:TraitementDossier')->findByDossier($traitementDossier->getDossier());
            foreach ($autreTraitements as $autreTraitement) {
                $autreTraitement->setEtat('Terminé');
            }
            $em->persist($traitementDossier);
            $notification = new \AppBundle\Entity\Notification();
            $notification->setDate(new \DateTime());
            $notification->setUser($traitementDossier->getUser());
            $notification->setLibelle('Assignation du dossier '.$traitementDossier->getDossier());
            $notification->setContenu("L'utilisateur ".$this->getUser()." vous a confié le traitement du "
                    . " dossier n° " . $traitementDossier->getDossier()->getId() . ". Le nom du dossier est " . $traitementDossier->getDossier() . " et "
                    . " son dégré d'importance est " . $traitementDossier->getDossier()->getDegreImportance() . '. Vous devriez terminé le traitement '
                    . ' du dossier avant le ' . $traitementDossier->getDossier()->getDateFinTraitementPrevu()->format("d/m/Y à H:i:s"));
            $notification->setDossier($traitementDossier->getDossier());
            $notification->setSource($this->getUser());
            $notification->setEtat(0);
            $em->persist($notification);
            $em->flush();
            $request->getSession()->getFlashBag()
                    ->set('success', 'Ajout effectué avec succés');
        }
        return $this->redirectToRoute('dossier_show', array('id' => $traitementDossier->getDossier()->getId()));
    }

    /**
     * Recupère et affiche un élément TraitementDossier.
     *
     * @Route("/{id}", name="traitementdossier_show")
     * @Method("GET")
     */
    public function showAction(TraitementDossier $traitementDossier) {
        $deleteForm = $this->createDeleteForm($traitementDossier);

        return $this->render('traitementdossier/show.html.twig', array(
                    'traitementDossier' => $traitementDossier,
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Affiche un formulaire de modification pour un element TraitementDossier existant.
     *
     * @Route("/{id}/edit", name="traitementdossier_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, TraitementDossier $traitementDossier) {
        $deleteForm = $this->createDeleteForm($traitementDossier);
        $editForm = $this->createForm('AppBundle\Form\TraitementDossierType', $traitementDossier);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($traitementDossier);
            $em->flush();
            $request->getSession()->getFlashBag()
                    ->set('success', 'Modification effectuée avec succés');

            return $this->redirectToRoute('traitementdossier_show', array('id' => $traitementDossier->getId()));
        }

        return $this->render('traitementdossier/edit.html.twig', array(
                    'traitementDossier' => $traitementDossier,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Supprime une occurence de TraitementDossier.
     *
     * @Route("/{id}", name="traitementdossier_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, TraitementDossier $traitementDossier) {
        $form = $this->createDeleteForm($traitementDossier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($traitementDossier);
            $em->flush();
            $request->getSession()->getFlashBag()
                    ->set('dangers', 'Suppression reussie !!!');
        }

        return $this->redirectToRoute('traitementdossier_index');
    }

    /**
     * Supprime la selection de TraitementDossier.
     *
     * @Route("/deleteSelection", name="traitementdossier_deleteSelection")
     * @Method("POST")
     */
    public function deleteSelectionAction(Request $request) {
        $selections = $request->get('selection');
        $em = $this->getDoctrine()->getManager();
        foreach ($selections as $id => $valeur) {
            $element = $em->getRepository('AppBundle:TraitementDossier')->find($id);
            $em->remove($element);
        }
        $em->flush();

        return $this->redirectToRoute('traitementdossier_index');
    }

    /**
     * Crée un formulaire de suppression de TraitementDossier.
     *
     * @param TraitementDossier $traitementDossier The TraitementDossier entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(TraitementDossier $traitementDossier) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('traitementdossier_delete', array('id' => $traitementDossier->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

}
