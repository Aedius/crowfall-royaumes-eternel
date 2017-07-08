<?php

namespace AppBundle\Controller\Front;

use AppBundle\Entity\Article;
use AppBundle\Repository\ArticleRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {

        $archetypeList = [
            [
                'name' => 'Champion',
                'img' => '/assets/images/champion-500px.png',
                'url' => '#',
            ],
            [
                'name' => 'Confessor',
                'img' => '/assets/images/confessor-500px.png',
                'url' => '#',
            ],
            [
                'name' => 'Druid',
                'img' => '/assets/images/druid-500px.png',
                'url' => '#',
            ],
            [
                'name' => 'Duelist',
                'img' => '/assets/images/duelist-500px.png',
                'url' => '#',
            ],
            [
                'name' => 'Knight',
                'img' => '/assets/images/knight-500px.png',
                'url' => '#',
            ],
            [
                'name' => 'Legionnaire',
                'img' => '/assets/images/legionnaire-500px.png',
                'url' => '#',
            ],
            [
                'name' => 'Myrmidon',
                'img' => '/assets/images/myrmidon-500px.png',
                'url' => '#',
            ],
            [
                'name' => 'Ranger',
                'img' => '/assets/images/ranger-500px.png',
                'url' => '#',
            ],
            [
                'name' => 'Templar',
                'img' => '/assets/images/templar-500px.png',
                'url' => '#',
            ],
        ];

        shuffle($archetypeList);

        $articleList = $this->getDoctrine()
            ->getRepository('AppBundle:Article')
            ->findBy(
                array('published' => true),
                array('publishedAt' => 'desc'),
                5
            );


        return $this->render('index.html.twig', [
            'archetypeList' => $archetypeList,
            'articleList' => $articleList,
        ]);
    }


    /**
    @Route("/article/{id}/{slug}", name="article_show", requirements={"id": "\d+"})
     */
    public function articleAction($id, $slug)
    {
        /** @var ArticleRepository $repo */
        $articleRepository = $this->getDoctrine()->getRepository('AppBundle:Article');

        /** @var Article|null $article */
        $article = $articleRepository->find($id);

        if($article === null || !$article->getPublished()) {
            throw $this->createNotFoundException('L\'article n\'est pas disponible');
        }

        if($article->getSlug() != $slug){
            return $this->redirectToRoute($this->container->get('router')->generate(
                'article_show',
                array('slug' => $article->getSlug(),
                    'id' => $article->getId())
            ));
        }

        return $this->render('show/article.html.twig', [
            'article' => $article,
        ]);

    }
}
