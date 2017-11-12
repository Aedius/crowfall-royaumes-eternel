<?php

namespace AppBundle\Controller\Front;

use AppBundle\Entity\Article;
use AppBundle\Entity\Category;
use AppBundle\Repository\ArticleRepository;
use AppBundle\Repository\CategoryRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
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

        return $this->render('index.html.twig', [
            'articleList' => $articleList,
        ]);
    }

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

        return $this->render(':article:show.html.twig', [
            'article' => $article,
        ]);

    }

    /**
     * @Route("/categorie/{id}/{slug}/{page}", name="category_show", requirements={"id": "\d+"} , defaults={"page" = 1} )
     * @param $id
     * @param $slug
     * @param $page
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function categoryAction($id, $slug, $page)
    {
        /** @var CategoryRepository $repo */
        $categoryRepository = $this->getDoctrine()->getRepository(Category::class);

        /** @var Category|null $category */
        $category = $categoryRepository->find($id);

        if (null === $category) {
            $this->redirectToRoute('homepage');
        }

        if ($category->getSlug() !== $slug) {
            return $this->redirectToRoute(
                $this->generateUrl(
                    'category_show',
                    [
                        'slug' => $category->getSlug(),
                        'id' => $category->getId()
                    ]
                ));
        }

        $paginationSize = $this->getParameter('app.pagination');

        $articleList = $this->getDoctrine()
            ->getRepository('AppBundle:Article')
            ->getByCategory($category, (int)$paginationSize, (int)$page);

        $articleCount = $this->getDoctrine()
            ->getRepository('AppBundle:Article')
            ->getCountByCategory($category);

        return $this->render(':category:show.html.twig', [
            'category' => $category,
            'articleList' => $articleList,
            'page' => $page,
            'pageMax' => ceil($articleCount / $paginationSize),

        ]);

    }
}
