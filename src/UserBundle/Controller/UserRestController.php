<?php

namespace UserBundle\Controller;

use EntitiesBundle\Entity\User;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class UserRestController extends Controller
{
    public function getUserAction($username){
        $person = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('EntitiesBundle:User')
            ->findOneById($username);

        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());

        $serializer = new Serializer($normalizers, $encoders);
       // $contactContent = $serializer->serialize($contact, 'json');
        $jsonContent = $serializer->serialize($person, 'json');
        return new Response($jsonContent);
    }

    public function getUsersAction(){
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT u.username,u.email
    FROM EntitiesBundle:User u
    ORDER BY u.id ASC'
        );
        $products = $query->getResult();

        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());

        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent = $serializer->serialize($products, 'json');

        return new Response($jsonContent);
    }
}