<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class GeneralController extends Controller
{

    /**
     * @Route("/")
     * @Template(":general:main.html.twig")
     */
    public function welcomeAction()
    {
        $contact = $this->getDoctrine()->getRepository("AppBundle:Contact")->findAll();
        $address = $this->getDoctrine()->getRepository("AppBundle:Address")->findAll();
        return [
            "empty"=> "nic",
            "contacts" => $contact,
            "addresses" => $address
        ];
    }
}
