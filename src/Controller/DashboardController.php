<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/$2a$12$99iZHSovZPM6xvwAMeFeoONS69pt45Udgplt4DAdT7fDQvX12nBte', name: 'dashboard_')]
class DashboardController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
      if(!$this->isGranted('ROLE_ADMIN'))
        return $this->redirectToRoute('dashboard_logout');


      unset($user);
      unset($form);
      $user = new User();
      $form = $this->createForm(RegistrationFormType::class, $user);
      $form->handleRequest($request);

      if($form->isSubmitted() && $form->isValid()) {
        $user->setPassword(
          $userPasswordHasher->hashPassword(
            $user,
            $form->get('plainPassword')->getData()
          )
        );

        $entityManager->persist($user);
        $entityManager->flush();
        // do anything else you need here, like send an email

        return $this->redirectToRoute('dashboard_index');
      }


      return $this->render('dashboard/index.html.twig', [
        'registrationForm' =>  $form->createView()
      ]);
    }
}
