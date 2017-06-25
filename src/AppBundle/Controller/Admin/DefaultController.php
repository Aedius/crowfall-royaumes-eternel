<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Category;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

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
    public function articleAction(Request $request)
    {

        $em = $this->get('doctrine.orm.default_entity_manager');

        // create a article and give it some dummy data for this example
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
                $this->addFlash('danger', 'something get wrong');
            }
        }

        return $this->render('global/form.html.twig', [
            'title' => 'Category',
            'submit_button' => 'save category',
            'form' => $form->createView(),
        ]);
    }
}