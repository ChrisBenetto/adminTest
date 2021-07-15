<?php

namespace App\Controller;

use App\Entity\News;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
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
    public function login(Request $request): Response
    {
        $user = new User();

        $form = $this->createFormBuilder($user)
            ->add('email', EmailType::class, ['label' => 'Email'])
            ->add('password', PasswordType::class, ['label' => 'Mot de passe'])
            ->add('submit', SubmitType::class, ['label' => "S'inscrire !"])
            ->getForm();

        $form->handleRequest($request);
        return $this->render('blog/login.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/signup" , name="signup" , methods= {"GET","POST"})
     */
    public function signup(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();

        $form = $this->createFormBuilder($user)
            ->add('username', TextType::class, ['label' => "Votre nom d'utilisateur"])
            ->add('email', EmailType::class, ['label' => 'Email'])
            ->add('password', PasswordType::class, ['label' => 'Mot de passe'])
            ->add('submit', SubmitType::class, ['label' => "S'inscrire !"])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setIsAdmin(false);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('login');
        }
        return $this->render('blog/signup.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
