<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/$2a$12$99iZHSovZPM6xvwAMeFeoONS69pt45Udgplt4DAdT7fDQvX12nBte', name: 'dashboard_')]
class LogoutController extends AbstractController
{
    #[Route('/logout', name: 'logout')]
    public function index()
    {

    }
}
