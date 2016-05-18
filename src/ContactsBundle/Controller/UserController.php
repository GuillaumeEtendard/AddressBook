<?php

namespace ContactsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
    public function showUserAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
        $email = trim(htmlentities($_POST['email']));
        $contacts = $em->getRepository('EntitiesBundle:User')->findOneBy(
            array('email' => $email)
        );
        var_dump($contacts);
        return $this->render('UserBundle::layout.html.twig', array(
            'contacts' => $contacts,
        ));
    }
}