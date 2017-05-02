<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Address;
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

        // tworzenie formularza

        $form = $this->createForm(AddressType::class, $address,
            ["action"=> $this->generateUrl("app_address_create")]);

        return ["form"=>$form->createView()];
    }

    /**
     * @Route("/create")
     * @Template(":address:new_address.html.twig")
     */
    public function createAction(Request $request)
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        //linijka ponizej sprawdza czy formularz jest poprawnie wypelniony
        // jesli walidacja nie jest ustalona to zawsze bedzie dobrze
        // isSubmited czyli czy postem

        if ($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($address);
            $em->flush();

            return $this->redirectToRoute("app_general_welcome", ["id"=> $address->getId()]);

        }

        return ["form"=> $form->createView()];
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
