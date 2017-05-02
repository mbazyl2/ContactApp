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

        $phone->setPhone($request->request->get('phone'));
        $phone->setDescription($request->request->get('description'));

        $phone->setContact($contact);
        $contact->addPhones($phone);

        $em = $this->getDoctrine()->getManager();
        $em ->persist($phone);
        $em->flush();

        return $this->redirectToRoute(
            'app_general_welcome',
            ['id' => $phone->getId()]);
    }

    /**
     * @Route("/show/{id}")
     * @Template(":phone:show_single_phone.html.twig")
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
     * @Template("phone/show_all_phones.html.twig")
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

        return $this->redirectToRoute("app_phone_showall");

    }
}