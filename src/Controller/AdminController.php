<?php

  namespace App\Controller;

  use App\Entity\User;
  use App\Form\EditUserType;
  use App\Form\RegistrationFormType;
  use Doctrine\ORM\EntityManagerInterface;
  use Doctrine\Persistence\ManagerRegistry;
  use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
  use Symfony\Component\HttpFoundation\Request;
  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
  use Symfony\Component\Routing\Annotation\Route;

  #[Route('/$2a$12$99iZHSovZPM6xvwAMeFeoONS69pt45Udgplt4DAdT7fDQvX12nBte/admin', name: 'dashboard_')]
  class AdminController extends AbstractController
  {
    private $_em;

    public function __construct(ManagerRegistry $doctrine)
    {
      $this->_em = $doctrine;
    }


    #[Route('/users', name: 'index')]
    public function index(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
      if (!$this->isGranted('ROLE_ADMIN')) {
        if (!$this->isGranted('ROLE_DEVELOPER')) {
          return $this->redirectToRoute('dashboard_logout');
        } else {
          return $this->redirectToRoute('dashboard_my_profile');
        }
      }

      // Slide form handler for adding users
      unset($user);
      unset($form);
      $user = new User();
      $form = $this->createForm(RegistrationFormType::class, $user);
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
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

      // User repository for getting all users
      $userRepository = $entityManager->getRepository(User::class);
      $users = $userRepository->findAll();

      // Search query top of table


      if($request->query->has('submitSearch') && !empty($request->query->get('user-table-search'))) {
        $searchParam = $request->query->get('user-table-search');
        if($searchParam == "Developer") {
          $searchParam = "ROLE_DEVELOPER";
        }
        if($searchParam == "Administrator") {
          $searchParam = "ROLE_ADMIN";
        }
        $users = $userRepository->getSearchedUsersQuery($searchParam);

        return $this->render('admin/index.html.twig', [
          'registrationForm' => $form->createView(),
          'users' => $users
        ]);
      }

      return $this->render('admin/index.html.twig', [
        'registrationForm' => $form->createView(),
        'users' => $users
      ]);
    }

    #[Route('/users/{id}', name: 'admin_view_user', methods: ['GET', 'PUT'])]
    public function viewUser($id, Request $request): Response
    {
      if (!$this->isGranted('ROLE_ADMIN')) {
        if (!$this->isGranted('ROLE_DEVELOPER')) {
          return $this->redirectToRoute('dashboard_logout');
        } else {
          return $this->redirectToRoute('dashboard_my_profile');
        }
      }
      unset($user);

      $userRepository = $this->_em->getRepository(User::class);
      $user = $userRepository->find($id);
      $userTasks = $user->getTasks();
      $totalHours = 0;
      foreach($userTasks as $userTask) {
        $totalHours += date_timestamp_get($userTask->getTime());
//        dump($totalHours);
      }
      $totalHours = gmdate("H:i:s", $totalHours);


      $editForm = $this->createForm(EditUserType::class, $user, [
        'method' => 'PUT',
        'action' => $this->generateUrl('dashboard_admin_view_user', ['id' => $id])
      ]);

//      dd($request->request->get("_method") );

      $editForm->handleRequest($request);
//      dd($editForm);

      if($editForm->isSubmitted() && $editForm->isValid() && $request->request->get("_method") == "PUT")
      {
        $editedUser = $editForm->getData();

        $userRepository->add($editedUser, true);

        return $this->redirectToRoute('dashboard_admin_view_user', ['id' => $id]);
      }


      return $this->render('admin/user-profile.html.twig', [
        'user' => $user,
        'userTasks' => $userTasks,
        'userTotalHours' => $totalHours,
        'editForm' => $editForm->createView()
      ]);
    }





    #[Route('/user-delete', name: 'user_delete')]
    public function deleteUser(Request $request): Response
    {
      if (!$this->isGranted('ROLE_ADMIN'))
      {
        return $this->redirectToRoute('dashboard_my_profile');
      }
      $userRepository = $this->_em->getRepository(User::class);
      $userId = $request->request->get("userId");
      $user = $userRepository->find($userId);
      if($user == null) {
        throw $this->createNotFoundException("User not found (for deletion)");
      }
      $userRepository->remove($user, true);
//      dd($user);

      return $this->redirectToRoute('dashboard_index');
    }


  }
