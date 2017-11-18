<?php

namespace AppBundle\Controller\Front;

use AppBundle\Entity\Article;
use AppBundle\Repository\ArticleRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ArticleController extends Controller
{

    /**
     * @Route("/article/{id}/{slug}", name="article_show", requirements={"id": "\d+"})
     * @param $id
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function articleAction($id, $slug)
    {
        /** @var ArticleRepository $repo */
        $articleRepository = $this->getDoctrine()->getRepository(Article::class);

        /** @var Article|null $article */
        $article = $articleRepository->find($id);

        if ($article === null || !$article->getPublished()) {
            throw $this->createNotFoundException('L\'article n\'est pas disponible');
        }

        if ($article->getSlug() != $slug) {
            return $this->redirectToRoute(
                $this->generateUrl(
                    'article_show',
                    ['slug' => $article->getSlug(),
                        'id' => $article->getId()]
                ));
        }

        $articleSideList = $articleRepository->getAll(3,1);

        return $this->render(':article:show.html.twig', [
            'article' => $article,
            'articleSideList' => $articleSideList,
        ]);

    }

    /**
     * @Route("/tous-les-articles/{page}", name="article_all", defaults={"page" = 1} )

     * @param $page
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function allArticleAction( $page )
    {

        $paginationSize = $this->getParameter('app.pagination');

        $articleList = $this->getDoctrine()
            ->getRepository('AppBundle:Article')
            ->getAll((int)$paginationSize, (int)$page);

        $articleCount = $this->getDoctrine()
            ->getRepository('AppBundle:Article')
            ->getAllCount();

        return $this->render('article/all.html.twig', [
            'articleList' => $articleList,
            'page' => $page,
            'pageMax' => ceil($articleCount / $paginationSize),

        ]);

    }
}