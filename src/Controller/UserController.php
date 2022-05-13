<?php

  namespace App\Controller;

  use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Component\Routing\Annotation\Route;

  #[Route('$2a$12$99iZHSovZPM6xvwAMeFeoONS69pt45Udgplt4DAdT7fDQvX12nBte', name: "dashboard_")]
  class UserController extends AbstractController
  {
//
//    #[Route('/dashboard/my-profile', name: 'my_profile')]
//    public function myProfile(): Response
//    {
//      return $this->render('dashboard/my-profile.html.twig');
//    }

    #[Route('/dashboard/my-profile', name: 'my_profile')]
    public function index(): Response
    {
      return $this->render('dashboard/my-profile.html.twig');
    }

  }
