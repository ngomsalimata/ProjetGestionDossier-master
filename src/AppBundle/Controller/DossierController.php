<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Dossier;
use AppBundle\Form\DossierType;
use AppBundle\Entity\PieceJointe;

/**
 * Dossier controller.
 *
 * @Route("/dossier")
 */
class DossierController extends Controller {

    /**
     * Recupère la liste de toutes les occurences de Dossier.
     *
     * @Route("/", name="dossier_index")
     * @Method("GET")
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();


        $dossiers = $em->createQuery('select d from AppBundle:Dossier d, AppBundle:TraitementDossier td '
                        . 'where td.dossier=d and td.user=?1 ')
                ->setParameter(1, $this->getUser())
                ->getResult();


        return $this->render('dossier/index.html.twig', array(
                    'dossiers' => $dossiers,
        ));
    }

    /**
     * Crée une nouvelle instance de Dossier.
     *
     * @Route("/new", name="dossier_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {
        $dossier = new Dossier();
        $dossier->setDateDebutTraitement(new \DateTime());
        $dossier->setDateFinTraitementPrevu(new \DateTime());
        $dossier->setUtilisateurDerniereModification($this->getUser());
        $form = $this->createForm('AppBundle\Form\DossierType', $dossier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dossier->setDateDerniereModification(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($dossier);
            $traitementDossier = new \AppBundle\Entity\TraitementDossier();
            $traitementDossier->setDateDebut(new \DateTime());
            $traitementDossier->setDateFin(new \DateTime());
            $traitementDossier->setDossier($dossier);
            $traitementDossier->setEtat('En Cours');
            $traitementDossier->setUser($this->getUser());
            $traitementDossier->setRemarques('Ce dossier a été assigné automatiquement lors de sa création');
            $em->persist($traitementDossier);
            $em->flush();
            $request->getSession()->getFlashBag()
                    ->set('success', 'Ajout effectué avec succés');

            return $this->redirectToRoute('dossier_show', array('id' => $dossier->getId()));
        }

        return $this->render('dossier/new.html.twig', array(
                    'dossier' => $dossier,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Recupère et affiche un élément Dossier.
     *
     * @Route("/{id}", name="dossier_show")
     * @Method("GET")
     */
    public function showAction(Dossier $dossier) {
        $deleteForm = $this->createDeleteForm($dossier);
        $traitementDossier = new \AppBundle\Entity\TraitementDossier();
        $traitementDossier->setDossier($dossier);
        $traitementDossier->setDateDebut(new \DateTime());
        $traitementDossier->setDateFin(new \DateTime());
        $traitementDossier_form = $this->createForm('AppBundle\Form\TraitementDossierType', $traitementDossier);
        $editForm = $this->createForm('AppBundle\Form\DossierType', $dossier);
        $commentaire = new \AppBundle\Entity\Commentaire();
        $commentaire->setDossier($dossier);
        $commentaire->setUser($this->getUser());
        $commentaire->setDate(new \DateTime());
        $commentaire_form = $this->createForm('AppBundle\Form\CommentaireType', $commentaire);
        $em = $this->getDoctrine()->getManager();
        $commentaires = $em->createQuery('select c from AppBundle:Commentaire c '
                        . 'where c.dossier=?1 order by c.date DESC')
                ->setParameter(1, $dossier)
                ->getResult();
        $traitementDossiers = $em->createQuery('select t from AppBundle:TraitementDossier t where t.dossier=?1 '
                        . ' order by t.dateDebut DESC ')
                ->setParameter(1, $dossier)
                ->getResult();
        $users = $em->createQuery('select u from AppBundle:User u, AppBundle:UserEntite ue where ue.user=u and ue.entite in '
                        . '(select e from AppBundle:Entite e,AppBundle:UserEntite ue1 '
                        . 'where ue1.entite=e and ue1.user=?1) ')
                ->setParameter(1, $this->getUser())
                ->getResult();
        $piecejointe= new \AppBundle\Entity\PieceJointe();
        $piecejointe->setDossier($dossier);
         $today= new \DateTime();
        
        $piecejointe->setDateAssociation($today);
       
        $piecejointe_form=$this->createForm('AppBundle\Form\PieceJointeType', $piecejointe);
        $pieceJointe=$em->getRepository('AppBundle:PieceJointe')->findBy(array('dossier'=>$dossier));
        return $this->render('dossier/show.html.twig', array(
                    'dossier' => $dossier,
                    'delete_form' => $deleteForm->createView(),
                    'edit_form' => $editForm->createView(),
                    'traitementDossier_form' => $traitementDossier_form->createView(),
                    'commentaire_form' => $commentaire_form->createView(),
                    'commentaires' => $commentaires,
                    'traitementDossiers' => $traitementDossiers,
                    'users' => $users,
                     'piecejointe_form'=>$piecejointe_form->createView(),
                     'pieceJointe'=>$pieceJointe,
        ));
    }

    /**
     * Affiche un formulaire de modification pour un element Dossier existant.
     *
     * @Route("/{id}/edit", name="dossier_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Dossier $dossier) {
        $deleteForm = $this->createDeleteForm($dossier);
        $editForm = $this->createForm('AppBundle\Form\DossierType', $dossier);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($dossier);
            $users_concernes = $em->createQuery('select u from AppBundle:User u, AppBundle:TraitementDossier td '
                            . 'where td.user=u and td.dossier=?1 ')
                    ->setParameter(1, $dossier)
                    ->getResult();
            foreach ($users_concernes as $users_concerne) {
                if ($users_concerne != $this->getUser()) {
                    $notification = new \AppBundle\Entity\Notification();
                    $notification->setDate(new \DateTime());
                    $notification->setUser($users_concerne);
                    $notification->setLibelle('Modification du dossier ' . $dossier);
                    $notification->setContenu("L'utilisateur " . $this->getUser() . " a apporté des modification sur le "
                            . " dossier n° " . $dossier->getId() . ". ayant pour nom de dossier: " . $dossier . ". "
                            . "Son dégré d'importance est " . $dossier->getDegreImportance() . '. Afficher le dossier pour voir les modifications '
                            . 'apportées. Nota Béné: Vous avez eu à travailler sur ce dossier, c\'est la raison pour laquelle vous êtes notifiés à chaque opération importante. ');
                    $notification->setDossier($dossier);
                    $notification->setSource($this->getUser());
                    $notification->setEtat(0);
                    $em->persist($notification);
                }
            }

            $em->flush();
            $request->getSession()->getFlashBag()
                    ->set('success', 'Modification effectuée avec succés');

            return $this->redirectToRoute('dossier_show', array('id' => $dossier->getId()));
        }

        return $this->render('dossier/edit.html.twig', array(
                    'dossier' => $dossier,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Supprime une occurence de Dossier.
     *
     * @Route("/{id}", name="dossier_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Dossier $dossier) {
        $form = $this->createDeleteForm($dossier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($dossier);
             $users_concernes = $em->createQuery('select u from AppBundle:User u, AppBundle:TraitementDossier td '
                            . 'where td.user=u and td.dossier=?1 ')
                    ->setParameter(1, $dossier)
                    ->getResult();
            foreach ($users_concernes as $users_concerne) {
                if ($users_concerne != $this->getUser()) {
                    $notification = new \AppBundle\Entity\Notification();
                    $notification->setDate(new \DateTime());
                    $notification->setUser($users_concerne);
                    $notification->setLibelle('Suppression du dossier ' . $dossier);
                    $notification->setContenu("L'utilisateur " . $this->getUser() . " a supprimé le "
                            . " dossier n° " . $dossier->getId() . ". ayant pour nom du dossier: " . $dossier . ". "
                            . " Son dégré d'importance est " . $dossier->getDegreImportance() . '. Nous vous alertons parce que vous avez eu à travailler sur ce dossier. ');
                    $notification->setDossier($dossier);
                    $notification->setSource($this->getUser());
                    $notification->setEtat(0);
                    $em->persist($notification);
                }
            }
            $em->flush();
            $request->getSession()->getFlashBag()
                    ->set('dangers', 'Suppression reussie !!!');
        }

        return $this->redirectToRoute('dossier_index');
    }

    /**
     * Supprime la selection de Dossier.
     *
     * @Route("/deleteSelection", name="dossier_deleteSelection")
     * @Method("POST")
     */
    public function deleteSelectionAction(Request $request) {
        $selections = $request->get('selection');
        $em = $this->getDoctrine()->getManager();
        foreach ($selections as $id => $valeur) {
            $element = $em->getRepository('AppBundle:Dossier')->find($id);
            $em->remove($element);
            $dossier=$element;
             $users_concernes = $em->createQuery('select u from AppBundle:User u, AppBundle:TraitementDossier td '
                            . 'where td.user=u and td.dossier=?1 ')
                    ->setParameter(1, $element)
                    ->getResult();
            foreach ($users_concernes as $users_concerne) {
                if ($users_concerne != $this->getUser()) {
                    $notification = new \AppBundle\Entity\Notification();
                    $notification->setDate(new \DateTime());
                    $notification->setUser($users_concerne);
                    $notification->setLibelle('Suppression du dossier ' . $dossier);
                    $notification->setContenu("L'utilisateur " . $this->getUser() . " a supprimé le "
                            . " dossier n° " . $dossier->getId() . ". ayant pour nom du dossier: " . $dossier . ". "
                            . " Son dégré d'importance est " . $dossier->getDegreImportance() . '. Nous vous alertons parce que vous avez eu à travailler sur ce dossier. ');
                    $notification->setDossier($dossier);
                    $notification->setSource($this->getUser());
                    $notification->setEtat(0);
                    $em->persist($notification);
                }
            }
        }
        $em->flush();

        return $this->redirectToRoute('dossier_index');
    }

    /**
     * Crée un formulaire de suppression de Dossier.
     *
     * @param Dossier $dossier The Dossier entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Dossier $dossier) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('dossier_delete', array('id' => $dossier->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

}
