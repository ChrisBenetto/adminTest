<?php

namespace App\Controller;

use App\Entity\News;
use App\Entity\Picture;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin",methods={"GET"})
     */
    public function homeAdmin(): Response
    {
        $repo = $this->getDoctrine()->getRepository(News::class);
        $news = $repo->findAll();
        return $this->render('admin/index.html.twig', [
            "news" => $news
        ]);
    }
    /**
     * @Route("/admin/createCategory", name="createCategory" , methods={"GET","POST"})
     * @Route("/admin/{id}/editCategory", name="editCategory", methods={"GET","PUT" , "POST"})
     */
    public function formCategory(Category $category = null, Request $request, EntityManagerInterface $manager): Response
    {
        if (!$category) {
            $category = new Category();
        }
        $form = $this->createFormBuilder($category)
            ->add('title', TextType::class, ['label' => 'Titre de la catégorie'])
            ->add('slug', TextType::class, ['label' => 'Tag'])
            ->add('submit', SubmitType::class, ['label' => 'Envoyez !'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$category->getId()) {
                $category->setCreateAt(new \DateTime());
            }
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($category);
            $manager->flush();
            return $this->redirectToRoute('admin');
        }
        return $this->render('admin/createCategory.html.twig', [
            'form' => $form->createView(),
            'editMode' => $category->getId() !== null
        ]);
    }
    /**
     * @Route("/admin/createNews", name="createNews" , methods={"GET","POST"})
     * @Route("/admin/{id}/edit", name="editNews", methods={"GET","PUT" , "POST"})
     */
    public function form(News $news = null, Request $request, EntityManagerInterface $manager): Response
    {
        if (!$news) {
            $news = new News();
        }
        $form = $this->createFormBuilder($news)
            ->add('title', TextType::class, ['label' => 'Titre de l\'actualité'])
            ->add('slug', TextType::class, ['label' => 'Résumé'])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'title',
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('content', TextareaType::class, ['label' => 'Contenu de l\'actualité'])
            ->add('publicationDate', DateType::class, ['label' => 'Date de publication'])
            ->add('publicationEnding', DateType::class, ['label' => 'Date de fin de publication'])
            ->add('imageFile', FileType::class, ['label' => 'Image principale'])
            ->add('pictures', FileType::class, [
                'multiple' => true,
                'mapped' => false
            ])
            ->add('submit', SubmitType::class, ['label' => 'Envoyez !'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $pictures = $form->get('pictures')->getData();

            foreach ($pictures as $picture) {
                $pictureInRepo = md5(uniqid()) . '.' . $picture->guessExtension();
                $picture->move(
                    $this->getParameter('images_directory'),
                    $pictureInRepo
                );
                $img = new Picture();
                $img->setCreateAt(new \DateTime());
                $img->setUrl($pictureInRepo);
                $img->setPlace(1);
                $news->addPicture($img);
            }

            if (!$news->getId()) {
                $news->setcreateAt(new \DateTime());
            } else {
                $news->setUpdateAt(new \DateTime());
            }
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($news);
            $manager->flush();
            return $this->redirectToRoute('admin');
        }
        return $this->render('admin/create.html.twig', [
            'form' => $form->createView(),
            'editMode' => $news->getId() !== null
        ]);
    }
    /**
     * @Route("/admin/{id}/delete", name="deleteNews", methods={"DELETE"})
     */
    public function deleteNews($id, EntityManagerInterface $manager): Response
    {
        $repo = $this->getDoctrine()->getRepository(News::class);
        $newsById = $repo->find($id);
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($newsById);
        $manager->flush();
        return $this->redirectToRoute('admin');
    }
}
