<?php

namespace App\Controller;

use App\Entity\News;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    /**
     *@Route("/" , name="home")
     */
    public function index(): Response
    {
        $repo = $this->getDoctrine()->getRepository(News::class);
        $newsOfDay = $repo->findBy(
            ['publicationDate' => new \DateTime('now')]
        );
        return $this->render('blog/home.html.twig', [
            'newsOfDay' => $newsOfDay
        ]);
    }
    /**
     * @Route("/news/{id}" , name="showNews",methods={"GET"}, requirements={"id"="\d+"}))
     */
    public function showNews($id): Response
    {
        $repo = $this->getDoctrine()->getRepository(News::class);
        $newsById = $repo->find($id);
        return $this->render('admin/news.html.twig', [
            "news" => $newsById
        ]);
    }
    /**
     *@Route("/login" , name="login" , methods= {"GET","POST"})
     */
    public function login(): Response
    {
        return $this->render('blog/login.html.twig');
    }
    /**
     * @Route("/signup" , name="signup" , methods= {"GET","POST"})
     */
    public function signup(): Response
    {
        return $this->render('blog/signup.html.twig');
    }
}
