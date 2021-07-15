<?php

namespace App\Controller;

use App\Entity\News;
use Symfony\Component\Form\Form;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
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
     * @Route("/admin/create", name="createNews")
     * @Route("/admin/{id}/edit", name="editNews")
     */
    public function form(News $news = null, Request $request, EntityManagerInterface $manager): Response
    {
        if (!$news) {
            $news = new News();
        }

        $form = $this->createFormBuilder($news)
            ->add('title', TextType::class, ['label' => 'Titre de l\'actualité'])
            ->add('slug', TextType::class, ['label' => 'Résumé'])
            ->add('content', TextareaType::class, ['label' => 'Contenu de l\'actualité'])
            ->add('publicationDate', DateType::class, ['label' => 'Date de publication'])
            ->add('publicationEnding', DateType::class, ['label' => 'Date de fin de publication'])
            /*->add('file', FileType::class, ['label' => 'Image de news']) */
            ->add('submit', SubmitType::class, ['label' => 'Créer une news'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$news->getId()) {
                $news->setcreateAt(new \DateTime());
            } else {
                $news->setUpdateAt(new \DateTime());
            }
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($form);
            $manager->flush();
            return $this->redirectToRoute('/admin');
        }
        return $this->render('admin/create.html.twig', [
            'form' => $form->createView(),
            'editMode' => $news->getId() !== null
        ]);
    }
    /**
     * @Route("/admin/delete/{id}", name="deleteNews")
     */
    public function deleteNews($id): Response
    {
        return $this->render('admin/index.html.twig');
    }
}
