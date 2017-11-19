<?php


namespace AppBundle\Controller\Front;

use AppBundle\Entity\Category;
use AppBundle\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends Controller
{
    public function render($view, array $parameters = array(), Response $response = null): Response
    {

        /** @var CategoryRepository $categoryRepository */
        $categoryRepository = $this->getDoctrine()->getRepository(Category::class);

        $parameters = array_merge(
            $parameters,
            array(
                'menu_category' => $categoryRepository->getCategoryWithArticle(),
            )
        );

        return parent::render($view, $parameters, $response);
    }
}