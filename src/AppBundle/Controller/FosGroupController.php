<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\FosGroup;
use AppBundle\Form\FosGroupType;

/**
 * FosGroup controller.
 *
 * @Route("/fosgroup")
 */
class FosGroupController extends Controller {

    public function __construct() {
        $this->droits = array("DOSSIER", "FOSGROUP", "FOSUSER", "COMMENTAIRE", "ENTITE", "MESSAGE",
            "NOTIFICATION", "PIECEJOINTE",
            "TRAITEMENTDOSSIER", "USERENTITE");
    }

    /**
     * Lists all FosGroup entities.
     *
     * @Route("/", name="fosgroup")
     * @Method("GET")
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:FosGroup')->findAll();


        return $this->render('fosgroup/index.html.twig', array(
                    'entities' => $entities,
        ));
    }

    /**
     * Creates a new FosGroup entity.
     *
     * @Route("/", name="fosgroup_create")
     * @Method("POST")
     */
    public function createAction(Request $request) {
        //Connexion avec Mysql
        $em = $this->getDoctrine()->getManager()->getConnection();

        //identifiant de l'utilisateur
        $idgroupe = $request->get('id');
        $nomGroupe = $request->get('nom');
        $roles = serialize($request->get('roles'));

        //bouton submit
        $ajout = $request->get('ajoutGroupe');
        $modifier = $request->get('modifierGroupe');

        if (isset($ajout)) {
            //ntegrity constraint violation
            $recherche = $em->prepare("SELECT * FROM fos_group WHERE name =:name");
            $recherche->execute(array('name' => $nomGroupe));
            $ligne = $recherche->fetchColumn();

            if ($ligne == 0) {
                //Insertion
                $requete = $em->prepare("INSERT into fos_group(name, roles) VALUES(:name,:roles)");
                $requete->execute(array('name' => $nomGroupe, 'roles' => $roles));

                //Identifiant de l'insertion
                $id = $em->lastInsertId();

                $route = $this->redirect($this->generateUrl('fosgroup_show', array('id' => $id)));
            } else {
                $message = "Le groupe $nomGroupe existe déjà !!!";
                $route = $this->render('fosgroup/new.html.twig', array(
                    'error' => $message,
                    'droits' => $this->droits,
                ));
            }
        }

        if (isset($modifier)) {
            //Insertion
            $requete = $em->prepare("UPDATE fos_group SET roles =:roles WHERE id=:id");
            $requete->execute(array('id' => $idgroupe, 'roles' => $roles));

            $route = $this->redirect($this->generateUrl('fosgroup_show', array('id' => $idgroupe)));
        }

        //redirection
        return $route;
    }

    /**
     * Creates a form to create a FosGroup entity.
     *
     * @param FosGroup $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(FosGroup $entity) {
        $form = $this->createForm(new FosGroupType(), $entity, array(
            'action' => $this->generateUrl('fosgroup_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new FosGroup entity.
     *
     * @Route("/new", name="fosgroup_new")
     * @Method("GET")
     */
    public function newAction() {

        return $this->render('fosgroup/new.html.twig', array(
                    'droits' => $this->droits,
        ));
    }

    /**
     * Finds and displays a FosGroup entity.
     *
     * @Route("/{id}", name="fosgroup_show")
     * @Method("GET")
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:FosGroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FosGroup entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        return $this->render('fosgroup/show.html.twig', array(
                    'title' => 'FosGroup ' . $entity,
                    'entity' => $entity,
                    'droits' => $this->droits,
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing FosGroup entity.
     *
     * @Route("/{id}/edit", name="fosgroup_edit")
     * @Method("GET")
     */
    public function editAction($id) {

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:FosGroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FosGroup entity.');
        }

        return $this->render('fosgroup/edit.html.twig', array(
                    'title' => 'Edition FosGroup ' . $entity,
                    'id' => $id,
                    'entity' => $entity,
                    'droits' => $this->droits,
        ));
    }

    /**
     * Creates a form to edit a FosGroup entity.
     *
     * @param FosGroup $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(FosGroup $entity) {
        $form = $this->createForm(new FosGroupType(), $entity, array(
            'action' => $this->generateUrl('fosgroup_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing FosGroup entity.
     *
     * @Route("/{id}", name="fosgroup_update")
     * @Method("PUT")
     * @Template("AppBundle:FosGroup:edit.html.twig")
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:FosGroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FosGroup entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('fosgroup_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a FosGroup entity.
     *
     * @Route("/{id}/del", name="fosgroup_delete")
     */
    public function deleteAction( $id) {
     
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:FosGroup')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find FosGroup entity.');
            }

            $em->remove($entity);
            $em->flush();
      

        return $this->redirect($this->generateUrl('fosgroup'));
    }

    /**
     * Creates a form to delete a FosGroup entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('fosgroup_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm()
        ;
    }

}
