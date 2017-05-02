<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Phone;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/phone")
 */
class PhoneController extends Controller
{

    /**
     * @Route("/new")
     * @Template(":phone:new_phone.html.twig")
     * @Method("GET")
     */
    public function newAction()
    {

        $contacts = $this->getDoctrine()->getRepository("AppBundle:Contact")->findAll();

        return [
            "contacts" =>  $contacts
        ];
    }

    /**
     * @Route("/create")
     * @Method("POST")
     */
    public function createAction(Request $request)
    {
        $contact = $this->getDoctrine()->getRepository("AppBundle:Contact")
            ->find($request->request->get("contact_id"));

        if(!$contact){
            throw $this->createNotFoundException("Contact not found");
        }

        $phone = new Phone();

        $phone->setPhone($request->request->get('street'));
        $phone->setDescription($request->request->get('number'));

        $phone->setContact($contact);
        $contact->addAddress($phone);

        $em = $this->getDoctrine()->getManager();
        $em ->persist($address);
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
        $phone = $this->getDoctrine()->getRepository("AppBundle:Phone")->find($id);
        if(!$phone){
            throw $this->createNotFoundException("Phone with id $id does not exists");
        }
        return ["phone"=>$phone];
    }

    /**
     * @Route("/showAll/")
     * @Template(":address:show_all_adresses.html.twig")
     */
    public function showAllAction()     //akcja wyswietlajaca wszystkie adresy
    {
        $phone = $this->getDoctrine()->getRepository("AppBundle:Phone")->findAll();
        return ["phones" => $phone];
    }

    /**
     *@Route("/delete/{id}")
     * @Method("DELETE")
     */
    public function deleteAction($id)
    {
        $phone = $this->getDoctrine()->getRepository("AppBundle:Phone")->find($id);

        if(!$phone){
            throw  $this->createNotFoundException("Phone not found");
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($phone);
        $em->flush();

        return $this->redirectToRoute("app_address_showall");

    }
}