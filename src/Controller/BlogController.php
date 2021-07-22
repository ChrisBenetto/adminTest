<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\News;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class BlogController extends AbstractController
{
    /**
     * @Route("/" , name="home")
     */
    public function index(): Response
    {
        $repo = $this->getDoctrine()->getRepository(News::class);
        $newsOfDay = $repo->findBy(
            ['publicationDate' => new \DateTime('now')]
        );

        return $this->render('blog/home.html.twig', [
            'news' => $newsOfDay
        ]);
    }
    /**
     * @Route("/news/{id}" , name="showNews",methods={"GET"}, requirements={"id"="\d+"}))
     */
    public function showNews($id): Response
    {
        $repo = $this->getDoctrine()->getRepository(News::class);
        $newsById = $repo->find($id);
        $categories = $newsById->getCategories();
        $pictures = $newsById->getPictures();

        return $this->render('blog/news.html.twig', [
            "news" => $newsById,
            "categories" => $categories,
            "pictures" => $pictures
        ]);
    }
    /**
     * @Route("/category/{id}" , name="showNewsByCategory",methods={"GET"}, requirements={"id"="\d+"}))
     */
    public function showNewsByCategory($id): Response
    {
        $repo = $this->getDoctrine()->getRepository(Category::class);
        $categoryById = $repo->find($id);
        $news = $categoryById->getNews();

        return $this->render('blog/categoryNews.html.twig', [
            "news" => $news,
            "category" => $categoryById
        ]);
    }
}
