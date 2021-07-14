<?php

namespace App\Controller;

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
        return $this->render('blog/home.html.twig');
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
