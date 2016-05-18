<?php

namespace ContactsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

use EntitiesBundle\Entity\User;
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
        $user = $this->container->get('security.context')->getToken()->getUser();
        $person = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('EntitiesBundle:User')
            ->find($user);
        if($person) {
            $contact = $person->getContacts();
            $encoders = array(new XmlEncoder(), new JsonEncoder());
            $normalizers = array(new ObjectNormalizer());

            $serializer = new Serializer($normalizers, $encoders);
            $contactContent = $serializer->serialize($contact, 'json');
            $contacts = $serializer->decode($contactContent, 'json');
        }else{
            $contacts = [];
        }

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
            $a = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('EntitiesBundle:User')
                ->find($user);
            $addContact = $a->addContact($contact);
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
            ->getForm();
    }
}
