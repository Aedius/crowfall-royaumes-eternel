<?php

namespace AppBundle\Controller\Writer;

use AppBundle\Entity\Article;
use AppBundle\Entity\Category;
use AppBundle\Entity\Version;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Vich\UploaderBundle\Form\Type\VichImageType;

/**
 * Class DefaultController
 * @package AppBundle\Controller\Writer
 */
class DefaultController extends Controller
{

    /**
     * @Route("writer/article", name="writer-article-edit")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function articleAction(Request $request)
    {
        $em = $this->get('doctrine.orm.default_entity_manager');

        // create a article and give it some dummy data for this example
        $article = new Article();

        $fb = $this->createFormBuilder($article);

        $fb->add('titre', TextType::class, [
            'label' => 'titre',
        ]);

        $fb->add('slug', TextType::class, [
            'label' => 'slug',
        ]);

        $fb->add('content', TextareaType::class, [
            'label' => 'contenu',
        ]);

        $fb->add('imageFile', VichImageType::class, [
            'required' => true,
            'allow_delete' => true,
            'download_label' => function (Article $article) {
                return $article->getSlug().'/image';
            },
            'download_uri' => true,
            'image_uri' => true,
        ]);

        $fb->add('published', ChoiceType::class, [
            'choices' => [
                'Publier' => true,
                'Non Publié' => false,
            ],
            'label' => 'publié',
        ]);

        $fb->add('category', EntityType::class, [
            'label' => 'Catégorie',
            'class' => Category::class
        ]);

        $fb->add('version', EntityType::class, [
            'class' => Version::class,
            'label' => 'Version',
        ]);


        $article->setAuthor($this->getUser());

        $form = $fb->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($article->getPublished() && !$article->getPublishedAt()) {
                $article->setPublishedAt(new \DateTime());
            }


            if ($form->isSubmitted() && $form->isValid()) {
                try {
                    $em->persist($article);
                    $em->flush();

                    $this->addFlash('success', 'article enregistré avec succès');
                } catch (UniqueConstraintViolationException $e) {
                    $this->addFlash('warning', 'not unique title or slug');
                } catch (\Exception $e) {
                    $this->addFlash('danger', 'something gores wrong');
                }
            }
        }

        return $this->render('global/form.html.twig', [
            'title' => 'Article',
            'form' => $form->createView(),
            'submit_button' => 'save article',
        ]);
    }
}