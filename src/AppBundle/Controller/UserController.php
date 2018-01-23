<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;

/**
 * User controller.
 *
 * @Route("/user")
 */
class UserController extends Controller {

    /**
     * Recupère la liste de toutes les occurences de User.
     *
     * @Route("/", name="user_index")
     * @Method("GET")
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $users = $em->createQuery('select u from AppBundle:User u, AppBundle:UserEntite ue where ue.user=u and ue.entite in '
                        . '(select e from AppBundle:Entite e,AppBundle:UserEntite ue1 '
                        . 'where ue1.entite=e and ue1.user=?1) ')
                ->setParameter(1, $this->getUser())
                ->getResult();


        return $this->render('user/index.html.twig', array(
                    'users' => $users,
        ));
    }

    /**
     * Crée une nouvelle instance de User.
     *
     * @Route("/new", name="user_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $user = new User();
        $form = $this->createForm('AppBundle\Form\UserType', $user);
        $form->handleRequest($request);
        $mes_entites = $em->createQuery('select e from AppBundle:Entite e, AppBundle:UserEntite ee '
                        . 'where ee.entite=e and ee.user =?1 ')
                ->setParameter(1, $this->getUser())
                ->getResult();

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $fosuserfosgroup = new \AppBundle\Entity\FosUserUserGroup();
            $fosuserfosgroup->setUser($user);
            $fosuserfosgroup->setGroup($user->getIdgroup());
            $em->persist($fosuserfosgroup);
            $em->flush();
            $request->getSession()->getFlashBag()
                    ->set('success', 'Ajout effectué avec succés');

            return $this->redirectToRoute('user_show', array('id' => $user->getId()));
        }

        return $this->render('user/new.html.twig', array(
                    'user' => $user,
                    'form' => $form->createView(),
                    'mes_entites' => $mes_entites,
        ));
    }

    /**
     * Recupère et affiche un élément User.
     *
     * @Route("/{id}", name="user_show")
     * @Method("GET")
     */
    public function showAction(User $user) {
        $deleteForm = $this->createDeleteForm($user);
        $em = $this->getDoctrine()->getManager();
        $userentites = $em->createQuery('select e from AppBundle:Entite e,AppBundle:UserEntite ue '
                        . 'where ue.entite=e and ue.user=?1')
                ->setParameter(1, $user)
                ->getResult();
        $editForm = $this->createForm('AppBundle\Form\UserType', $user);
        $notifications = $em->createQuery('select n from AppBundle:Notification n where n.user=?1 order by n.date DESC')
                ->setParameter(1, $user)
                ->setMaxResults(20)
                ->getResult();
        $dossier_actuels = $em->createQuery('select d from AppBundle:Dossier d, AppBundle:TraitementDossier td '
                        . 'where td.dossier=d and td.user=?1 and td.etat= ?2 ')
                ->setParameter(1, $user)
                ->setParameter(2, 'En Cours')
                ->getResult();
        $mes_entites = $em->createQuery('select e from AppBundle:Entite e, AppBundle:UserEntite ee '
                        . 'where ee.entite=e and ee.user =?1 ')
                ->setParameter(1, $user)
                ->getResult();


        return $this->render('user/show.html.twig', array(
                    'user' => $user,
                    'delete_form' => $deleteForm->createView(),
                    'userentites' => $userentites,
                    'edit_form' => $editForm->createView(),
                    'notifications' => $notifications,
                    'dossier_actuels' => $dossier_actuels,
                    'mes_entites' => $mes_entites,
            
        ));
    }

    /**
     * Affiche un formulaire de modification pour un element User existant.
     *
     * @Route("/{id}/edit", name="user_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, User $user) {
        $deleteForm = $this->createDeleteForm($user);
        $editForm = $this->createForm('AppBundle\Form\UserType', $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $fosuserfosgroup = $em->createQuery('select f from AppBundle:FosUserUserGroup f where f.user=?1 ')
                    ->setParameter(1, $user)
                    ->setMaxResults(1)
                    ->getResult();
            if (!$fosuserfosgroup) {
                $fosuserfosgroup = new \AppBundle\Entity\FosUserUserGroup();
                $fosuserfosgroup->setUser($user);
                $em->persist($fosuserfosgroup);
            }
            $fosuserfosgroup[0]->setGroup($user->getIdgroup());
            $em->flush();
            $request->getSession()->getFlashBag()
                    ->set('success', 'Modification effectuée avec succés');

            return $this->redirectToRoute('user_show', array('id' => $user->getId()));
        }

        return $this->render('user/edit.html.twig', array(
                    'user' => $user,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Supprime une occurence de User.
     *
     * @Route("/{id}", name="user_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, User $user) {
        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
            $request->getSession()->getFlashBag()
                    ->set('dangers', 'Suppression reussie !!!');
        }

        return $this->redirectToRoute('user_index');
    }

    /**
     * Supprime la selection de User.
     *
     * @Route("/deleteSelection", name="user_deleteSelection")
     * @Method("POST")
     */
    public function deleteSelectionAction(Request $request) {
        $selections = $request->get('selection');
        $em = $this->getDoctrine()->getManager();
        foreach ($selections as $id => $valeur) {
            $element = $em->getRepository('AppBundle:User')->find($id);
            $em->remove($element);
        }
        $em->flush();

        return $this->redirectToRoute('user_index');
    }

    /**
     * Crée un formulaire de suppression de User.
     *
     * @param User $user The User entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(User $user) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('user_delete', array('id' => $user->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

}
