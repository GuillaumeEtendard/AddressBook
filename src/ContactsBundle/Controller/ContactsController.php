<?php

namespace ContactsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use EntitiesBundle\Entity\Contacts;
use ContactsBundle\Form\ContactsType;

/**
 * Contacts controller.
 *
 */
class ContactsController extends Controller
{
    /**
     * Lists all Contacts entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();

        $contacts = $em->getRepository('EntitiesBundle:Contacts')->findBy(
            array('user_id' => $user)
        );

        return $this->render('ContactsBundle:Contacts:index.html.twig', array(
            'contacts' => $contacts,
        ));
    }

    /**
     * Creates a new contacts entity.
     *
     */
    public function newAction(Request $request)
    {
        $contact = new Contacts();
        $form = $this->createForm('ContactsBundle\Form\ContactsType', $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $user = $this->container->get('security.context')->getToken()->getUser();
            $contact->setUserId($user);
            $em->persist($contact);
            $em->flush();

            return $this->redirectToRoute('contacts_show', array('id' => $contact->getId()));
        }

        return $this->render('ContactsBundle:Contacts:new.html.twig', array(
            'contact' => $contact,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Contacts entity.
     *
     */
    public function showAction(Contacts $contacts)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();

        if($user->getId() !== $contacts->getUserId()->getId()){
            return $this->redirectToRoute('contacts_index');
        }
        $deleteForm = $this->createDeleteForm($contacts);

        return $this->render('ContactsBundle:Contacts:show.html.twig', array(
            'contacts' => $contacts,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Contacts entity.
     *
     */
    public function editAction(Request $request, Contacts $contacts)
    {
        $deleteForm = $this->createDeleteForm($contacts);
        $editForm = $this->createForm('ContactsBundle\Form\ContactsType', $contacts);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contacts);
            $em->flush();

            return $this->redirectToRoute('contacts_edit', array('id' => $contacts->getId()));
        }

        return $this->render('ContactsBundle:Contacts:edit.html.twig', array(
            'contacts' => $contacts,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Contacts entity.
     *
     */
    public function deleteAction(Request $request, Contacts $contacts)
    {
        $form = $this->createDeleteForm($contacts);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($contacts);
            $em->flush();
        }

        return $this->redirectToRoute('contacts_index');
    }

    /**
     * Creates a form to delete a Contacts entity.
     *
     * @param Contacts $contacts The contacts entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Contacts $contacts)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('contacts_delete', array('id' => $contacts->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }
}
