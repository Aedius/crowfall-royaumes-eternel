<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Category;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
     * @Route("admin/category", name="admin-category-edit")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function categoryAction(Request $request)
    {

        $em = $this->get('doctrine.orm.default_entity_manager');

        // create a category and give it some dummy data for this example
        $category = new Category();

        $fb = $this->createFormBuilder($category);

        $fb->add('name', TextType::class, [
            'label' => 'name',
        ]);

        $fb->add('slug', TextType::class, [
            'label' => 'slug',
        ]);

        $fb->add('description', TextareaType::class, [
            'label' => 'description de la category',
        ]);

        $fb->add('imageFile', VichImageType::class, [
            'required' => true,
            'allow_delete' => true,
            'download_label' => function (Category $category) {
                return $category->getSlug().'/image';
            },
            'download_uri' => true,
            'image_uri' => true,
        ]);

        $form = $fb->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $em->persist($category);
                $em->flush();

                $this->addFlash('success', 'Category enregistré avec succès');
            } catch (UniqueConstraintViolationException $e) {
                $this->addFlash('warning','not unique name or slug');
            }catch (\Exception $e) {
                $this->addFlash('danger', 'something get wrong : '. $e->getMessage());
            }
        }

        return $this->render('global/form.html.twig', [
            'title' => 'Category',
            'submit_button' => 'save category',
            'form' => $form->createView(),
        ]);
    }
}