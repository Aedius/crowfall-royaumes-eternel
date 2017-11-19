<?php

namespace AppBundle\Controller\Front;

use AppBundle\Component\Helper\Pagination;
use AppBundle\Entity\Article;
use AppBundle\Entity\Comment;
use AppBundle\Repository\ArticleRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ArticleController extends Controller
{

    /**
     * @Route("/article/{id}/{slug}", name="article_show", requirements={"id": "\d+"})
     * @param Request $request
     * @param int $id
     * @param string $slug
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function articleAction(Request $request, $id, $slug)
    {
        /** @var ArticleRepository $repo */
        $articleRepository = $this->getDoctrine()->getRepository(Article::class);

        /** @var Article|null $article */
        $article = $articleRepository->find($id);

        if ($article === null || !$article->getPublished()) {
            throw $this->createNotFoundException('L\'article n\'est pas disponible');
        }

        if ($article->getSlug() !== $slug) {
            return $this->redirectToRoute(
                $this->generateUrl(
                    'article_show',
                    ['slug' => $article->getSlug(),
                        'id' => $article->getId()]
                ));
        }

        $articleSideList = $articleRepository->getAll(3, 1);


        $comment = new Comment();
        $comment->setDate(new \DateTime());
        $comment->setAuthor($this->getUser());

        $form = $this->createFormBuilder($comment)
            ->add('content', TextareaType::class, [
                'label' => 'Laisser un commentaire',
                'trim' => true,
            ])
            ->add('save', SubmitType::class, array('label' => 'Envoyer'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $comment->setArticle($article);

            /** @var Comment $comment */
            $comment = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('article_show', ['id' => $id, 'slug' => $slug]);
        }

        return $this->render(':article:show.html.twig', [
            'article' => $article,
            'articleSideList' => $articleSideList,
            'commentList' => $article->getCommentList(),
            'commentForm' => $form->createView(),
        ]);

    }

    /**
     * @Route("/tous-les-articles/{page}", name="article_all", defaults={"page" = 1} )
     * @param $page
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function allArticleAction($page)
    {

        $paginationSize = $this->getParameter('app.pagination');

        $articleList = $this->getDoctrine()
            ->getRepository('AppBundle:Article')
            ->getAll((int)$paginationSize, (int)$page);

        $articleCount = $this->getDoctrine()
            ->getRepository('AppBundle:Article')
            ->getAllCount();

        $router = $this->get('router');
        $pagination = new Pagination();
        if ($page > 1) {
            $pagination
                ->setPreviousPageWording('Articles prédédents')
                ->setPreviousPageUrl(
                    $router->generate('article_all', ['page' => $page - 1])
                );
        }
        if ($page < ceil($articleCount / $paginationSize)) {
            $pagination
                ->setNextPageWording('Articles suivants')
                ->setNextPageUrl(
                    $router->generate('article_all', ['page' => $page + 1])
                );
        }

        return $this->render('article/all.html.twig', [
            'articleList' => $articleList,
            'pagination' => $pagination,
        ]);

    }
}