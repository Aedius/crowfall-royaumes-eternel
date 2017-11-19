<?php

namespace AppBundle\Controller\Front;

use AppBundle\Component\Helper\Pagination;
use AppBundle\Entity\Category;
use AppBundle\Repository\CategoryRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CategoryController extends Controller
{
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


        $router = $this->get('router');
        $pagination = new Pagination();
        if ($page > 1) {
            $pagination
                ->setPreviousPageWording('Articles "'.$category->getName().'" prédédents')
                ->setPreviousPageUrl(
                    $router->generate('category_show', ['id' => $id, 'slug' => $slug, 'page' => $page - 1])
                );
        }
        if ($page < ceil($articleCount / $paginationSize)) {
            $pagination
                ->setNextPageWording('Articles "'.$category->getName().'" suivants')
                ->setNextPageUrl(
                    $router->generate('category_show', ['id' => $id, 'slug' => $slug, 'page' => $page + 1])
                );
        }

        return $this->render(':category:show.html.twig', [
            'category' => $category,
            'articleList' => $articleList,
            'pagination' => $pagination,

        ]);

    }
}