<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
    /**
     * @Route("/add", name="add_message",methods={"POST"} )
     */
    public function addmessage()
    {
       exit('add message');
    }
    /**
     * @Route("/edit", name="edit_message",methods={"POST"} )
     */
    public function editmessage()
    {
       exit('add message');
    }
}
