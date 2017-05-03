<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Mail;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mail")
 */
class MailController extends Controller
{

    /**
     * @Route("/new")
     * @Template(":mail:new_mail.html.twig")
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

        $mail = new Mail();

        $mail->setMail($request->request->get('mail'));
        $mail->setDescription($request->request->get('description'));

        $mail->setContact($contact);
        $contact->addMails($mail);

        $em = $this->getDoctrine()->getManager();
        $em ->persist($mail);
        $em->flush();

        return $this->redirectToRoute(
            'app_general_welcome',
            ['id' => $mail->getId()]);
    }

    /**
     * @Route("/show/{id}")
     * @Template(":mail:show_single_mail.html.twig")
     */
    public function showOneAction($id)
    {
        $mail = $this->getDoctrine()->getRepository("AppBundle:Mail")->find($id);
        if(!$mail){
            throw $this->createNotFoundException("Mail with id $id does not exists");
        }
        return ["mail"=>$mail];
    }

    /**
     * @Route("/showAll/")
     * @Template(":mail:show_all_mails.html.twig")
     */
    public function showAllAction()
    {
        $mail = $this->getDoctrine()->getRepository("AppBundle:Mail")->findAll();
        return ["mails" => $mail];
    }

    /**
     *@Route("/delete/{id}")
     * @Method("DELETE")
     */
    public function deleteAction($id)
    {
        $mail = $this->getDoctrine()->getRepository("AppBundle:Mail")->find($id);

        if(!$mail){
            throw  $this->createNotFoundException("Mail not found");
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($mail);
        $em->flush();

        return $this->redirectToRoute("app_mail_showall");

    }
}