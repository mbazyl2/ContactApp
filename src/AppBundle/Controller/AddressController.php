<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Address;
use AppBundle\Entity\Contact;
use AppBundle\Form\AddressType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/address")
 */
class AddressController extends Controller
{

    /**
     * @Route("/new")
     * @Template(":address:new_address.html.twig")
     */
    public function newAction()
    {
        $address = new Address(); // tworzy nowy objekt adres klasy Address
        $contacts = $this->getDoctrine()->getRepository("AppBundle:Contact")->findAll();
        // tworzenie formularza

        $form = $this->createForm(AddressType::class, $address,
            ["action"=> $this->generateUrl("app_address_create")]);

        return [
                    "form" => $form->createView(),
                    "contacts" =>  $contacts
                ];
    }

    /**
     * @Route("/create")
     * @Template(":address:new_address.html.twig")
     */
    public function createAction(Request $request)
    {
        // w argumencie obiektu przekazujemy metode request aby z posta pobrac dane
        $contact = $this->getDoctrine()->getRepository("AppBundle:Contact")
            ->find($request->request->get("contact_id"));

        if(!$contact){
            throw $this->createNotFoundException("Contact not found");
        }


        $address = new Address();

        $address->setStreet($request->request->get('street'));
        $address->setNumber($request->request->get('number'));
        $address->setCity($request->request->get('city'));

        $address->setContact($contact);
        $contact->addBook($address);

        $em = $this->getDoctrine()->getManager();
        $em ->persist($book);
        $em->flush();

        return $this->redirectToRoute(
            'app_general_welcome',
            ['id' => $address->getId()]);
        }

    /**
     * @Route("/show/{id}")
     * @Template(":address:show_single_address.html.twig")
     */
    public function showOneAction($id)
        {
            $address = $this->getDoctrine()->getRepository("AppBundle:Address")->find($id);
            if(!$address){
                throw $this->createNotFoundException("Address with id $id does not exists");
            }
            return ["address"=>$address];
         }

    /**
     * @Route("/showAll/")
     * @Template(":address:show_all_adresses.html.twig")
     */
    public function showAllAction()     //akcja wyswietlajaca wszystkie adresy
    {
        $address = $this->getDoctrine()->getRepository("AppBundle:Address")->findAll();
        return ["addresses" => $address];
    }

    /**
     *@Route("/delete/{id}")
     * @Method("DELETE")
     */
    public function deleteAction($id)
    {
        $address = $this->getDoctrine()->getRepository("AppBundle:Address")->find($id);

        if(!$address){
            throw  $this->createNotFoundException("Contact not found");
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($address);
        $em->flush();

        return $this->redirectToRoute("app_address_showall");

    }
}
