<?php

namespace AppBundle\Controller\Front;

use AppBundle\Component\Helper\Pagination;
use AppBundle\Entity\Version;
use AppBundle\Repository\VersionRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class VersionController extends BaseController
{
    /**
     * @Route("/version/{id}/{slug}/{page}", name="version_show", requirements={"id": "\d+"} , defaults={"page" = 1} )
     * @param $id
     * @param $slug
     * @param $page
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function versionAction($id, $slug, $page)
    {
        /** @var VersionRepository $repo */
        $versionRepository = $this->getDoctrine()->getRepository(Version::class);

        /** @var Version|null $version */
        $version = $versionRepository->find($id);

        if (null === $version) {
            $this->redirectToRoute('homepage');
        }

        if ($version->getSlug() !== $slug) {
            return $this->redirectToRoute(
                $this->generateUrl(
                    'version_show',
                    [
                        'slug' => $version->getSlug(),
                        'id' => $version->getId()
                    ]
                ));
        }

        $paginationSize = $this->getParameter('app.pagination');

        $articleList = $this->getDoctrine()
            ->getRepository('AppBundle:Article')
            ->getByVersion($version, (int)$paginationSize, (int)$page);

        $articleCount = $this->getDoctrine()
            ->getRepository('AppBundle:Article')
            ->getCountByVersion($version);

        $router = $this->get('router');
        $pagination = new Pagination();
        if ($page > 1) {
            $pagination
                ->setPreviousPageWording('Articles "' . $version->getName() . '" prédédents')
                ->setPreviousPageUrl(
                    $router->generate('version_show', ['id' => $id, 'slug' => $slug, 'page' => $page - 1])
                );
        }
        if ($page < ceil($articleCount / $paginationSize)) {
            $pagination
                ->setNextPageWording('Articles "' . $version->getName() . '" suivants')
                ->setNextPageUrl(
                    $router->generate('version_show', ['id' => $id, 'slug' => $slug, 'page' => $page + 1])
                );
        }

        return $this->render(':version:show.html.twig', [
            'version' => $version,
            'articleList' => $articleList,
            'pagination' => $pagination,

        ]);

    }
}