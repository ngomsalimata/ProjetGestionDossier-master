<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Notification;
use AppBundle\Form\NotificationType;

/**
 * Notification controller.
 *
 * @Route("/notification")
 */
class NotificationController extends Controller {

    /**
     * Recupère la liste de toutes les occurences de Notification.
     *
     * @Route("/", name="notification_index")
     * @Method("GET")
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $notifications = $em->getRepository('AppBundle:Notification')->findAll();

        return $this->render('notification/index.html.twig', array(
                    'notifications' => $notifications,
        ));
    }

    /**
     * Crée une nouvelle instance de Notification.
     *
     * @Route("/new", name="notification_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {
        $notification = new Notification();
        $form = $this->createForm('AppBundle\Form\NotificationType', $notification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($notification);
            $em->flush();
            $request->getSession()->getFlashBag()
                    ->set('success', 'Ajout effectué avec succés');

            return $this->redirectToRoute('notification_show', array('id' => $notification->getId()));
        }

        return $this->render('notification/new.html.twig', array(
                    'notification' => $notification,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Recupère et affiche un élément Notification.
     *
     * @Route("/{id}", name="notification_show")
     * @Method("GET")
     */
    public function showAction(Notification $notification) {
        $deleteForm = $this->createDeleteForm($notification);

        return $this->render('notification/show.html.twig', array(
                    'notification' => $notification,
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Affiche un formulaire de modification pour un element Notification existant.
     *
     * @Route("/{id}/edit", name="notification_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Notification $notification) {


        $em = $this->getDoctrine()->getManager();
        if ($notification->getEtat()) {
            $notification->setEtat(0);
        } else {
            $notification->setEtat(1);
        }
        $em->flush();
        $request->getSession()->getFlashBag()
                ->set('success', 'Modification effectuée avec succés');

        return $this->redirectToRoute('user_show', array('id' => $notification->getUser()->getId()));
    }

    /**
     * Supprime une occurence de Notification.
     *
     * @Route("/{id}", name="notification_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Notification $notification) {
        $form = $this->createDeleteForm($notification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($notification);
            $em->flush();
            $request->getSession()->getFlashBag()
                    ->set('dangers', 'Suppression reussie !!!');
        }

        return $this->redirectToRoute('notification_index');
    }

    /**
     * Supprime la selection de Notification.
     *
     * @Route("/deleteSelection", name="notification_deleteSelection")
     * @Method("POST")
     */
    public function deleteSelectionAction(Request $request) {
        $selections = $request->get('selection');
        $em = $this->getDoctrine()->getManager();
        foreach ($selections as $id => $valeur) {
            $element = $em->getRepository('AppBundle:Notification')->find($id);
            $em->remove($element);
        }
        $em->flush();

        return $this->redirectToRoute('notification_index');
    }

    /**
     * Crée un formulaire de suppression de Notification.
     *
     * @param Notification $notification The Notification entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Notification $notification) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('notification_delete', array('id' => $notification->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

}
