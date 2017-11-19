<?php

namespace AppBundle\Controller\Front;

use AppBundle\Component\Helper\Pagination;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends BaseController
{

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {

        $articleList = $this->getDoctrine()
            ->getRepository('AppBundle:Article')
            ->findBy(
                array('published' => true),
                array('publishedAt' => 'desc'),
                5
            );

        $router = $this->get('router');
        $pagination = new Pagination();
        $pagination
            ->setNextPageWording('Voir tous les articles')
            ->setNextPageUrl(
                $router->generate('article_all')
            );


        return $this->render('index.html.twig', [
            'articleList' => $articleList,
            'pagination' => $pagination,
        ]);
    }


}
