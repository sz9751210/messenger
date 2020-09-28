<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use AppBundle\Entity\Message;
use AppBundle\Entity\MessageRepository;

class MessageController extends Controller
{
    /**
     * @Route("/create-message")
     */
    public function createAction(Request $request)
    {

        $message = new Message();
        $message->setDate(new \DateTime("now"));
        $form = $this->createFormBuilder($message)
            ->add('name', TextType::class)
            ->add('text', TextareaType::class)
            ->add('save', SubmitType::class, array('label' => '新留言'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $message = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();

            return $this->redirect('/view-message/' . $message->getId());
        }

        return $this->render(
            'message/edit.html.twig',
            array('form' => $form->createView())
        );
    }
    /**
     * @Route("/view-message/{id}")
     */
    public function viewAction($id)
    {

        $message = $this->getDoctrine()
            ->getRepository('AppBundle:Message')
            ->find($id);

        if (!$message) {
            throw $this->createNotFoundException(
                '找不到留言 id: ' . $id
            );
        }

        return $this->render(
            'message/view.html.twig',
            array('message' => $message)
        );
    }
/**
* @Route("/show-messages")
*/  
public function showAction() {

    $messages = $this->getDoctrine()
      ->getRepository('AppBundle:Message')
      ->findAll();
    //抓取實體並印出全部
    $em = $this->getDoctrine()->getManager();
    $messageRepository = $em->getRepository('AppBundle:Message');
    // $messengerCount      = $em->getRepository('AppBundle:MessageRepository')->getAmountOfMessages();


    return $this->render(
      'message/show.html.twig',
      array('messages' => $messages)
      );

    
  
  }
  /**
* @Route("/delete-message/{id}")
*/ 
public function deleteAction($id) {

    $em = $this->getDoctrine()->getManager();
    $message = $em->getRepository('AppBundle:Message')->find($id);
  
    if (!$message) {
      throw $this->createNotFoundException(
      '找不到留言 id: ' . $id
      );
    }
  
    $em->remove($message);
    $em->flush();
  
    return $this->redirect('/show-messages');
  
  }
  /**
* @Route("/update-message/{id}")
*/  
public function updateAction(Request $request, $id) {

    $em = $this->getDoctrine()->getManager();
    $message = $em->getRepository('AppBundle:Message')->find($id);
  
    if (!$message) {
      throw $this->createNotFoundException(
      '找不到留言 id: ' . $id
      );
    }
  
    $form = $this->createFormBuilder($message)
      ->add('name', TextType::class)
      ->add('text', TextareaType::class)
      ->add('save', SubmitType::class, array('label' => 'Update'))
      ->getForm();
  
    $form->handleRequest($request);
  
    if ($form->isSubmitted()) {
  
      $message = $form->getData();
      $message->setDate(new \DateTime("now"));
      $em->flush();
  
      return $this->redirect('/view-message/' . $id);
  
    }
  
    return $this->render(
      'message/edit.html.twig',
      array('form' => $form->createView())
      );
  
  }
  
}
