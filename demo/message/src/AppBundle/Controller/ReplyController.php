<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use AppBundle\Entity\Reply;

class ReplyController extends Controller
{
    /**
     * @Route("/create-reply/{mid}")
     */
    public function createAction(Request $request,$mid)
    {
        $em = $this->getDoctrine()->getManager();
        $message = $em->getRepository('AppBundle:Message')->find($mid);
        $reply = new Reply();
        $message->addReply($reply);
        $reply->setMessage($mid);
        $reply->setDate(new \DateTime("now"));
        $form = $this->createFormBuilder($reply)
            ->add('name', TextType::class)
            ->add('text', TextareaType::class)
            ->add('save', SubmitType::class, array('label' => '新留言'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
           
            $reply = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($reply);
            $em->flush();

            return $this->redirect('/show-reply/' . $reply->getId());
        }

        return $this->render(
            'reply/edit.html.twig',
            array('form' => $form->createView())
        );
    }
    /**
     * @Route("/view-reply/{id}")
     */
    public function viewAction($id)
    {

        $reply = $this->getDoctrine()
            ->getRepository('AppBundle:Reply')
            ->find($id);

        if (!$reply) {
            throw $this->createNotFoundException(
                '找不到回覆 id: ' . $id
            );
        }

        return $this->render(
            'reply/view.html.twig',
            array('reply' => $reply)
        );
    }
/**
* @Route("/show-reply/{id}")
*/  
public function showAction($id) {


    $message = $this->getDoctrine()
      ->getRepository('AppBundle:Message')
      ->find($id);

    $replys = $this->getDoctrine()
      ->getRepository('AppBundle:Reply')
      ->findAll();
  
    return $this->render(
      'reply/show.html.twig',
      array('message' => $message, 'replys' => $replys)
      );
  
  }
  /**
* @Route("/delete-reply/{id}")
*/ 
public function deleteAction($mid,$id) {

    $em = $this->getDoctrine()->getManager();
    $message = $em->getRepository('AppBundle:Message')->find($mid);
    $em = $this->getDoctrine()->getManager();
    $reply = $em->getRepository('AppBundle:Reply')->find($id);
  
    if (!$reply) {
      throw $this->createNotFoundException(
      '找不到回覆 id: ' . $id
      );
    }
  
    $em->remove($reply);
    $em->flush();
  
    return $this->redirect('/show-reply/'. $message->getId());
  
  }
  /**
* @Route("/update-reply/{id}")
*/  
public function updateAction(Request $request, $id) {

    $em = $this->getDoctrine()->getManager();
    $reply = $em->getRepository('AppBundle:Reply')->find($id);
  
    if (!$reply) {
      throw $this->createNotFoundException(
      '找不到回覆 id: ' . $id
      );
    }
  
    $form = $this->createFormBuilder($reply)
      ->add('name', TextType::class)
      ->add('text', TextareaType::class)
      ->add('save', SubmitType::class, array('label' => 'Update'))
      ->getForm();
  
    $form->handleRequest($request);
  
    if ($form->isSubmitted()) {
  
      $reply = $form->getData();
      $reply->setDate(new \DateTime("now"));
      $em->flush();
  
      return $this->redirect('/view-reply/' . $id);
  
    }
  
    return $this->render(
      'reply/edit.html.twig',
      array('form' => $form->createView())
      );
  
  }
}
