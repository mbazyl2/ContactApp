<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Contact;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ContactController extends Controller
{
    /**
     * @Route("/")
     * @Template(":default:index.html.twig")
     */
    public function indexAction()
    {
        return new Response("[]");
    }

    /**
     * @Route("/new")
     * @Template(":contact:new_form.html.twig")
     * @Method("GET")
     */
    public function newContactAction()          // akcja przekierowujaca na widok formularza
    {
        return [];
    }

    /**
     * @Route("/create")
     * @Template("::base.html.twig")
     * @Method("POST")
     */
    public function createContactAction(Request $request)           // akcja tworzaca nowy obiekt na podstawie danych z formularza
    {
        $contact = new Contact();                                   // nowy obiekt $contact klasy Contact
        $contact->setName($request->request->get('name'));
        $contact->setSurname($request->request->get("surname"));
        $contact->setNickname($request->request->get("nickname"));
        // nadanie nowemu obiektowi za pomoca setterow atrybutow przeslanych formularzem


        $em = $this->getDoctrine()->getManager();
        $em->persist($contact);
        $em->flush();
        return [];
        // narazie nie jest zwracane nic, ale akcja ta, po dodaniu obiektu do bazy bedzie przekierowywac do wyswietlenia dodanego kontaktu lub wszystkich kontaktow
    }
}
