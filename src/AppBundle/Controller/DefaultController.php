<?php

namespace AppBundle\Controller;

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

        // replace this example code with whatever you need
        return $this->render('index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
            'archetypeList' => $archetypeList,
        ]);
    }
}
