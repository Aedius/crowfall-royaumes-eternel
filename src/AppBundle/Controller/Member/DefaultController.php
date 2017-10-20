<?php

namespace AppBundle\Controller\Member;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/mon-compte", name="member_home")
     */
    public function indexAction()
    {


        return $this->render('member/index.html.twig');
    }

}