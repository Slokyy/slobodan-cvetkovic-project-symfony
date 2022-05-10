<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/$2a$12$99iZHSovZPM6xvwAMeFeoONS69pt45Udgplt4DAdT7fDQvX12nBte', name: 'dashboard_')]
class DashboardController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
      if(!$this->isGranted('ROLE_ADMIN'))
        return $this->redirectToRoute('dashboard_logout');
        return $this->render('dashboard/index.html.twig');
    }
}
