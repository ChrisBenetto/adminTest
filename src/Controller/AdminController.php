<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function homeAdmin(): Response
    {
        return $this->render('admin/index.html.twig');
    }
    /**
     * @Route("/admin/create", name="create")
     */
    public function createNews(): Response
    {
        return $this->render('admin/index.html.twig');
    }
    /**
     * @Route("/admin/edit/{id}", name="edit")
     */
    public function editNews($id): Response
    {
        return $this->render('admin/index.html.twig');
    }
    /**
     * @Route("/admin/delete/{id}", name="delete")
     */
    public function deleteNews($id): Response
    {
        return $this->render('admin/index.html.twig');
    }
}
