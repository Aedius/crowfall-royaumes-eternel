<?php


namespace AppBundle\Controller\Front;


use AppBundle\Component\Helper\Pagination;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class UserController  extends BaseController
{
    /**
     * @Route("/user-article/{id}/{slug}/{page}", name="user_article_show", requirements={"id": "\d+"} , defaults={"page" = 1} )
     * @param $id
     * @param $slug
     * @param $page
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function userArticleAction($id, $slug, $page)
    {
        $userRepository = $this->getDoctrine()->getRepository(User::class);

        /** @var User|null $user */
        $user = $userRepository->find($id);

        if (null === $user) {
            $this->redirectToRoute('homepage');
        }

        if ($user->getUsernameCanonical() !== $slug) {
            return $this->redirectToRoute(
                $this->generateUrl(
                    'user_article_show',
                    [
                        'slug' => $user->getUsernameCanonical(),
                        'id' => $user->getId()
                    ]
                ));
        }

        $paginationSize = $this->getParameter('app.pagination');

        $articleList = $this->getDoctrine()
            ->getRepository('AppBundle:Article')
            ->getByAuthor($user, (int)$paginationSize, (int)$page);

        $articleCount = $this->getDoctrine()
            ->getRepository('AppBundle:Article')
            ->getCountByAuthor($user);

        $router = $this->get('router');
        $pagination = new Pagination();
        if ($page > 1) {
            $pagination
                ->setPreviousPageWording('Articles de "' . $user->getUsername() . '" prédédents')
                ->setPreviousPageUrl(
                    $router->generate('user_article_show', ['id' => $id, 'slug' => $slug, 'page' => $page - 1])
                );
        }
        if ($page < ceil($articleCount / $paginationSize)) {
            $pagination
                ->setNextPageWording('Articles de "' . $user->getUsername() . '" suivants')
                ->setNextPageUrl(
                    $router->generate('user_article_show', ['id' => $id, 'slug' => $slug, 'page' => $page + 1])
                );
        }

        return $this->render(':user:show-article.html.twig', [
            'user' => $user,
            'articleList' => $articleList,
            'pagination' => $pagination,

        ]);

    }
}