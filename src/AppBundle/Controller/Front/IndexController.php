<?php

namespace AppBundle\Controller\Front;

use AppBundle\Component\Helper\Pagination;
use AppBundle\Entity\Article;
use AppBundle\Entity\Quickie;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;

class IndexController extends BaseController
{

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {

        $articleList = $this->getDoctrine()
            ->getRepository(Article::class)
            ->getAll(5, 1);

        $router = $this->get('router');
        $pagination = new Pagination();
        $pagination
            ->setNextPageWording('Voir tous les articles')
            ->setNextPageUrl(
                $router->generate('article_all')
            );

        $quickie = null;

        $quickieList = $this->getDoctrine()
            ->getRepository(Quickie::class)
            ->findBy([
                'published' => true
            ]);

        if ($count = count($quickieList)) {
            if ($count === 1) {
                $quickie = reset($quickieList);
            } else {

                // change every hour.

                $quickieList = array_values($quickieList); //just to be sure
                $date = new \DateTime();
                $hour = $date->format('H');

                $quickie = $quickieList[$hour % $count];
            }
        }

        return $this->render('index.html.twig', [
            'articleList' => $articleList,
            'pagination' => $pagination,
            'quickie' => $quickie,
        ]);
    }


}
