<?php

  namespace App\Controller;

  use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Component\Routing\Annotation\Route;
  use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

//symfony-final-project - bcrypt
  #[Route('/$2a$12$99iZHSovZPM6xvwAMeFeoONS69pt45Udgplt4DAdT7fDQvX12nBte', name: 'dashboard_')]
  class LoginController extends AbstractController
  {
    #[Route('/login', name: 'login')]
    public function index(AuthenticationUtils $auth): Response
    {

      if($this->getUser())
        return $this->redirectToRoute("dashboard_index");

      $error = $auth->getLastAuthenticationError();
      $lastUserError = $auth->getLastUsername();

//      dd($this->getUser());

      return $this->render('login/index.html.twig', [
        'error' => $error,
        'last_username' => $lastUserError
      ]);
    }
  }
